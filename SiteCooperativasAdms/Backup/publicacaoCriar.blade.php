<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Publicação</title>
</head>
<body>

    @include('partials.perfilLogado')

    <p><a href="{{ route('publicacao.painel') }}">&larr; Voltar para publicações</a></p>

    <h2>Criar Publicação</h2>

    @foreach($errors->all() as $erro)
        <p>{{ $erro }}</p>
    @endforeach

    <form action="{{ route('publicacao.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <p>
            <label>Título</label><br>
            <input type="text" name="tituloPublicacao" value="{{ old('tituloPublicacao') }}">
        </p>

        <p>
            <label>Descrição</label><br>
            <textarea name="descricaoPublicacao">{{ old('descricaoPublicacao') }}</textarea>
        </p>

        <p>
            <label>Categoria</label><br>
            <select name="idCategoriaPublicacao">
                @foreach($categorias as $c)
                    <option value="{{ $c->idCategoriaPublicacao }}"
                        {{ old('idCategoriaPublicacao') == $c->idCategoriaPublicacao ? 'selected' : '' }}>
                        {{ $c->descricaoCategoriaPublicacao }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Imagens</label><br>
            <input type="file" name="imagens[]" accept="image/*" multiple>
        </p>

        <button type="submit">Criar publicação</button>
    </form>

</body>
</html>