<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\User;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/login', [UserController::class, 'authenticate'] );

Route::middleware('auth:sanctum')->group(function() {
	Route::post('/logout', [UserController::class, 'logoutWeb'] );
});

Route::get('/sanctum', [CsrfCookieController::class, 'show']);
