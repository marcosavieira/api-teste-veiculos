<?php
namespace App\Http\Controllers;

use App\Models\Pessoa;
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
}