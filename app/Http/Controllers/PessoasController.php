<?php

namespace App\Http\Controllers;

use App\Models\Pessoas;
use Illuminate\Http\Request;

class PessoasController extends Controller
{
    //lista todas as pessoas . em json
    public function index()
    {
        
        $pessoas = Pessoas::all();
        return response()->json($pessoas);
    }



    //store cria uma nova pessoa na tabela
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
        ]);

        $pessoa = Pessoas::create($validateData);

        return response()->json($pessoa, 201);
    }
    //lista uma pessoa especifico
    public function show(Pessoas $pessoa)
    {
        return response()->json($pessoa);
    }

    //atualiza uma pessoa
    public function update(Request $request, Pessoas $pessoa)
    {
        $validateData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
        ]);
        $pessoa->update($validateData);
        return response()->json($pessoas, 200);
    }

    //deleta uma pessoa 
    public function destroy(Pessoas $pessoa)
    {
        $pessoa->delete();
        return response()->json(null, 204);
    }
}