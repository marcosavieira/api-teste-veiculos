<?php
namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\Models\Veiculo;
use Illuminate\Http\Request;

class RelatoriosController extends Controller
{
    public function relatorioTodasPessoas()
    {
        $pessoas = Pessoa::all();

        return response()->json(['pessoas' => $pessoas]);
    }

    public function relatorioMediaIdadePorGenero()
   {
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
   }

   public function relatorioTodosVeiculos()
{
    // Todos os Veículos
    $todosVeiculos = Veiculo::all();

    return response()->json(['todos_veiculos' => $todosVeiculos]);
}

public function relatorioVeiculosPorPessoa()
{
    // Todos os Veículos por Pessoa Ordenados por Nome de Pessoa
    $veiculosPorPessoa = Veiculo::with(['pessoa' => function ($query) {
        $query->orderBy('nome');
    }])->get();

    return response()->json(['veiculosPorPessoa' => $veiculosPorPessoa]);
}
    public function relatorioContagemPorGenero()
    {
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
    }
    
    public function relatorioMarcasVeiculos()
{
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
}

}