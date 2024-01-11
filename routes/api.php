<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoasController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pessoas', [PessoasController::class, 'index']);
Route::post('/pessoas', [PessoasController::class, 'store']);
// Route::post('/pessoas', 'PessoasController@store');
Route::get('/pessoas/{pessoa}', [PessoasController::class, 'show']);
Route::put('/pessoas/{pessoa}', [PessoasController::class, 'update']);
Route::delete('/pessoas/{pessoa}', [PessoasController::class, 'destroy']);