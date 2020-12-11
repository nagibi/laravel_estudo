<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __constructor(){
    }

    public function cep($numero){
        $cep = new \BrunoCouty\BuscaViaCep\Services\Cep();
        return $this->response(200, "MSG000151", $cep->busca($numero));

    }
}
