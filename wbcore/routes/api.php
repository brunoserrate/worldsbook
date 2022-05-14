<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilsController;

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

// Rota para buscar mais comentários sem necessidade de recarregar tudo
Route::get('comentario/mais-comentarios', 'ComentarioAPIController@carregarComentarios');

// Buscar historias
Route::get('historia', 'HistoriaAPIController@index');

// Buscar historia pelo ID
Route::get('historia/{id}', 'HistoriaAPIController@show');

// Buscar histórias
Route::get('historia/pesquisa', 'HistoriaAPIController@pesquisarHistoria');

// Buscar capitulos
Route::get('capitulo', 'CapituloAPIController@index');

// Buscar capitulo pelo ID
Route::get('capitulo/{id}', 'CapituloAPIController@show');

// Chamada para quando o usuário visualizar o capítulo
Route::post('capitulo/visualizado/{id}', 'CapituloAPIController@capituloVisualizado');

// Buscar usuários
Route::get('user/pesquisa', [UserController::class, 'pesquisarUsuarios']);

// Auxiliares
Route::get('categoria', 'CategoriaAPIController@index');
Route::get('comentario', 'ComentarioAPIController@index');
Route::get('direitos_autorais', 'DireitosAutoraisAPIController@index');
Route::get('idioma', 'IdiomaAPIController@index');
Route::get('publico_alvo', 'PublicoAlvoAPIController@index');
Route::get('tags', 'TagsAPIController@index');

// Rotas autênticadas
Route::middleware('auth:sanctum')->group(function() {

	// Upload de capa da história
	Route::post('historia/upload/capa', 'HistoriaAPIController@uploadCapaHistoria');

	// Gravar a história
	Route::post('historia', 'HistoriaAPIController@store');

	// Atualizar historia
	Route::patch('historia/{id}', 'HistoriaAPIController@update');

	// Upload de capa do capítulo
	Route::post('capitulo/upload/capa', 'CapituloAPIController@uploadCapaCapitulo');

	// Vota no capítulo
	Route::post('capitulo/votado/{id}', 'CapituloAPIController@votarCapitulo');

	// Gravar a capítulo
	Route::post('capitulo', 'CapituloAPIController@store');

	// Atualizar capítulo
	Route::patch('capitulo/{id}', 'CapituloAPIController@update');

	// Comentar no capítulo
	Route::post('comentario', 'ComentarioAPIController@store');

	// Upload da foto perfil para ser utilizada pelo usuário
	Route::post('user/upload/foto', [UserController::class, 'uploadFoto']);

	// Função para alterar o campo usar_apelido do usuário
	Route::post('user/preferencia/apelido', [UserController::class, 'preferenciaUsarApelido']);

	// Atualizar configurações de perfil
	Route::patch('user/perfil/{id}', [UserController::class, 'atualizarPerfil']);
});


