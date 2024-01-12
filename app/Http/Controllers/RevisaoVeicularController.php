<?php

namespace App\Http\Controllers;

use App\Models\RevisaoVeicular;
use Illuminate\Http\Request;

class RevisaoVeicularController extends Controller
{
     // Lista todas as revisoes em JSON
     public function getRevisoesVeiculares()
     {
         $revisoes = RevisaoVeicular::all();
         return response()->json($revisoes);
     }
 
     // Cria uma nova revisao na tabela
     public function createRevisaoVeicular(Request $request)
     {
         $validateData = $request->validate([
             'marca' => 'required|string',
             'placa' => 'required|string',
             'data_manutencao' => 'required|date',
         ]);
 
         $revisao = RevisaoVeicular::create($validateData);
 
         return response()->json($revisao, 201);
     }
 
     // Lista uma revisao específica
     public function getRevisao(RevisaoVeicular $revisao)
     {
         return response()->json($revisao);
     }
 
     // Atualiza uma pessoa
     public function updateRevisaoVeicular(Request $request, RevisaoVeicular $revisao)
     {
         $validateData = $request->validate([
            'marca' => 'required|string',
            'placa' => 'required|string',
            'data_manutencao' => 'required|date',
         ]);
 
         $revisao->update($validateData);
 
         return response()->json($revisao, 200);
     }
 
     // Deleta uma pessoa
     public function deleteRevisaoVeicular(RevisaoVeicular $revisao)
     {
         $revisao->delete();
         return response()->json(null, 204);
     }
}
