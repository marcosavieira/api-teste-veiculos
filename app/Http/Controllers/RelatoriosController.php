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

class RelatoriosController extends Controller
{
    public function relatorioTodasPessoas()
    {
        try {
            $pessoas = Pessoa::all();
            return response()->json(['pessoas' => $pessoas]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter o relatório de todas as pessoas.'], 500);
        }
    }

    

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

    public function relatorioTodasRevisoes()
    {
        try {
            $revisoes = RevisaoVeicular::all();
            return response()->json(['revisoes' => $revisoes]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter o relatório de todas as revisões.'], 500);
        }
    }

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