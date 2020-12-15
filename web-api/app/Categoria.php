<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Class Categoria
 *
 * @OA\Schema(
 *     description="Categoria model"
 * )
 */

class Categoria extends Model
{
    use ValidatesRequests;

    const CREATED_AT = 'dataCriacao';

    const UPDATED_AT = 'dataAtualizacao';

    protected $fillable = [
        'categoriaId', 'nome', 'status', 'usuarioCriacaoId', 'usuarioAtualizacaoId', 'dataCriacao', 'dataAtualizacao',
    ];

    static $validar = [
        'nome' => 'required|max:255',
    ];

    static $validarMensagens = [
        'nome.required' => 'MSG000073',
        'nome.max' => 'MSG000290',
    ];

    //hasOne x belongTo
    // https://stackoverflow.com/questions/37582848/what-is-the-difference-between-belongsto-and-hasone-in-laravel/37583250
    //1:1
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoriaId', 'id');
    }

    //   //1:1
    //   public function categoria()
    //   {
    //       return $this->hasOne(Categoria::class, 'categoriaId', 'id');
    //   }

    //    //N:N
    //   public function categoria()
    //   {
    //       return $this->belongsToMany(Categoria::class, 'categoriaId', 'id');
    //   }

    //1:N
    public function subcategorias()
    {
        return $this->hasMany(Categoria::class, 'categoriaId', 'id');
    }
}
