<?php

use App\Mail\newEnviarEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*****************
 * Global
 *****************/

Route::get('enums/sim-nao', 'EnumController@simNao');
Route::get('enums/tipo-documento', 'EnumController@tipoDocumento');
Route::get('enums/tipo-arquivo', 'EnumController@tipoArquivo');
Route::get('enums/sexo', 'EnumController@sexo');
Route::get('enums/status', 'EnumController@status');

Route::get('categorias', 'CategoriaController@index');



/*****************
 * Usuarios
 *****************/

Route::group(['middleware' => 'validar-acesso:admin'], function () {

    Route::get('usuarios', 'UsuarioController@pesquisar');
    Route::get('usuarios/total','UsuarioController@total');
    Route::get('usuarios/{id}', 'UsuarioController@obter');
    Route::get('usuarios/{id}/nome','UsuarioController@nome');
    Route::post('usuarios', 'UsuarioController@cadastrar');
    Route::put('usuarios/{id}', 'UsuarioController@atualizar');
    // Route::match(['put','get'],['usuarios/{id}', 'UsuarioController@atualizar']);
    Route::put('usuarios/{id}/status','UsuarioController@status');
    Route::post('usuarios/{id}/grupos','UsuarioController@grupo');
    Route::delete('usuarios/{id}','UsuarioController@deletar');

});

/*****************
 * Arquivos
 *****************/

Route::group(['middleware' => 'validar-acesso:admin|edit'], function () {

    Route::get('arquivos', 'ArquivoController@pesquisar');
    Route::get('arquivos/total','ArquivoController@total');
    Route::get('arquivos/{id}', 'ArquivoController@obter');
    Route::post('arquivos', 'ArquivoController@cadastrar');
    Route::get('arquivos/{id}/nome','ArquivoController@nome');
    Route::put('arquivos/{id}/status','ArquivoController@status');
    Route::delete('arquivos/{id}','ArquivoController@deletar');

});

/*****************
 * Grupos
 *****************/

Route::group(['middleware' => 'validar-acesso:admin'], function () {

    Route::get('grupos', 'GrupoController@pesquisar');
    Route::get('grupos/total','GrupoController@total');
    Route::get('grupos/{id}', 'GrupoController@obter');
    Route::get('grupos/{id}/nome','GrupoController@nome');
    Route::post('grupos', 'GrupoController@cadastrar');
    Route::put('grupos/{id}', 'GrupoController@atualizar');
    Route::put('grupos/{id}/status','GrupoController@status');
    Route::post('grupos/{id}/funcoes','GrupoController@grupo');
    Route::delete('grupos/{id}','GrupoController@deletar');

});

/*****************
 * Auth
 *****************/

Route::group(['middleware' => 'validar-acesso:teste|teste,usuario-cadastrar'], function () {

    Route::get('auth/permissoes', 'AuthController@permissoes');
    Route::put('auth/alterar-senha', 'AuthController@alterarSenha');
    Route::get('auth/usuario-logado', 'AuthController@usuarioLogado');
    Route::put('auth/usuario-logado', 'AuthController@atualizarPerfil');
    Route::post('auth/logout', 'AuthController@logout');

});


Route::post('auth/cadastrar', 'AuthController@cadastrar');;

Route::post('auth/login', 'AuthController@login');;

Route::get('auth/confirmar-email/{token}', 'AuthController@confirmarEmail');;

Route::post('auth/resetar-senha', 'AuthController@resetarSenha');

Route::post('auth/role', 'AuthController@createRole');

Route::post('auth/permission', 'AuthController@createPermission');

Route::post('auth/assign-role', 'AuthController@assignRole');

Route::post('auth/attach-permission', 'AuthController@attachPermission');

/*** */
