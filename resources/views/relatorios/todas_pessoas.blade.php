<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Todas as Pessoas</title>
</head>
<body>
    <h1>Relatório de Todas as Pessoas</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Idade</th>
                <th>Genero</th>
                <th>Qtd Veiculos</th>
                
                <!-- Adicione mais colunas conforme necessário -->
            </tr>
        </thead>
        <tbody>
            @foreach($pessoas as $pessoa)
                <tr>
                    <td>{{ $pessoa->id }}</td>
                    <td>{{ $pessoa->nome }}</td>
                    <td>{{ $pessoa->idade }}</td>
                    <td>{{ $pessoa->genero }}</td>
                    <td>{{ $pessoa->quantidade_veiculos }}</td>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
            @endforeach
        </tbody>
    </table>
    <h1>Média de Idade por Gênero</h1>

<p>Média de Idade Homens: {{ $mediaIdadeHomens }}</p>
<p>Média de Idade Mulheres: {{ $mediaIdadeMulheres }}</p>

<!-- Insere a imagem do gráfico no PDF -->
<img src="{{ 'data:image/png;base64,' . base64_encode($chartImage) }}" alt="Gráfico" style="width: 100%; height: auto;">

</body>
</html>
