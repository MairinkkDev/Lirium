<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evento: {{ $evento->nomeEvento }}</title>
</head>
<body>

    @include('partials.perfilLogado')

    <p><a href="{{ route('evento.painel') }}">&larr; Voltar para eventos</a></p>

    <h2>{{ $evento->nomeEvento }}</h2>

    @if(session('mensagem'))
        <p>{{ session('mensagem') }}</p>
    @endif

    @foreach($errors->all() as $erro)
        <p>{{ $erro }}</p>
    @endforeach

    <form action="{{ route('evento.atualizar', $evento->idEvento) }}" method="post">
        @csrf
        @method('PATCH')

        <p>
            <label>Nome do evento</label><br>
            <input type="text" name="nomeEvento" value="{{ old('nomeEvento', $evento->nomeEvento) }}">
        </p>

        <p>
            <label>Descrição</label><br>
            <textarea name="descricaoEvento">{{ old('descricaoEvento', $evento->descricaoEvento) }}</textarea>
        </p>

        <p>
            <label>Data/hora de início</label><br>
            <input type="datetime-local" name="dataInicioEvento"
                   value="{{ old('dataInicioEvento', optional($evento->dataInicioEvento)->format('Y-m-d\TH:i')) }}">
        </p>

        <p>
            <label>Data/hora de término (opcional)</label><br>
            <input type="datetime-local" name="dataFimEvento"
                   value="{{ old('dataFimEvento', optional($evento->dataFimEvento)->format('Y-m-d\TH:i')) }}">
        </p>

        <p>
            <label>CEP</label><br>
            <input type="text" name="cepEnderecoEvento" value="{{ old('cepEnderecoEvento', $evento->cepEnderecoEvento) }}">
        </p>

        <p>
            <label>Logradouro</label><br>
            <input type="text" name="logradouroEnderecoEvento" value="{{ old('logradouroEnderecoEvento', $evento->logradouroEnderecoEvento) }}">
        </p>

        <p>
            <label>Número</label><br>
            <input type="text" name="numeroEnderecoEvento" value="{{ old('numeroEnderecoEvento', $evento->numeroEnderecoEvento) }}">
        </p>

        <p>
            <label>Complemento</label><br>
            <input type="text" name="complementoEnderecoEvento" value="{{ old('complementoEnderecoEvento', $evento->complementoEnderecoEvento) }}">
        </p>

        <p>
            <label>Bairro</label><br>
            <input type="text" name="bairroEnderecoEvento" value="{{ old('bairroEnderecoEvento', $evento->bairroEnderecoEvento) }}">
        </p>

        <p>
            <label>Cidade</label><br>
            <input type="text" name="cidadeEnderecoEvento" value="{{ old('cidadeEnderecoEvento', $evento->cidadeEnderecoEvento) }}">
        </p>

        <p>
            <label>UF</label><br>
            <input type="text" name="ufEnderecoEvento" maxlength="2" value="{{ old('ufEnderecoEvento', $evento->ufEnderecoEvento) }}">
        </p>

        <p>
            <label>Status</label><br>
            <select name="statusEvento">
                <option value="agendado" {{ old('statusEvento', $evento->statusEvento) === 'agendado' ? 'selected' : '' }}>Agendado</option>
                <option value="em_andamento" {{ old('statusEvento', $evento->statusEvento) === 'em_andamento' ? 'selected' : '' }}>Em andamento</option>
                <option value="concluido" {{ old('statusEvento', $evento->statusEvento) === 'concluido' ? 'selected' : '' }}>Concluído</option>
                <option value="cancelado" {{ old('statusEvento', $evento->statusEvento) === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </p>

        <button type="submit">Salvar alterações</button>
    </form>

    @include('partials.confirmaMensagem')

</body>
</html>