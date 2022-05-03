<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

// Rota registrar usuario
Route::post('/register', [UserController::class, 'register'] );

// Esqueci minha senha
Route::post('/forgot-password', [UserController::class, 'forgotPassword'] );

// Resetar senha
Route::post('/reset-password', [UserController::class, 'resetPassword'] );

// Chamada para quando o usuário visualizar o capítulo
Route::post('capitulo/visualizado/{id}', 'CapituloAPIController@capituloVisualizado');

// Função para alterar o campo usar_apelido do usuário
Route::post('user/preferencia/apelido', [UserController::class, 'preferenciaUsarApelido']);

// Rota para buscar mais comentários sem necessidade de recarregar tudo
Route::get('comentario/mais-comentarios', 'ComentarioAPIController@carregarComentarios');

Route::resource('historia', HistoriaAPIController::class);
Route::resource('capitulo', CapituloAPIController::class);
Route::resource('categoria', CategoriaAPIController::class);
Route::resource('comentario', ComentarioAPIController::class);
Route::resource('direitos_autorais', DireitosAutoraisAPIController::class);
Route::resource('idioma', IdiomaAPIController::class);
Route::resource('publico_alvo', PublicoAlvoAPIController::class);
Route::resource('tags', TagsAPIController::class);
