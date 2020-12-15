<?php

namespace App\Http\Controllers\Api\V1;

use App\Categoria;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

class CategoriaController extends Controller
{
    private $categoria;

    public function __construct(Categoria $categoria)
    {
        $status = 0;
        $this->categoria = $categoria;
        // $categoria = Categoria::with('categoria','subcategorias')->findOrFail(2);
        Categoria::with('categoria')
            ->with(['subcategorias' => function ($q) use ($status) {
                $q->where('status', $status);
            }])->findOrFail(2);

        // ProductCategory::with('children')
        // ->with(['products' => function ($q) use($SpecificID) {
        //     $q->whereHas('types', function($q) use($SpecificID) {
        //         $q->where('types.id', $SpecificID)
        //     });
        // }])
        // ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function total()
    {
        try {
            $total = Categoria::all()->count();
            return $this->response(200, "MSG000151", $total);
        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function pesquisar(Request $request)
    {
        try {
            $qtdRegistros = is_null($request->qtdRegistros) || $request->qtdRegistros == -1 ? 999999999999999 : $request->qtdRegistros;
            $pageCurrent = $request->get('pageCurrent');
            $orderBy = is_null($request->orderBy) ? 'id' : $request->orderBy;
            $orderType = is_null($request->orderType) ? 'desc' : $request->orderType;

            $query = Categoria::query();

            //nome
            if (!is_null($request->get('nome'))) {
                $query = $query->where('categorias.nome', 'like', '%' . $request->get('nome') . '%');
            }

            //id
            if (!is_null($request->get('id'))) {
                $query = $query->where('categorias.id', '=', $request->get('id'));
            }

            //status
            if (!is_null($request->get('status'))) {
                $query = $query->where('status', '=', $request->get('status'));
            }

            //dataCriacao
            $dataCriacaoInicial = $request->get('dataCriacaoInicial');
            $dataCriacaoFinal = $request->get('dataCriacaoFinal');

            if (!is_null(($dataCriacaoInicial)) || !is_null(($dataCriacaoFinal))) {

                if (!is_null(($dataCriacaoInicial))) {
                    $inicial = Carbon::parse($dataCriacaoInicial);
                    $query = $query->where('dataCriacaoInicial', ">=", $inicial);
                }

                if (!is_null(($dataCriacaoFinal))) {

                    $final = Carbon::parse($dataCriacaoFinal)->endOfDay();
                    $query = $query->where('dataCriacaoInicial', "<=", $final);
                }
            }

            //dataCriacao
            $dataAtualizacaoInicial = $request->get('dataAtualizacaoInicial');
            $dataAtualizacaoFinal = $request->get('dataAtualizacaoFinal');

            if (!is_null(($dataAtualizacaoInicial)) || !is_null(($dataAtualizacaoFinal))) {

                if (!is_null(($dataAtualizacaoInicial))) {
                    $inicial = Carbon::parse($dataAtualizacaoInicial);
                    $query = $query->where('dataAtualizacaoFinal', ">=", $inicial);
                }

                if (!is_null(($dataAtualizacaoFinal))) {

                    $final = Carbon::parse($dataAtualizacaoFinal)->endOfDay();
                    $query = $query->where('dataAtualizacaoFinal', "<=", $final);
                }
            }

            $totalRecordsFilter = $query->get()->Count();

            $records = $query
                ->orderBy($orderBy, $orderType)
                ->select('categorias.*')
                ->skip($pageCurrent)
                ->take($qtdRegistros)
                ->get();

            return $this->response(200, "MSG000151", ['total' => $totalRecordsFilter, 'data' => $categoria]);

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
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

            //validar
            $validar = Categoria::$validar;
            $validator = Validator::make(
                $request->all(),
                $validar,
                Categoria::$validarMensagens);

            if ($validator->fails()) {
                return $this->erros("MSG000071", $validator);
            }

            //cadastrar
            $this->categoria->fill($request->all());
            $this->categoria->usuarioCriacaoId = JWTAuth::user()->id;
            $this->categoria->usuarioAtualizacaoId = JWTAuth::user()->id;
            $this->categoria->save();

            return $this->response(201, "MSG000151", $this->categoria);

        } catch (Exception $ex) {

            return $this->response(404, "MSG000131", $e->getMessage());
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

            //validar
            if (is_null($id)) {
                return $this->response(404, "MSG000152");
            }

            $this->categoria = Categoria::find($id);

            if (!is_null(($this->categoria))) {

                return $this->response(200, "MSG000151", $categoria);

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(Request $request, $id)
    {
        try {

            if (is_null($id)) {
                return $this->response(404, "MSG000152");
            }

            //validar
            $validar = Categoria::$validar;
            $validator = Validator::make(
                $request->all(),
                $validar,
                Categoria::$validarMensagens);

            if ($validator->fails()) {
                return $this->erros("MSG000071", $validator);
            }

            $this->categoria = Categoria::find($id);

            if (!is_null(($this->categoria))) {

                //cadastrar
                $this->categoria->fill($request->all());
                $this->categoria->usuarioAtualizacaoId = JWTAuth::user()->id;
                $this->categoria->save();

                return $this->response(200, "MSG000151", $this->categoria);

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
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

            $this->categoria = Categoria::find($id);

            if (!is_null(($this->categoria))) {

                $this->categoria->delete();
                return $this->response(200, "MSG000144");

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function nome($id)
    {
        try {

            if (is_null($id)) {
                return $this->response(404, "MSG000152");
            }

            $this->categoria = Categoria::select('nome')->where('id', $id)->first();

            if (!is_null(($this->categoria))) {

                return $this->response(200, "MSG000144", $this->categoria->nome);

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }

    }

    public function status(Request $request, $id)
    {
        try {

            if (is_null($id)) {
                return $this->response(404, "MSG000152");
            }

            $this->categoria = Categoria::find($id);

            if (!is_null(($this->categoria))) {

                $this->categoria->status = $request->status;
                $this->categoria->save();

                return $this->response(200, "MSG000144", $this->categoria);

            } else {

                return $this->response(404, "MSG000112");

            }

        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }

    }

}
