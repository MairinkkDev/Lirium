<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicação: {{ $publicacao->tituloPublicacao }}</title>
</head>
<body>

    @include('partials.perfilLogado')

    <p><a href="{{ route('publicacao.painel') }}">&larr; Voltar para publicações</a></p>

    <h2>{{ $publicacao->tituloPublicacao }}</h2>

    @if(session('mensagem'))
        <p>{{ session('mensagem') }}</p>
    @endif

    @foreach($errors->all() as $erro)
        <p>{{ $erro }}</p>
    @endforeach

    <h3>Imagens atuais</h3>

    @forelse($publicacao->imagens as $img)
        <span style="display:inline-block; margin-right:10px;">
            <img src="{{ Storage::url($img->caminhoImagemPublicacao) }}" width="100"><br>

            <form action="{{ route('publicacao.imagem.excluir', [$publicacao->idPublicacao, $img->idImagemPublicacao]) }}"
                  method="post"
                  data-confirmar="Remover esta imagem?">
                @csrf
                @method('DELETE')
                <button type="submit">Remover</button>
            </form>
        </span>
    @empty
        <p>Nenhuma imagem cadastrada.</p>
    @endforelse

    <form action="{{ route('publicacao.atualizar', $publicacao->idPublicacao) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <p>
            <label>Título</label><br>
            <input type="text" name="tituloPublicacao" value="{{ old('tituloPublicacao', $publicacao->tituloPublicacao) }}">
        </p>

        <p>
            <label>Descrição</label><br>
            <textarea name="descricaoPublicacao">{{ old('descricaoPublicacao', $publicacao->descricaoPublicacao) }}</textarea>
        </p>

        <p>
            <label>Categoria</label><br>
            <select name="idCategoriaPublicacao">
                @foreach($categorias as $c)
                    <option value="{{ $c->idCategoriaPublicacao }}"
                        {{ old('idCategoriaPublicacao', $publicacao->idCategoriaPublicacao) == $c->idCategoriaPublicacao ? 'selected' : '' }}>
                        {{ $c->descricaoCategoriaPublicacao }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Status</label><br>
            <select name="statusPublicacao">
                <option value="ativo"   {{ old('statusPublicacao', $publicacao->statusPublicacao) === 'ativo'   ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ old('statusPublicacao', $publicacao->statusPublicacao) === 'inativo' ? 'selected' : '' }}>Inativo</option>
            </select>
        </p>

        <p>
            <label>Adicionar novas imagens</label><br>
            <input type="file" name="imagens[]" accept="image/*" multiple>
        </p>

        <button type="submit">Salvar alterações</button>
    </form>

    @include('partials.confirmaMensagem')

</body>
</html>