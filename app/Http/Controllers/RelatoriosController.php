<?php
namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\Models\Veiculo;
use App\Models\RevisaoVeicular;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Relatórios",
 *     description="Endpoints para relatórios"
 * )
 */

class RelatoriosController extends Controller
{
 /**
 * @OA\Get(
 *     path="/relatorios/todas-pessoas",
 *     summary="Relatório de todas as pessoas",
 *     tags={"Relatórios"},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de todas as pessoas",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Pessoa"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao obter o relatório de todas as pessoas",
 *     ),
 * )
 */
public function relatorioTodasPessoas()
{
    try {
        $pessoas = Pessoa::all();
        return response()->json(['pessoas' => $pessoas]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao obter o relatório de todas as pessoas.'], 500);
    }
}

/**
 * @OA\Get(
 *     path="/relatorios/media-idade-genero",
 *     summary="Relatório de média de idade por gênero",
 *     tags={"Relatórios"},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de média de idade por gênero",
 *         @OA\JsonContent(type="object", @OA\Property(property="homens", type="array", @OA\Items(ref="#/components/schemas/Pessoa")),
 *                                              @OA\Property(property="mulheres", type="array", @OA\Items(ref="#/components/schemas/Pessoa")),
 *                                              @OA\Property(property="mediaIdadeHomens", type="number"),
 *                                              @OA\Property(property="mediaIdadeMulheres", type="number"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao gerar o relatório de média de idade por gênero",
 *     ),
 * )
 */
public function relatorioMediaIdadePorGenero()
{
    try {
        $homens = Pessoa::whereRaw('LOWER(genero) = ?', ['masculino'])->get();
        $mulheres = Pessoa::whereRaw('LOWER(genero) = ?', ['feminino'])->get();
        $mediaIdadeHomens = number_format(Pessoa::whereRaw('LOWER(genero) = ?', ['masculino'])->avg('idade'), 2);
        $mediaIdadeMulheres = number_format(Pessoa::whereRaw('LOWER(genero) = ?', ['feminino'])->avg('idade'), 2);

        return response()->json([
            'homens' => $homens,
            'mulheres' => $mulheres,
            'mediaIdadeHomens' => $mediaIdadeHomens,
            'mediaIdadeMulheres' => $mediaIdadeMulheres,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao gerar o relatório de média de idade por gênero.'], 500);
    }
}

/**
 * @OA\Get(
 *     path="/relatorios/todos-veiculos",
 *     summary="Relatório de todos os veículos",
 *     tags={"Relatórios"},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de todos os veículos",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Veiculo"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao obter o relatório de todos os veículos",
 *     ),
 * )
 */
public function relatorioTodosVeiculos()
{
    try {
        // Todos os Veículos
        $todosVeiculos = Veiculo::all();
        return response()->json(['todos_veiculos' => $todosVeiculos]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao obter o relatório de todos os veículos.'], 500);
    }
}

  /**
 * @OA\Get(
 *     path="/relatorios/veiculos-por-pessoa",
 *     summary="Relatório de veículos por pessoa",
 *     tags={"Relatórios"},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de veículos por pessoa",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Veiculo"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao obter o relatório de veículos por pessoa",
 *     ),
 * )
 */
public function relatorioVeiculosPorPessoa()
{
    try {
        // Todos os Veículos por Pessoa Ordenados por Nome de Pessoa
        $veiculosPorPessoa = Veiculo::with(['pessoa' => function ($query) {
            $query->orderBy('nome');
        }])->get();

        return response()->json(['veiculosPorPessoa' => $veiculosPorPessoa]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao obter o relatório de veículos por pessoa.'], 500);
    }
}

/**
 * @OA\Get(
 *     path="/relatorios/contagem-por-genero",
 *     summary="Relatório de contagem de veículos por gênero",
 *     tags={"Relatórios"},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de contagem de veículos por gênero",
 *         @OA\JsonContent(type="array", @OA\Items(
 *             @OA\Property(property="contagemVeiculosHomens", type="integer"),
 *             @OA\Property(property="contagemVeiculosMulheres", type="integer"),
 *             @OA\Property(property="quemTemMaisVeiculos", type="string")
 *         ))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao gerar o relatório de contagem por gênero",
 *     ),
 * )
 */
public function relatorioContagemPorGenero()
{
    try {
        // Informação de Quem Tem Mais Veículos (Homens ou Mulheres)
        $contagemVeiculosHomens = Veiculo::whereHas('pessoa', function ($query) {
            $query->whereRaw('LOWER(genero) = ?', ['masculino']);
        })->count();

        $contagemVeiculosMulheres = Veiculo::whereHas('pessoa', function ($query) {
            $query->whereRaw('LOWER(genero) = ?', ['feminino']);
        })->count();

        $quemTemMaisVeiculos = $contagemVeiculosHomens > $contagemVeiculosMulheres ? 'Homens' : 'Mulheres';

        return response()->json([
            'contagemVeiculosHomens' => $contagemVeiculosHomens,
            'contagemVeiculosMulheres' => $contagemVeiculosMulheres,
            'quemTemMaisVeiculos' => $quemTemMaisVeiculos,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao gerar o relatório de contagem por gênero.'], 500);
    }
}

/**
 * @OA\Get(
 *     path="/relatorios/marcas-veiculos",
 *     summary="Relatório de marcas de veículos",
 *     tags={"Relatórios"},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de marcas de veículos",
 *         @OA\JsonContent(type="object", @OA\Property(property="marcasOrdenadasPorVeiculos", type="array", @OA\Items(
 *             @OA\Property(property="marca", type="string"),
 *             @OA\Property(property="total", type="integer")
 *         )),
 *         @OA\Property(property="totaisMarcasHomens", type="array", @OA\Items(
 *             @OA\Property(property="marca", type="string"),
 *             @OA\Property(property="total", type="integer")
 *         )),
 *         @OA\Property(property="totaisMarcasMulheres", type="array", @OA\Items(
 *             @OA\Property(property="marca", type="string"),
 *             @OA\Property(property="total", type="integer")
 *         )))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao gerar o relatório de marcas de veículos",
 *     ),
 * )
 */
public function relatorioMarcasVeiculos()
{
    try {
        // Todas as Marcas Ordenadas pelo Número de Veículos
        $marcasOrdenadasPorVeiculos = Veiculo::select('marca', \DB::raw('COUNT(*) as total'))
            ->groupBy('marca')
            ->orderByRaw('total DESC')
            ->get();

        // Totais de Marcas Ordenadas do Maior para o Menor, Separados entre Homens e Mulheres
        $totaisMarcasHomens = Veiculo::whereHas('pessoa', function ($query) {
            $query->whereRaw('LOWER(genero) = ?', ['masculino']);
        })->select('marca', \DB::raw('COUNT(*) as total'))
            ->groupBy('marca')
            ->orderByRaw('total DESC')
            ->get();

        $totaisMarcasMulheres = Veiculo::whereHas('pessoa', function ($query) {
            $query->whereRaw('LOWER(genero) = ?', ['feminino']);
        })->select('marca', \DB::raw('COUNT(*) as total'))
            ->groupBy('marca')
            ->orderByRaw('total DESC')
            ->get();

        return response()->json([
            'marcasOrdenadasPorVeiculos' => $marcasOrdenadasPorVeiculos,
            'totaisMarcasHomens' => $totaisMarcasHomens,
            'totaisMarcasMulheres' => $totaisMarcasMulheres,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao gerar o relatório de marcas de veículos.'], 500);
    }
}

 /**
 * @OA\Get(
 *     path="/relatorios/todas-revisoes",
 *     summary="Relatório de todas as revisões",
 *     tags={"Relatórios"},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de todas as revisões",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/RevisaoVeicular"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao obter o relatório de todas as revisões",
 *     ),
 * )
 */
public function relatorioTodasRevisoes()
{
    try {
        $revisoes = RevisaoVeicular::all();
        return response()->json(['revisoes' => $revisoes]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao obter o relatório de todas as revisões.'], 500);
    }
}

/**
 * @OA\Get(
 *     path="/relatorios/marcas-maior-numero-revisoes",
 *     summary="Relatório de marcas com maior número de revisões",
 *     tags={"Relatórios"},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de marcas com maior número de revisões",
 *         @OA\JsonContent(type="array", @OA\Items(
 *             @OA\Property(property="marca", type="string"),
 *             @OA\Property(property="total", type="integer")
 *         ))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao obter o relatório de marcas com maior número de revisões",
 *     ),
 * )
 */
public function relatorioMarcasMaiorNumeroRevisoes()
{
    try {
        // Marcas com Maior Número de Revisões
        $marcasMaisRevisoes = RevisaoVeicular::select('marca', DB::raw('COUNT(*) as total'))
            ->groupBy('marca')
            ->orderByRaw('total DESC')
            ->get();

        return response()->json(['marcasMaisRevisoes' => $marcasMaisRevisoes]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao obter o relatório de marcas com maior número de revisões.'], 500);
    }
}

/**
 * @OA\Get(
 *     path="/relatorios/pessoas-maior-numero-revisoes",
 *     summary="Relatório de pessoas com maior número de revisões",
 *     tags={"Relatórios"},
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de pessoas com maior número de revisões",
 *         @OA\JsonContent(type="array", @OA\Items(
 *             @OA\Property(property="pessoa_id", type="integer"),
 *             @OA\Property(property="total", type="integer")
 *         ))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao obter o relatório de pessoas com maior número de revisões",
 *     ),
 * )
 */
public function relatorioPessoasMaiorNumeroRevisoes()
{
    try {
        // Pessoas com Maior Número de Revisões
        $pessoasMaisRevisoes = RevisaoVeicular::with('pessoa')
            ->select('pessoa_id', DB::raw('COUNT(*) as total'))
            ->groupBy('pessoa_id')
            ->orderByRaw('total DESC')
            ->get();

        return response()->json(['pessoasMaisRevisoes' => $pessoasMaisRevisoes]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao obter o relatório de pessoas com maior número de revisões.'], 500);
    }
}

/**
 * @OA\Get(
 *     path="/relatorios/media-tempo-entre-revisoes/{pessoa_id}",
 *     summary="Relatório de média de tempo entre revisões para uma pessoa",
 *     tags={"Relatórios"},
 *     @OA\Parameter(
 *         name="pessoa_id",
 *         in="path",
 *         required=true,
 *         description="ID da pessoa para a qual calcular a média de tempo entre revisões",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Relatório de média de tempo entre revisões",
 *         @OA\JsonContent(type="array", @OA\Items(
 *             @OA\Property(property="quantidadeRevisoes", type="integer"),
 *             @OA\Property(property="mediaTempoEntreRevisoes", type="integer"),
 *             @OA\Property(property="ultimaRevisaoData", type="string"),
 *             @OA\Property(property="previsaoProximaRevisaoData", type="string")
 *         ))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao calcular a previsão de próxima revisão",
 *     ),
 * )
 */
public function relatorioMediaTempoEntreRevisoes($pessoa_id)
{
    try {
        $revisoes = RevisaoVeicular::where('pessoa_id', $pessoa_id)
            ->orderBy('data_manutencao', 'asc')
            ->get();

        $quantidadeRevisoes = $revisoes->count();

        if ($quantidadeRevisoes < 2) {
            return response()->json(['message' => 'Não há informações suficientes para calcular uma previsão de próxima revisão.'], 200);
        }

        $tempoEntreRevisoes = [];
        for ($i = 1; $i < $quantidadeRevisoes; $i++) {
            $dataRevisaoAnterior = new \DateTime($revisoes[$i - 1]->data_manutencao);
            $dataRevisaoAtual = new \DateTime($revisoes[$i]->data_manutencao);

            $diferencaDias = $dataRevisaoAnterior->diff($dataRevisaoAtual)->days;

            $tempoEntreRevisoes[] = $diferencaDias;
        }

        // Calcula a média de tempo entre revisões
        $mediaTempoEntreRevisoes = round(array_sum($tempoEntreRevisoes) / ($quantidadeRevisoes - 1));

        // Obtém a data da última revisão
        $ultimaRevisaoData = new \DateTime($revisoes->last()->data_manutencao);

        // Estima a data da próxima revisão com base na média, considerando a data atual
        $previsaoProximaRevisaoData = new \DateTime();
        $previsaoProximaRevisaoData->add(new \DateInterval('P' . $mediaTempoEntreRevisoes . 'D'));

        return response()->json([
            'quantidadeRevisoes' => $quantidadeRevisoes,
            'mediaTempoEntreRevisoes' => $mediaTempoEntreRevisoes,
            'ultimaRevisaoData' => $ultimaRevisaoData->format('Y-m-d'),
            'previsaoProximaRevisaoData' => $previsaoProximaRevisaoData->format('Y-m-d'),
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao calcular a previsão de próxima revisão.'], 500);
    }
}
}