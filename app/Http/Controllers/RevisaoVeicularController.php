<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\Pessoa;
use App\Models\RevisaoVeicular;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class RevisaoVeicularController extends Controller
{
    // Lista todas as revisões em JSON
    public function getRevisoesVeiculares()
    {
        try {
            $revisoes = RevisaoVeicular::all();
            return response()->json($revisoes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter a lista de revisões.'], 500);
        }
    }

    // Cria uma nova revisão na tabela
    public function createRevisaoVeicular(Request $request)
{
    try {
        $validateData = $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'veiculo_id' => 'required|exists:veiculos,id',
            'marca' => 'required|string',
            'data_manutencao' => 'required|date',
        ]);

        // Verifica se o veículo associado à veiculo_id existe
        $veiculo = Veiculo::find($validateData['veiculo_id']);

        if (!$veiculo) {
            return response()->json(['error' => 'Veículo não encontrado.'], 404);
        }

        // Adiciona a revisão associada ao veículo
        $revisao = RevisaoVeicular::create($validateData);

        return response()->json($revisao, 201);
    } catch (ValidationException $e) {
        dd($e->validator->errors()); // Adiciona essa linha para imprimir os erros de validação
        return response()->json(['error' => $e->validator->errors()], 400);
    } catch (\Exception $e) {
        dd($e->getMessage()); // Adiciona essa linha para imprimir a mensagem de exceção
        return response()->json(['error' => 'Erro ao criar uma nova revisão.'], 500);
    }
}


    // Lista uma revisão específica
    public function getRevisao($id)
    {
        try {
            $revisao = RevisaoVeicular::findOrFail($id);
            return response()->json($revisao);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Revisão não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter a revisão.'], 500);
        }
    }

    // Atualiza uma revisão
    public function updateRevisaoVeicular(Request $request, RevisaoVeicular $revisao)
    {
        try {
            $validateData = $request->validate([
                'pessoa_id' => 'required|exists:pessoas,id',
            'veiculo_id' => 'required|exists:veiculos,id',
            'marca' => 'required|string',
            'data_manutencao' => 'required|date',
            ]);

            // Atualiza os dados da revisão
            $revisao->update($validateData);

            return response()->json($revisao, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar a revisão.'], 500);
        }
    }

    // Deleta uma revisão
    public function deleteRevisaoVeicular(RevisaoVeicular $revisao)
    {
        try {
            $revisao->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar a revisão.'], 500);
        }
    }
}