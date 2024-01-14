<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\Pessoa;
use App\Models\RevisaoVeicular;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="RevisoesVeiculares",
 *     description="Operações relacionadas a revisões veiculares",
 * )
 */
class RevisaoVeicularController extends Controller
{
    /**
     * @OA\Get(
     *     path="/veiculos/revisoes",
     *     summary="Lista todas as revisões veiculares",
     *     tags={"RevisoesVeiculares"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de todas as revisões veiculares",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/RevisaoVeicular"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao obter a lista de revisões veiculares",
     *     ),
     * )
     */
    public function getRevisoesVeiculares()
    {
        try {
            $revisoes = RevisaoVeicular::all();
            return response()->json($revisoes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter a lista de revisões veiculares.'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/veiculos/revisoes",
     *     summary="Cria uma nova revisão veicular",
     *     tags={"RevisoesVeiculares"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RevisaoVeicular"),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Revisão veicular criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/RevisaoVeicular")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Veículo não encontrado",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar uma nova revisão veicular",
     *     ),
     * )
     */
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
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar uma nova revisão veicular.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/veiculos/revisoes/{id}",
     *     summary="Lista uma revisão veicular específica",
     *     tags={"RevisoesVeiculares"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da revisão veicular",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Revisão veicular encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/RevisaoVeicular")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Revisão veicular não encontrada",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao obter a revisão veicular",
     *     ),
     * )
     */
    public function getRevisaoVeicular($id)
    {
        try {
            $revisao = RevisaoVeicular::findOrFail($id);
            return response()->json($revisao);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Revisão veicular não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter a revisão veicular.'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/veiculos/revisoes/{revisao}",
     *     summary="Atualiza uma revisão veicular",
     *     tags={"RevisoesVeiculares"},
     *     @OA\Parameter(
     *         name="revisao",
     *         in="path",
     *         required=true,
     *         description="Revisão veicular a ser atualizada",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RevisaoVeicular"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Revisão veicular atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/RevisaoVeicular")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar a revisão veicular",
     *     ),
     * )
     */
    public function updateRevisaoVeicular(Request $request, RevisaoVeicular $revisao)
    {
        try {
            $validateData = $request->validate([
                'pessoa_id' => 'required|exists:pessoas,id',
                'veiculo_id' => 'required|exists:veiculos,id',
                'marca' => 'required|string',
                'data_manutencao' => 'required|date',
            ]);

            // Atualiza os dados da revisão veicular
            $revisao->update($validateData);

            return response()->json($revisao, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar a revisão veicular.'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/veiculos/revisoes/{revisao}",
     *     summary="Deleta uma revisão veicular",
     *     tags={"RevisoesVeiculares"},
     *     @OA\Parameter(
     *         name="revisao",
     *         in="path",
     *         required=true,
     *         description="Revisão veicular a ser deletada",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Revisão veicular deletada com sucesso",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao deletar a revisão veicular",
     *     ),
     * )
     */
    public function deleteRevisaoVeicular(RevisaoVeicular $revisao)
    {
        try {
            $revisao->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar a revisão veicular.'], 500);
        }
    }
}
