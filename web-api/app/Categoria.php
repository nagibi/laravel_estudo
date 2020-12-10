<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    const CREATED_AT = 'dataCriacao';
    const UPDATED_AT = 'dataAtualizacao';    
    
    public function subcategorias(){
        return $this->hasMany(Categoria::class,'categoriaId','id');
    }

    public function allSubcategorias(){
        return $this->subcategorias->with('allSubcategorias');
    }
}
