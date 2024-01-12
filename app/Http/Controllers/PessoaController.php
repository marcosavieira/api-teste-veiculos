<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    // Lista todas as pessoas em JSON
    public function getPessoas()
    {
        $pessoas = Pessoa::all();
        return response()->json($pessoas);
    }

    // Cria uma nova pessoa na tabela
    public function createPessoa(Request $request)
    {
        $validateData = $request->validate([
            'nome' => 'required|string',
            'idade' => 'required|integer',
            'genero' => 'required|string',
        ]);

        $pessoa = Pessoa::create($validateData);

        return response()->json($pessoa, 201);
    }

    // Lista uma pessoa específica
    public function getPessoa(Pessoa $pessoa)
    {
        return response()->json($pessoa);
    }

    // Atualiza uma pessoa
    public function updatePessoa(Request $request, Pessoa $pessoa)
    {
        $validateData = $request->validate([
            'nome' => 'required|string',
            'idade' => 'required|integer',
            'genero' => 'required|string',
        ]);

        $pessoa->update($validateData);

        return response()->json($pessoa, 200);
    }

    // Deleta uma pessoa
    public function deletePessoa(Pessoa $pessoa)
    {
        $pessoa->delete();
        return response()->json(null, 204);
    }
}