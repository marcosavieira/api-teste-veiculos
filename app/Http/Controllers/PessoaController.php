<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Pessoas",
 *     description="Operações relacionadas a pessoas",
 * )
 */
class PessoaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pessoas",
     *     summary="Lista todas as pessoas",
     *     tags={"Pessoas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de todas as pessoas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Pessoa"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao obter a lista de pessoas",
     *     ),
     * )
     */
    public function getPessoas()
    {
        try {
            $pessoas = Pessoa::all();
            return response()->json($pessoas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter a lista de pessoas.'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/pessoas",
     *     summary="Cria uma nova pessoa",
     *     tags={"Pessoas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Pessoa"),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pessoa criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Pessoa")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar uma nova pessoa",
     *     ),
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/pessoas/{id}",
     *     summary="Lista uma pessoa específica",
     *     tags={"Pessoas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pessoa encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Pessoa")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pessoa não encontrada",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao obter a pessoa",
     *     ),
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/pessoas/{pessoa}",
     *     summary="Atualiza uma pessoa",
     *     tags={"Pessoas"},
     *     @OA\Parameter(
     *         name="pessoa",
     *         in="path",
     *         required=true,
     *         description="Pessoa a ser atualizada",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Pessoa"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pessoa atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Pessoa")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar a pessoa",
     *     ),
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/pessoas/{pessoa}",
     *     summary="Deleta uma pessoa",
     *     tags={"Pessoas"},
     *     @OA\Parameter(
     *         name="pessoa",
     *         in="path",
     *         required=true,
     *         description="Pessoa a ser deletada",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Pessoa deletada com sucesso",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao deletar a pessoa",
     *     ),
     * )
     */
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
