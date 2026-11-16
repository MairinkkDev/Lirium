<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento</title>
</head>
<body>

    @include('partials.perfilLogado')

    <p><a href="{{ route('evento.painel') }}">&larr; Voltar para eventos</a></p>

    <h2>Criar Evento</h2>

    @foreach($errors->all() as $erro)
        <p>{{ $erro }}</p>
    @endforeach

    <form action="{{ route('evento.store') }}" method="post">
        @csrf

        <p>
            <label>Nome do evento</label><br>
            <input type="text" name="nomeEvento" value="{{ old('nomeEvento') }}">
        </p>

        <p>
            <label>Descrição</label><br>
            <textarea name="descricaoEvento">{{ old('descricaoEvento') }}</textarea>
        </p>

        <p>
            <label>Data/hora de início</label><br>
            <input type="datetime-local" name="dataInicioEvento" value="{{ old('dataInicioEvento') }}">
        </p>

        <p>
            <label>Data/hora de término (opcional)</label><br>
            <input type="datetime-local" name="dataFimEvento" value="{{ old('dataFimEvento') }}">
        </p>

        <p>
            <label>CEP</label><br>
            <input type="text" name="cepEnderecoEvento" value="{{ old('cepEnderecoEvento') }}">
        </p>

        <p>
            <label>Logradouro</label><br>
            <input type="text" name="logradouroEnderecoEvento" value="{{ old('logradouroEnderecoEvento') }}">
        </p>

        <p>
            <label>Número</label><br>
            <input type="text" name="numeroEnderecoEvento" value="{{ old('numeroEnderecoEvento') }}">
        </p>

        <p>
            <label>Complemento</label><br>
            <input type="text" name="complementoEnderecoEvento" value="{{ old('complementoEnderecoEvento') }}">
        </p>

        <p>
            <label>Bairro</label><br>
            <input type="text" name="bairroEnderecoEvento" value="{{ old('bairroEnderecoEvento') }}">
        </p>

        <p>
            <label>Cidade</label><br>
            <input type="text" name="cidadeEnderecoEvento" value="{{ old('cidadeEnderecoEvento') }}">
        </p>

        <p>
            <label>UF</label><br>
            <input type="text" name="ufEnderecoEvento" maxlength="2" value="{{ old('ufEnderecoEvento') }}">
        </p>

        <button type="submit">Criar evento</button>
    </form>

</body>
</html>