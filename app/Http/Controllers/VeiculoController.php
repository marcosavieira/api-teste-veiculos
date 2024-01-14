<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Veiculos",
 *     description="Operações relacionadas a veículos",
 * )
 */
class VeiculoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/veiculos",
     *     summary="Lista todos os veículos",
     *     tags={"Veiculos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de todos os veículos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Veiculo"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao obter a lista de veículos",
     *     ),
     * )
     */
    public function getVeiculos()
    {
        try {
            $veiculos = Veiculo::all();
            return response()->json($veiculos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter a lista de veículos.'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/veiculos",
     *     summary="Cria um novo veículo",
     *     tags={"Veiculos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Veiculo"),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Veículo criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Veiculo")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar um novo veículo",
     *     ),
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/veiculos/{id}",
     *     summary="Lista um veículo específico",
     *     tags={"Veiculos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do veículo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Veículo encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Veiculo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Veículo não encontrado",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao obter o veículo",
     *     ),
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/veiculos/{veiculo}",
     *     summary="Atualiza um veículo",
     *     tags={"Veiculos"},
     *     @OA\Parameter(
     *         name="veiculo",
     *         in="path",
     *         required=true,
     *         description="Veículo a ser atualizado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Veiculo"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Veículo atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Veiculo")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar o veículo",
     *     ),
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/veiculos/{veiculo}",
     *     summary="Deleta um veículo",
     *     tags={"Veiculos"},
     *     @OA\Parameter(
     *         name="veiculo",
     *         in="path",
     *         required=true,
     *         description="Veículo a ser deletado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Veículo deletado com sucesso",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao deletar o veículo",
     *     ),
     * )
     */
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
