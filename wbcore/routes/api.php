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

Route::resource('historia', HistoriaAPIController::class);
Route::resource('capitulo', CapituloAPIController::class);
Route::resource('categoria', CategoriaAPIController::class);
Route::resource('comentario', ComentarioAPIController::class);
Route::resource('direitos_autorais', DireitosAutoraisAPIController::class);
Route::resource('idioma', IdiomaAPIController::class);
Route::resource('publico_alvo', PublicoAlvoAPIController::class);
Route::resource('tags', TagsAPIController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
