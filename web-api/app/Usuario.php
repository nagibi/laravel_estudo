<?php

namespace App;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Validation\ValidatesRequests;
// Please add this line
use Tymon\JWTAuth\Contracts\JWTSubject;
use Zizaco\Entrust\Traits\EntrustUserTrait;

// Please implement JWTSubject interface
// class User extends Authenticatable implements JWTSubject
class Usuario extends Authenticatable implements JWTSubject
{
    use CanResetPassword, EntrustUserTrait, ValidatesRequests;
    
    const CREATED_AT = 'dataCriacao';
    const UPDATED_AT = 'dataAtualizacao';    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     //podem ser editadas
    protected $fillable = [
        'arquivoId','nome', 'email', 'password','status','usuarioCriacaoId','usuarioAtualizacaoId','dataCriacao','dataAtualizacao'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    static $validar = [
      'nome' => 'required|max:255',
      'confirmacaoEmail' => 'required',
      'senha' => 'required|max:6|min:6',
      'dataNascimento' => 'required',
      'sexo' => 'required',
      'email' => 'required|email|max:50',
      'grupoId'=>'required'
    ];

    static $atualizarPerfilValidar = [
      'nome' => 'required|max:255',
      'dataNascimento' => 'required',
      'sexo' => 'required',
      'email' => 'required|email|max:50',
    ];

    static $alterarSenhaValidar = [
      'senhaAtual' => 'required|max:6|min:6',
      'novaSenha' => 'required|max:6|min:6',
    ];

    static $loginValidar = [
      'password' => 'required|max:6|min:6',
      'email' => 'required|email|max:50',
    ];

    static $cadastrarValidar = [
      'nome' => 'required|max:255',
      'senha' => 'required|max:6|min:6',
      'email' => 'required|email|max:50',
    ];

    static $resetarSenhaValidar = [
      'email' => 'required|email|max:50',
    ];

    static $validarMensagens = [
      'sexo.required'=>'MSG000073',
      'nome.required'=>'MSG000073',
      'nome.max'=>'MSG000290',
      'confirmacaoEmail.required'=>'MSG000073',
      'senha.required'=>'MSG000073',
      'senha.max'=>'MSG000291'  ,
      'senha.min'=>'MSG000292' ,
      'dataNascimento.required'=>'MSG000073',
      'email.required'=>'MSG000073',
      'email.email'=>'MSG000074',
      'email.unique'=>'MSG000077',
      'email.max'=>'MSG000289',
      'grupoId.required'=>'MSG000289'
    ];

    public function arquivo()
    {
        return $this->belongsTo('App\Arquivo','arquivoId');
    }

    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }

}
