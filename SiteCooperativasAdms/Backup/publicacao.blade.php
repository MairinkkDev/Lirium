<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicações</title>
</head>
<body>

    @include('partials.perfilLogado')

    <h2>Publicações</h2>

    <section>
        <a href="/welcome"> home </a>
    </section>

    @if(session('mensagem'))
        <p>{{ session('mensagem') }}</p>
    @endif

    <p>
        <a href="{{ route('publicacao.painel') }}">
            {{ $status === 'todos' ? '[Todas]' : 'Todas' }}
        </a>

        <a href="{{ route('publicacao.painel') }}?status=ativo">
            {{ $status === 'ativo' ? '[Ativas]' : 'Ativas' }}
        </a>

        <a href="{{ route('publicacao.painel') }}?status=inativo">
            {{ $status === 'inativo' ? '[Inativas]' : 'Inativas' }}
        </a>
    </p>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Título</th>
                <th>Categoria</th>
                <th>Data</th>
                <th>Status</th>
                <th colspan="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($publicacao as $p)
                <tr>
                    <td>
                        @if($p->imagens->first())
                            <img src="{{ Storage::url($p->imagens->first()->caminhoImagemPublicacao) }}"
                                 alt="{{ $p->tituloPublicacao }}" width="80">
                        @else
                            sem imagem
                        @endif
                    </td>
                    <td>{{ $p->tituloPublicacao }}</td>
                    <td>{{ $p->categoria->descricaoCategoriaPublicacao ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->dataCriacaoPublicacao)->format('d/m/Y H:i') }}</td>
                    <td>{{ $p->statusPublicacao }}</td>
                    <td>
                        <a href="{{ route('publicacao.detalhe', $p->idPublicacao) }}">Editar</a>
                    </td>
                    <td>
                        <form action="{{ route('publicacao.excluir', $p->idPublicacao) }}"
                              method="post"
                              data-confirmar="Excluir a publicação {{ $p->tituloPublicacao }}? Esta ação não pode ser desfeita.">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Nenhuma publicação encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $publicacao->links('vendor.pagination.simples') }}
    </div>

    <p>
        <a href="{{ route('publicacao.criar.form') }}">
            <button type="button">Criar publicação</button>
        </a>
    </p>

    @include('partials.confirmaMensagem')

</body>
</html>