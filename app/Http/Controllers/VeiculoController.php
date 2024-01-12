<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    // Lista todos os veículos em JSON
    public function getVeiculos()
    {
        $veiculos = Veiculo::all();
        return response()->json($veiculos);
    }

    // Cria um novo veículo na tabela
    public function createVeiculo(Request $request)
    {
        $validateData = $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'marca' => 'required|string',
            'tipo' => 'required|string',
            'modelo' => 'nullable|string',
            'placa' => 'nullable|string',
        ]);

        $veiculo = Veiculo::create($validateData);

        $veiculo->veiculo_id = $veiculo->id;

        return response()->json($veiculo, 201);
    }

    // Lista um veículo específico
    public function getVeiculo(Veiculo $veiculo)
    {
        return response()->json($veiculo);
    }

    // Atualiza um veículo
    public function updateVeiculo(Request $request, Veiculo $veiculo)
    {
        $validateData = $request->validate([
            'pessoa_id' => 'exists:pessoas,id',
            'marca' => 'string',
            'tipo' => 'string',
            'modelo' => 'nullable|string',
            'placa' => 'nullable|string',
        ]);

        $veiculo->update($validateData);

        return response()->json($veiculo, 200);
    }

    // Deleta um veículo
    public function deleteVeiculo(Veiculo $veiculo)
    {
        $veiculo->delete();
        return response()->json(null, 204);
    }
}