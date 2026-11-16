<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
</head>
<body>

    @include('partials.perfilLogado')

    <h2>Eventos</h2>

      <section>
    <a href="/welcome"> home </a>
    </section>

    @if(session('mensagem'))
        <p>{{ session('mensagem') }}</p>
    @endif

    <p>
        <a href="{{ route('evento.painel') }}">
            {{ $status === 'todos' ? '[Todos]' : 'Todos' }}
        </a>

        <a href="{{ route('evento.painel') }}?status=agendado">
            {{ $status === 'agendado' ? '[Agendados]' : 'Agendados' }}
        </a>

        <a href="{{ route('evento.painel') }}?status=em_andamento">
            {{ $status === 'em_andamento' ? '[Em andamento]' : 'Em andamento' }}
        </a>

        <a href="{{ route('evento.painel') }}?status=concluido">
            {{ $status === 'concluido' ? '[Concluídos]' : 'Concluídos' }}
        </a>

        <a href="{{ route('evento.painel') }}?status=cancelado">
            {{ $status === 'cancelado' ? '[Cancelados]' : 'Cancelados' }}
        </a>
    </p>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Status</th>
                <th colspan="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($evento as $e)
                <tr>
                    <td>{{ $e->nomeEvento }}</td>
                    <td>{{ $e->dataInicioEvento->format('d/m/Y H:i') }}</td>
                    <td>{{ $e->dataFimEvento?->format('d/m/Y H:i') ?? '-' }}</td>
                    <td>{{ str_replace('_', ' ', $e->statusEvento) }}</td>
                    <td>
                        <a href="{{ route('evento.detalhe', $e->idEvento) }}">Editar</a>
                    </td>
                    <td>
                        <form action="{{ route('evento.excluir', $e->idEvento) }}"
                              method="post"
                              data-confirmar="Excluir o evento {{ $e->nomeEvento }}? Esta ação não pode ser desfeita.">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Nenhum evento encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $evento->links('vendor.pagination.simples') }}
    </div>

    <p>
        <a href="{{ route('evento.criar.form') }}">
            <button type="button">Criar evento</button>
        </a>
    </p>

    @include('partials.confirmaMensagem')

</body>
</html>