<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', 'App\Http\Controllers\controllerStarship@index');
Route::get('/naves', function () {
    return view('naves');
});
Route::get('/pilots', function () {
    return view('pilotos');
});
//Route::get('api/prueba', 'App\Http\Controllers\controllerStarship@getStarshipsWithPilots');


