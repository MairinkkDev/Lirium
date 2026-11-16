@extends('layouts.instituicao')

@section('titulo', 'Eventos')

@section('content')
  @php
    $abas = [
      'todos'        => 'Todas',
      'em_andamento' => 'Em Andamento',
      'agendado'     => 'Agendados',
      'concluido'    => 'Encerrados',
    ];

    $rotulos = [
      'em_andamento' => 'Em andamento',
      'agendado'     => 'Agendado',
      'concluido'    => 'Encerrado',
      'cancelado'    => 'Cancelado',
    ];

    $classes = [
      'em_andamento' => 'andamento',
      'agendado'     => 'agendado',
      'concluido'    => 'encerrado',
      'cancelado'    => 'encerrado',
    ];
  @endphp

  <div class="inst-page-head">
    <div>
      <h1 class="page-title">Eventos</h1>
      <p class="page-sub">Crie, altere e encerre os eventos da sua instituição</p>
    </div>

    <a href="{{ route('evento.criar.form') }}" class="btn-mini btn-cadastrar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
        <circle cx="12" cy="12" r="10" />
        <line x1="12" y1="8" x2="12" y2="16" />
        <line x1="8" y1="12" x2="16" y2="12" />
      </svg>
      Criar evento
    </a>
  </div>

  <nav class="inst-tabs" aria-label="Filtrar eventos por status">
    @foreach($abas as $valor => $rotulo)
      <a href="{{ route('evento.painel') }}{{ $valor === 'todos' ? '' : '?status=' . $valor }}"
        class="inst-tab {{ $status === $valor ? 'active' : '' }}"
        @if($status === $valor) aria-current="page" @endif>{{ $rotulo }}</a>
    @endforeach
  </nav>

  <form action="{{ route('evento.painel') }}" method="get" class="inst-filters" style="margin-top:0;">
    @if($status !== 'todos')
      <input type="hidden" name="status" value="{{ $status }}">
    @endif
    <label class="inst-search sm">
      <input type="text" name="busca" placeholder="Buscar evento por nome" value="{{ $busca }}"
        aria-label="Buscar evento por nome">
    </label>
  </form>

  <div class="inst-card" style="margin-top:18px;">
    <div class="inst-table-wrap">
      <table class="inst-table eventos">
        <thead>
          <tr>
            <th>Evento</th>
            <th>Início</th>
            <th>Encerramento</th>
            <th>Status</th>
            <th></th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($eventos as $e)
            <tr>
              <td>{{ $e->nomeEvento }}</td>
              <td>{{ $e->dataInicioEvento?->format('d/m/Y') ?? '-' }}</td>
              <td>{{ $e->dataFimEvento?->format('d/m/Y') ?? '-' }}</td>
              <td>
                <span class="chip-status {{ $classes[$e->statusEvento] ?? 'encerrado' }}">
                  {{ $rotulos[$e->statusEvento] ?? $e->statusEvento }}
                </span>
              </td>

              @if($e->statusEvento === 'concluido')
               <td class="nota-evento">
  {{ $e->nota_media !== null ? number_format($e->nota_media, 1, ',', '') : '-' }}
</td>
                <td>
                  <a href="{{ route('evento.avaliacoes', $e->idEvento) }}" class="btn-alterar">Ver Avaliações</a>
                </td>
              @elseif($e->statusEvento === 'cancelado')
                <td></td>
                <td>-</td>
              @else
                <td>
                  <a href="{{ route('evento.detalhe', $e->idEvento) }}" class="btn-alterar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                      stroke-linejoin="round">
                      <path d="M12 20h9" />
                      <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                    </svg>
                    Alterar
                  </a>
                </td>
                <td>
                  <form action="{{ route('evento.encerrar', $e->idEvento) }}" method="POST"
                    onsubmit="return confirm('Encerrar este evento?');" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-alterar btn-encerrar">
                      <svg viewBox="0 0 24 24">
                        <rect x="5" y="5" width="14" height="14" rx="2" />
                      </svg>
                      Encerrar
                    </button>
                  </form>
                </td>
              @endif
            </tr>
          @empty
            <tr>
              <td colspan="6" class="inst-empty">
                @if($busca !== '' || $status !== 'todos')
                  Nenhum evento encontrado com esse filtro.
                @else
                  Nenhum evento criado. Use “Criar evento” para começar.
                @endif
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($eventos->hasPages())
    <div style="margin-top:16px;">
      {{ $eventos->links() }}
    </div>
  @endif
@endsection