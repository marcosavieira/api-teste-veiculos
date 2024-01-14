<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class VeiculoController extends Controller
{
    // Lista todos os veículos em JSON
    public function getVeiculos()
    {
        try {
            $veiculos = Veiculo::all();
            return response()->json($veiculos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter a lista de veículos.'], 500);
        }
    }

    // Cria um novo veículo na tabela
    public function createVeiculo(Request $request)
    {
        try {
            $validateData = $request->validate([
                'pessoa_id' => 'required|exists:pessoas,id',
                'marca' => 'required|string',
                'tipo' => 'required|string',
                'modelo' => 'nullable|string',
                'placa' => 'nullable|string',
            ]);

            $veiculo = Veiculo::create($validateData);

            return response()->json($veiculo, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar um novo veículo.'], 500);
        }
    }

    // Lista um veículo específico
    public function getVeiculo($id)
    {
        try {
            $veiculo = Veiculo::findOrFail($id);
            return response()->json($veiculo);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Veículo não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter o veículo.'], 500);
        }
    }

    // Atualiza um veículo
    public function updateVeiculo(Request $request, Veiculo $veiculo)
    {
        try {
            $validateData = $request->validate([
                'pessoa_id' => 'exists:pessoas,id',
                'marca' => 'string',
                'tipo' => 'string',
                'modelo' => 'nullable|string',
                'placa' => 'nullable|string',
            ]);

            $veiculo->update($validateData);

            return response()->json($veiculo, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar o veículo.'], 500);
        }
    }

    // Deleta um veículo
    public function deleteVeiculo(Veiculo $veiculo)
    {
        try {
            $veiculo->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar o veículo.'], 500);
        }
    }
}