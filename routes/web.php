<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post("api/facultades","App\Http\Controllers\AppController@facultades");
Route::post("api/programas","App\Http\Controllers\AppController@programas");
Route::post("api/estudiantes","App\Http\Controllers\AppController@estudiantes");