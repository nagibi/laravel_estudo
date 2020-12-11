
<?php

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
 * Home
 *****************/

Route::get('/cep/{numero}', 'HomeController@cep');


/*****************
 * Enums
 *****************/

Route::get('enums/sim-nao', 'EnumController@simNao');
Route::get('enums/tipo-documento', 'EnumController@tipoDocumento');
Route::get('enums/tipo-arquivo', 'EnumController@tipoArquivo');
Route::get('enums/sexo', 'EnumController@sexo');
Route::get('enums/status', 'EnumController@status');

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

/*****************
 * Categorias
 *****************/
Route::group(['middleware' => 'validar-acesso:teste|teste,usuario-cadastrar'], function () {


Route::get('categorias', 'CategoriaController@pesquisar');
Route::get('categorias/total','CategoriaController@total');
Route::get('categorias/{id}', 'CategoriaController@obter');
Route::get('categorias/{id}/nome','CategoriaController@nome');
Route::post('categorias', 'CategoriaController@cadastrar');
Route::put('categorias/{id}', 'CategoriaController@atualizar');
Route::put('categorias/{id}/status','CategoriaController@status');
Route::post('categorias/{id}/grupos','CategoriaController@grupo');
Route::delete('categorias/{id}','CategoriaController@deletar');


});
