<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\VeiculoController; 
use App\Http\Controllers\RevisaoVeicularController;
use App\Http\Controllers\RelatoriosController; 

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

// Rotas para Revisao
Route::get('/veiculos/revisoes', [RevisaoVeicularController::class, 'getRevisoesVeiculares']);
Route::post('/veiculos/revisoes', [RevisaoVeicularController::class, 'createRevisaoVeicular']);
Route::get('/veiculos/revisoes/{revisao}', [RevisaoVeicularController::class, 'getRevisaoVeicular']);
Route::put('/veiculos/revisoes/{revisao}', [RevisaoVeicularController::class, 'updateRevisaoVeicular']);
Route::delete('/veiculos/revisoes/{revisao}', [RevisaoVeicularController::class, 'deleteRevisaoVeicular']);

//Rotas de relatorios Pessoas
Route::get('/relatorios/todas_pessoas', [RelatoriosController::class, 'relatorioTodasPessoas']);
Route::get('/relatorios/media_idade_genero', [RelatoriosController::class, 'relatorioMediaIdadePorGenero']);

//Rotas de relatorios Veiculos
Route::get('/relatorios/todos_veiculos', [RelatoriosController::class, 'relatorioTodosVeiculos']);
Route::get('/relatorios/veiculos_por_pessoa', [RelatoriosController::class, 'relatorioVeiculosPorPessoa']);
Route::get('/relatorios/contagem_por_genero', [RelatoriosController::class, 'relatorioContagemPorGenero']);
Route::get('/relatorios/marcas_veiculos', [RelatoriosController::class, 'relatorioMarcasVeiculos']);

//Rotas de relatorios Revisoes
Route::get('/relatorios/todas-revisoes', [RelatoriosController::class, 'relatorioTodasRevisoes']);
Route::get('/relatorios/marcas-maior-numero-revisoes', [RelatoriosController::class, 'relatorioMarcasMaiorNumeroRevisoes']);
Route::get('/relatorios/pessoas-maior-numero-revisoes', [RelatoriosController::class, 'relatorioPessoasMaiorNumeroRevisoes']);
Route::get('/relatorios/media-tempo-entre-revisoes/{pessoa_id}', [RelatoriosController::class, 'relatorioMediaTempoEntreRevisoes']);


