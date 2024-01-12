<?php

namespace App\Http\Controllers;

use App\Models\Revisao;
use Illuminate\Http\Request;

class RevisaoController extends Controller
{
    // Lista todas as revisões em JSON
    public function getRevisoes()
    {
        $revisoes = Revisao::all();
        return response()->json($revisoes);
    }

    // Cria uma nova revisão na tabela
    public function createRevisao(Request $request)
    {
        $validateData = $request->validate([
            'veiculo_id' => 'required|exists:veiculos_id',
            'data_revisao' => 'required|date',
            'descricao' => 'nullable|string',
        ]);

        $revisao = Revisao::create($validateData);

        return response()->json($revisao, 201);
    }

    // Lista uma revisão específica
    public function getRevisao(Revisao $revisao)
    {
        return response()->json($revisao);
    }

    // Atualiza uma revisão
    public function updateRevisao(Request $request, Revisao $revisao)
    {
        $validateData = $request->validate([
            'veiculo_id' => 'exists:veiculos,id',
            'data_revisao' => 'date',
            'descricao' => 'nullable|string',
        ]);

        $revisao->update($validateData);

        return response()->json($revisao, 200);
    }

    // Deleta uma revisão
    public function deleteRevisao(Revisao $revisao)
    {
        $revisao->delete();
        return response()->json(null, 204);
    }
}
