<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class PessoaController extends Controller
{
    // Lista todas as pessoas em JSON
    public function getPessoas()
    {
        try {
            $pessoas = Pessoa::all();
            return response()->json($pessoas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter a lista de pessoas.'], 500);
        }
    }

    // Cria uma nova pessoa na tabela
    public function createPessoa(Request $request)
    {
        try {
            $validateData = $request->validate([
                'nome' => 'required|string',
                'idade' => 'required|integer',
                'genero' => 'required|string',
                'quantidade_veiculos' => 'required|integer',
            ]);

            $pessoa = Pessoa::create($validateData);

            return response()->json($pessoa, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar uma nova pessoa.'], 500);
        }
    }

    // Lista uma pessoa específica
    public function getPessoa($id)
    {
        try {
            $pessoa = Pessoa::findOrFail($id);
            return response()->json($pessoa);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Pessoa não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter a pessoa.'], 500);
        }
    }

    // Atualiza uma pessoa
    public function updatePessoa(Request $request, Pessoa $pessoa)
    {
        try {
            $validateData = $request->validate([
                'nome' => 'required|string',
                'idade' => 'required|integer',
                'genero' => 'required|string',
                'quantidade_veiculos' => 'required|integer',
            ]);

            $pessoa->update($validateData);

            return response()->json($pessoa, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar a pessoa.'], 500);
        }
    }

    // Deleta uma pessoa
    public function deletePessoa(Pessoa $pessoa)
    {
        try {
            $pessoa->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar a pessoa.'], 500);
        }
    }
}