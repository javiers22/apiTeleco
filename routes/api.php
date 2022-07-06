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

Route::group([
    'middleware' => 'api'

], function ($router) {
    Route::match(['get', 'post'],'login', [\App\Http\Controllers\UserAuthController::class, 'login'])->name('login');
    Route::post('logout', [\App\Http\Controllers\UserAuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [\App\Http\Controllers\UserAuthController::class, 'refresh'])->name('refresh');
    Route::post('me', [\App\Http\Controllers\UserAuthController::class, 'me'])->name('me');
    Route::get('prueba/{id}', [\App\Http\Controllers\UserAuthController::class, 'prueba'])->name('prueba');
    Route::get('vinculacion', [\App\Http\Controllers\UserAuthController::class, 'vinculacion'])->name('vinculacion');
});

/*Se deben crear las otras rutas para el acceso sin token de los metodos 
Obtener Facultades de una división
Obtener Programas de una Facultad
Obtener varios estudiantes*/