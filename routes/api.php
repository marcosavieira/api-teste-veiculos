<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoaController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pessoas', [PessoaController::class, 'getPessoas']);
Route::post('/pessoas', [PessoaController::class, 'createPessoa']);
Route::get('/pessoas/{pessoa}', [PessoaController::class, 'getPessoa']);
Route::put('/pessoas/{pessoa}', [PessoaController::class, 'updatePessoa']);
Route::delete('/pessoas/{pessoa}', [PessoaController::class, 'deletePessoa']);