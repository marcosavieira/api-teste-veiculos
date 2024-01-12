<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\VeiculoController; 

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas para Pessoa
Route::get('/pessoas', [PessoaController::class, 'getPessoas']);
Route::post('/pessoas', [PessoaController::class, 'createPessoa']);
Route::get('/pessoas/{pessoa}', [PessoaController::class, 'getPessoa']);
Route::put('/pessoas/{pessoa}', [PessoaController::class, 'updatePessoa']);
Route::delete('/pessoas/{pessoa}', [PessoaController::class, 'deletePessoa']);

// Rotas para Veiculo
Route::get('/veiculos', [VeiculoController::class, 'getVeiculos']);
Route::post('/veiculos', [VeiculoController::class, 'createVeiculo']);
Route::get('/veiculos/{veiculo}', [VeiculoController::class, 'getVeiculo']);
Route::put('/veiculos/{veiculo}', [VeiculoController::class, 'updateVeiculo']);
Route::delete('/veiculos/{veiculo}', [VeiculoController::class, 'deleteVeiculo']);