<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RobersonFaria\Cepaberto\Facade\CepAberto;

class LocalidadeController extends Controller
{
    public function __constructor()
    {
    }

    public function cep($cep)
    {
        try {
            return $this->response(200, "MSG000151", CepAberto::obterEnderecoPorCep($cep));
        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function paises()
    {
        try {
            return $this->response(200, "MSG000151", CepAberto::listarPaises());
        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function paisesUfs($pais)
    {
        try {
            return $this->response(200, "MSG000151", CepAberto::listarUfs(mb_strtoupper($pais)));
        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }

    }

    public function ufs()
    {
        try {
            return $this->response(200, "MSG000151", CepAberto::listarUfs());
        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function ufsCidades($uf)
    {
        try {
            return $this->response(200, "MSG000151", CepAberto::listarCidades($uf));
        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function geo(Request $request)
    {
        try {
            return $this->response(200, "MSG000151", CepAberto::obterGeo($request->lat, $request->lng));
        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

    public function logradouro(Request $request)
    {
        try {
            return $this->response(200, "MSG000151", CepAberto::obterEnderecoPorLogradouro($request->uf, $request->cidade));
        } catch (Exception $ex) {
            return $this->response(404, "MSG000131", $e->getMessage());
        }
    }

}
