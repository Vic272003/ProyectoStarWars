<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('starship','App\Http\Controllers\controllerStarship@getStarship');
Route::get('starship/{id}','App\Http\Controllers\controllerStarship@getStarhipId');

//Usados
Route::get('pilotosDeNaves','App\Http\Controllers\controllerStarship@getStarshipsWithPilots');
Route::get('pilot','App\Http\Controllers\controllerPilot@getPilot');
Route::post('addPilotToStarship/{id}', 'App\Http\Controllers\controllerStarship@addPilotToStarship');
Route::post('deletePilot/{id}','App\Http\Controllers\controllerPilot@deletePilot');

Route::delete('starships/{starship}/pilots/{pilot}','App\Http\Controllers\controllerStarship@deletePilotFromStarship');

//Usados



Route::post('addStarship','App\Http\Controllers\controllerStarship@insertStarship');
Route::post('updateStarship/{id}','App\Http\Controllers\controllerStarship@updateStarship');
Route::delete('deleteStarship/{id}','App\Http\Controllers\controllerStarship@deleteStarship');

Route::get('starship/{id}/pilots', 'App\Http\Controllers\controllerStarship@getPilotsPorId');

