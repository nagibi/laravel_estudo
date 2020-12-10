<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Categoria;

class CategoriaController extends Controller
{
    public function __constructor(){

    }

    public function index(){
        $categoria = Categoria::where('id', 1)->with('allSubcategorias')->first();
    }
}
