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
//Redirigimos a la vista naves si recogemos esa url
Route::get('/naves', function () {
    return view('naves');
});
//Redirigimos a la vista pilotos si recogemos esa url
Route::get('/pilots', function () {
    return view('pilotos');
});


