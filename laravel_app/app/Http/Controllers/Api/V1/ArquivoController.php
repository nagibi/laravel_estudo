<?php

namespace App\Http\Controllers\Api\V1;

use App\DemonstrativoFaturamento;
use App\Arquivo;
use App\Faturamento;
use App\Empresa;
use App\Demo;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Illuminate\Support\Facades\DB;

class ArquivoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function total()
    {
        $total = Arquivo::all()->count();
        return $this->response(200, "MSG000151", $total);
    }

    public function pesquisar(Request $request)
    {
        $qtdRegistros = is_null($request->qtdRegistros) || $request->qtdRegistros == -1 ? 999999999999999 : $request->qtdRegistros;
        $pageCurrent = $request->get('pageCurrent');
        $orderBy = is_null($request->orderBy) ? 'id' : $request->orderBy;
        $orderType = is_null($request->orderType) ? 'desc' : $request->orderType;
        $query = Arquivo::query();

        //nome
        if (!is_null($request->get('nome'))) {
            $query = $query->where('arquivos.nome', 'like', '%' . $request->get('nome') . '%');
        }

        //nomeOriginal
        if (!is_null($request->get('nomeOriginal'))) {
            $query = $query->where('arquivos.nomeOriginal', 'like', '%' . $request->get('nomeOriginal') . '%');
        }

        //id
        if (!is_null($request->get('id'))) {
            $query = $query->where('arquivos.id', '=', $request->get('id'));
        }

        //caminho
        if (!is_null($request->get('caminho'))) {
            $query = $query->where('caminho', (boolean) json_decode(strtolower($request->get('caminho'))));
        }

        //extensao
        if (!is_null($request->get('status'))) {
            $query = $query->where('status', '=', $request->get('status'));
        }

        //tipo
        if (!is_null($request->get('tipo'))) {
            $query = $query->where('tipo', '=', $request->get('tipo'));
        }

        //extensao
        if (!is_null($request->get('extensao'))) {
            $query = $query->where('extensao', (boolean) json_decode(strtolower($request->get('extensao'))));
        }

        //dataCriacao
        $dataCriacaoInicial = $request->get('dataCriacaoInicial');
        $dataCriacaoFinal = $request->get('dataCriacaoFinal');

        if (!is_null(($dataCriacaoInicial)) || !is_null(($dataCriacaoFinal))) {

            if (!is_null(($dataCriacaoInicial))) {
                $inicial = Carbon::parse($dataCriacaoInicial);
                $query = $query->where('created_at', ">=", $inicial);
            }

            if (!is_null(($dataCriacaoFinal))) {

                $final = Carbon::parse($dataCriacaoFinal)->endOfDay();
                $query = $query->where('created_at', "<=", $final);
            }
        }

        //dataCriacao
        $dataAtualizacaoInicial = $request->get('dataAtualizacaoInicial');
        $dataAtualizacaoFinal = $request->get('dataAtualizacaoFinal');

        if (!is_null(($dataAtualizacaoInicial)) || !is_null(($dataAtualizacaoFinal))) {

            if (!is_null(($dataAtualizacaoInicial))) {
                $inicial = Carbon::parse($dataAtualizacaoInicial);
                $query = $query->where('updated_at', ">=", $inicial);
            }

            if (!is_null(($dataAtualizacaoFinal))) {

                $final = Carbon::parse($dataAtualizacaoFinal)->endOfDay();
                $query = $query->where('updated_at', "<=", $final);
            }
        }

        $totalRecordsFilter = $query->get()->Count();

        $records = $query
            ->orderBy($orderBy, $orderType)
            ->select('arquivos.*')
            ->skip($pageCurrent)
            ->take($qtdRegistros)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {
            $id = $record->id;
            $nome = $record->nome;
            $nomeOriginal = $record->nomeOriginal;
            $tipo = $record->tipo;
            $caminho = $record->caminho;
            $extensao = $record->extensao;
            $status = $record->status;
            $confirmacaoEmail = $record->confirmacaoEmail;
            $dataCriacao = $record->dataCriacao;
            $dataAtualizacao = $record->dataAtualizacao;

            $data_arr[] = array(
                "id" => $id,
                "nome" => $nome,
                "nomeOriginal" => $nomeOriginal,
                "tipo" => $tipo,
                "caminho" => $caminho,
                "extensao" => $extensao,
                "status" => $status,
                "confirmacaoEmail" => $confirmacaoEmail,
                "dataCriacao" => $dataCriacao,
                "dataAtualizacao" => $dataAtualizacao,
            );
        }

        return $this->response(200, "MSG000151", ['total' => $totalRecordsFilter, 'data' => $data_arr]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function cadastrar(Request $request)
    {
        try {

            DB::beginTransaction();
                
                $arquivos = array(); 
                $arquivosModel = array();

                if ($request->hasFile('file')) {

                    foreach ($request->file('file') as $file) {

                        if ($file->isValid()) {
                            
                            //salvando arquivo localmente
                            $extensao = $file->getClientOriginalExtension();
                            $nome = time() . Str::random(5) . '.' . $file->getClientOriginalExtension();
                            $nomeOriginal = $file->getClientOriginalName();
                            $destino = 'arquivos';
                            $file->move($destino, $nome);
                            $arquivos[] = $destino . "/" . $nome;

                            /****************
                             * Salvando no DB
                             ****************/
                            
                            //Arquivo
                            $arquivo = new Arquivo();
                            $arquivo->nome = $nome;
                            $arquivo->nomeOriginal = $nomeOriginal;
                            // $arquivo->tipo = $request->tipo;
                            $arquivo->caminho = asset('/' . $destino . "/" . $nome);
                            $arquivo->extensao = $extensao;
                            $arquivo->status = 2;
                            $arquivo->usuarioCriacaoId = JWTAuth::user()->id;
                            $arquivo->usuarioAtualizacaoId = JWTAuth::user()->id;
                            $arquivo->save();

                            array_push($arquivosModel, $arquivo);
                        }
                    }

                    DB::commit();

                    return $this->response(201, "MSG000151", $arquivosModel);

                }else{

                    DB::rollback();
                    
                    //deletando arquivos gravados localmente
                    foreach ($arquivos as &$arquivo) {
                        unlink($arquivo);
                    }

                    return $this->response(201, "MSG000071", null);
                };


        } catch (Exception $e) {
            return $this->response(404, "MSG000131");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function obter($id)
    {
        try {

            if (is_null($id)) {
                return $this->response(404, "MSG000152");
            }

            $arquivo = Arquivo::find($id);

            if (!is_null(($arquivo))) {

                return $this->response(200, "MSG000151", $arquivo);

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131");
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletar($id)
    {

        try {

            if (is_null($id)) {
                return $this->response(404, "MSG000152");
            }

            $user = User::find($id);

            if (!is_null(($user))) {

                $user->delete();
                return $this->response(200, "MSG000144");

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131");
        }
    }

    public function nome($id)
    {
        try {

            if (is_null($id)) {
                return $this->response(404, "MSG000152");
            }

            $arquivo = Arquivo::select('nomeOriginal')->where('id',$id)->first();

            if (!is_null(($arquivo))) {

                return $this->response(200, "MSG000144", $arquivo->nomeOriginal);

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131");
        }

    }

    public function status(Request $request, $id)
    {
        try {

            if (is_null($id)) {
                return $this->response(404, "MSG000152");
            }

            $arquivo = Arquivo::find($id);

            if (!is_null(($arquivo))) {

                $arquivo->status = $request->status;
                $arquivo->save();

                return $this->response(200, "MSG000144", $arquivo);

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131");
        }

    }

}
