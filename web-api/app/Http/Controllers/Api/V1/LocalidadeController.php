<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Facade;
use RobersonFaria\Cepaberto\Facade\CepAberto;

class LocalidadeController extends Controller
{
    public function __constructor(){
    }

    public function cep($cep){
        return $this->response(200, "MSG000151", CepAberto::obterEnderecoPorCep($cep));
    }

    public function paises(){
        return $this->response(200, "MSG000151", CepAberto::listarPaises());
    }

    public function paisesUfs($pais){
        return $this->response(200, "MSG000151", CepAberto::listarUfs(mb_strtoupper($pais)));

    }

    public function ufs(){
        return $this->response(200, "MSG000151", CepAberto::listarUfs());
    }

    public function ufsCidades($uf){
        return $this->response(200, "MSG000151", CepAberto::listarCidades($uf));
    }

    public function geo(Request $request){
        return $this->response(200, "MSG000151", CepAberto::obterGeo($request->lat,$request->lng));
    }

    public function logradouro(Request $request){
        return $this->response(200, "MSG000151", CepAberto::obterEnderecoPorLogradouro($request->uf,$request->cidade));
    }

}
