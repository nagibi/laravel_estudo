<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmarEmail;
use App\Mail\ResetarSenha;
use App\Role;
use App\Usuario;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mockery\Expectation;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

class AuthController extends Controller
{
    public function permissoes()
    {
        try {

            $usuario = JWTAuth::user();
            $grupo = $usuario->roles->first();
            $usuario->load('roles.permissions');
            $permissoes = $usuario->roles->first()->permissions;

            if ($grupo != null && count($permissoes) == 0) {
                return $this->response(404, "MSG000112");
            } else {
                return $this->response(200, "MSG000144", ['grupo' => $grupo, 'permissoes' => $permissoes]);
            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $ex->getMessage());
        }
    }

    public function usuarioLogado()
    {
        try {

            $usuario = JWTAuth::user();

            if (!is_null(($usuario))) {

                return $this->response(200, null, $usuario);

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }

    }

    public function atualizarPerfil(Request $request)
    {
        try {

            $usuario = JWTAuth::user();

            if (is_null(($usuario))) {
                return $this->response(404, "MSG000112");
            }

            //validar
            $validar = Usuario::$atualizarPerfilValidar;
            $validar['email'] = $validar['email'] . '|unique:usuarios' . ',email,' . $usuario->id;
            $validator = Validator::make($request->all(), $validar, Usuario::$validarMensagens);

            if ($validator->fails()) {
                return $this->erros("MSG000071", $validator);
            }

            $usuario->nome = $request->nome;
            $usuario->email = $request->email;
            $usuario->dataNascimento = $request->dataNascimento;
            $usuario->sexo = $request->sexo;
            $usuario->usuarioAtualizacaoId = JWTAuth::user()->id;
            $usuario->arquivoId = !is_null($request->arquivoId) ? $request->arquivoId : null;
            $usuario->save();

            return $this->response(200, "MSG000151", $usuario);

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }

    }

    public function alterarSenha(Request $request)
    {
        try {

            //validar
            $validator = Validator::make(
                $request->all(),
                Usuario::$alterarSenhaValidar,
                Usuario::$validarMensagens);

            if ($validator->fails()) {
                return $this->erros("MSG000071", $validator, "MSG000126");
            }

            $usuario = JWTAuth::user();

            if (!Hash::check($request->get('senhaAtual'), $usuario->password)) {
                return $this->erro(
                    "MSG000071",
                    "senhaAtual",
                    "MSG000126");
            }

            if (!is_null(($usuario))) {

                $usuario->password = Hash::make($request->novaSenha);
                $usuario->usuarioAtualizacaoId = $usuario->id;
                $usuario->save();

                return $this->response(200, "MSG000151", $usuario);

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            Usuario::$loginValidar,
            Usuario::$validarMensagens);

        if ($validator->fails()) {
            return $this->response(404, "MSG000034", $validator->errors()->all());
        }

        try {

            $input = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($input)) {

                return $this->response(401, "MSG000034");
            }

            $usuario = JWTAuth::user();
            $status = $usuario->status;

            switch ($status) {
                case 2: //Inativo
                    return $this->response(401, "MSG000028");
                    break;
                case 3: //Bloqueado
                    return $this->response(401, "MSG000031");
                    break;
                case 4: //Expirado
                    return $this->response(401, "MSG000030");

                    break;
                case 5: //EmVerificacao
                    return $this->response(401, "MSG000029");

                    break;

                default:
                    if ($usuario->confirmacaoEmail == 0) {
                        return $this->response(400, "MSG000028");
                    } else {

                        // $token1 = compact('token');

                        // $roles = $usuario->roles;
                        // foreach ($roles->perms  as $permission) {

                        // }

                        $grupo = $usuario->roles->first();
                        $usuario->load('roles.permissions');
                        $permissoes = $usuario->roles->first()->permissions;

                        $funcionalidades = array();
                        foreach ($permissoes as $record) {
                            $funcionalidades[] = $record->name;
                        }

                        return $this->response(
                            200,
                            "MSG000032",
                            [
                                'id' => $usuario->id,
                                'arquivoId' => $usuario->arquivoId,
                                'arquivoCaminho' => !is_null($usuario->arquivo) ? $usuario->arquivo->caminho : null,
                                'nome' => $usuario->nome,
                                'email' => $usuario->email,
                                'autenticado' => true,
                                'tokenExpira' => Auth::guard('api')->factory()->getTTL(),
                                'token' => "Bearer " . $token,
                                'grupo' => $grupo,
                                'funcionalidades' => $funcionalidades,
                            ]);
                    }
                    break;
            }

        } catch (JWTException $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }

    }

    public function cadastrar(Request $request)
    {
        try {

            //validando form
            $validator = Validator::make(
                $request->all(),
                Usuario::$cadastrarValidar,
                Usuario::$validarMensagens);

            if ($validator->fails()) {
                return $this->response(200, "MSG000034", $validator->errors()->all());
            }

            //validando e-mail
            $usuario = Usuario::where('email', '=', $request->email)->first();

            if (!is_null(($usuario))) {
                return $this->response(200, "MSG000259");
            }

            $usuario = new Usuario;
            $usuario->nome = $request->nome;
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->senha);
            $usuario->token = Str::random(32);
            $usuario->dataNascimento = new DateTime();
            $usuario->status = 1;
            $usuario->sexo = 1;
            $usuario->usuarioCriacaoId = 1;
            $usuario->usuarioAtualizacaoId = 1;
            $usuario->confirmacaoEmail = false;
            $usuario->save();

            $admin = Role::findOrFail(1);
            $usuario->roles()->attach($admin);
            $usuario->save();

            FacadesMail::send(new ConfirmarEmail($usuario));

            return $this->response(201, "MSG000039", $usuario);

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function confirmarEmail($token)
    {
        try {

            $usuario = Usuario::where('token', $token)->first();

            // if (!$usuario->isEmpty()) {
            if (!is_null(($usuario))) {

                $usuario->confirmacaoEmail = true;
                $usuario->status = 1;
                $usuario->usuarioAtualizacaoId = $usuario->id;
                $usuario->save();

                return $this->response(200, "MSG000260", $usuario);

            } else {

                return $this->response(404, "MSG000041");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function resetarSenha(Request $request)
    {

        try {
            $validator = Validator::make(
                $request->all(),
                Usuario::$resetarSenhaValidar,
                Usuario::$validarMensagens);

            if ($validator->fails()) {
                return $this->response(404, "MSG000034", $validator->errors()->all());
            }

            $email = $request->email;
            $usuario = Usuario::where('email', $email)->first();

            if (is_null(($usuario))) {

                return $this->response(404, "MSG000041");

            } else if ($usuario->status == 0) {

                return $this->response(200, "MSG000044");

            } else {
                try {

                    $novaSenha = Str::random(6);
                    $usuario->password = Hash::make($novaSenha);
                    $usuario->save();

                    $usuario->password = $novaSenha;

                    FacadesMail::send(new ResetarSenha($usuario));

                    return $this->response(200, "MSG000047", $usuario);

                } catch (Exception $ex) {

                    return $this->response(404, "MSG000046");
                }
            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {

            JWTAuth::invalidate(JWTAuth::getToken());
            Auth::guard('api')->logout();
            Auth::logout();

            return $this->response(200, "MSG000047");

        } catch (Expectation $ex) {
            return $this->response(404, "MSG000131", $ex);
        }
    }

    public function refresh()
    {
        try {

            return $this->respondWithToken(Auth::refresh());
            return $this->response(200, "MSG000047", null);

        } catch (Expectation $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    protected function token($token)
    {
        return $this->response(404, "MSG000131", [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ], "Token");
    }

    // public function createRole(Request $request)
    // {
    //     $role = new Role();
    //     $role->name = $request->input('name');
    //     $role->save();

    //     return response()->json("created");
    // }

    // public function createPermission(Request $request)
    // {
    //     $viewUsuarios = new Permission();
    //     $viewUsuarios->name = $request->input('name');
    //     $viewUsuarios->save();

    //     return response()->json("created");
    // }

    // public function assignRole(Request $request)
    // {
    //     $usuario = Usuario::where('email', '=', $request->input('email'))->first();

    //     $role = Role::where('name', '=', $request->input('role'))->first();
    //     //$usuario->attachRole($request->input('role'));
    //     $usuario->roles()->attach($role->id);

    //     return response()->json("created");
    // }

    // public function attachPermission(Request $request)
    // {
    //     $role = Role::where('name', '=', $request->input('role'))->first();
    //     $permission = Permission::where('name', '=', $request->input('name'))->first();
    //     $role->attachPermission($permission);

    //     return response()->json("created");
    // }

}
