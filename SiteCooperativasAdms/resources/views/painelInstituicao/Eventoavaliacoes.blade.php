@extends('layouts.instituicao')

@section('titulo', 'Avaliações do evento')

@section('content')
  {{-- Recebe do EventoController@avaliacoes:
       $evento       -> nome, inicio, fim, status
       $distribuicao -> [5 => qtd, 4 => qtd, 3 => qtd, 2 => qtd, 1 => qtd]
       $avaliacoes   -> lista com nome, data, nota e comentario
       A média e o total são calculados aqui a partir da distribuição. --}}
  @php
    $distribuicao = $distribuicao ?? [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    $avaliacoes   = collect($avaliacoes ?? []);

    $notaFiltro = (int) request('nota', 0);
    if ($notaFiltro >= 1 && $notaFiltro <= 5) {
      $avaliacoes = $avaliacoes->where('nota', $notaFiltro);
    }

    $total = array_sum($distribuicao);
    $soma  = collect($distribuicao)->map(fn ($qtd, $nota) => $qtd * $nota)->sum();
    $media = $total > 0 ? $soma / $total : 0;
    $maior = max($distribuicao) ?: 1;

    $estrela = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1z"/></svg>';
    $estrelas = function (float $nota) use ($estrela) {
      $html = '';
      for ($i = 1; $i <= 5; $i++) {
        $html .= str_replace('<svg ', '<svg class="' . ($i <= round($nota) ? 'on' : '') . '" ', $estrela);
      }
      return $html;
    };
    $iniciais = function (string $nome) {
      $partes = preg_split('/\s+/', trim($nome));
      $ini = mb_substr($partes[0], 0, 1) . (count($partes) > 1 ? mb_substr(end($partes), 0, 1) : mb_substr($partes[0], 1, 1));
      return mb_strtoupper($ini);
    };

    $rotulosStatus = ['agendado' => 'Agendado', 'em_andamento' => 'Em andamento', 'concluido' => 'Encerrado', 'cancelado' => 'Cancelado'];
    $classesStatus = ['agendado' => 'agendado', 'em_andamento' => 'andamento', 'concluido' => 'encerrado', 'cancelado' => 'encerrado'];
    $statusEvento  = $evento['status'] ?? 'concluido';
  @endphp

  <a class="inst-voltar" href="{{ route('evento.painel') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
      stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6" />
    </svg>
    Voltar para eventos
  </a>

  <div class="inst-aval">

    <h1 class="inst-aval-titulo">
      {{ $evento['nome'] }}
      <span class="chip-status {{ $classesStatus[$statusEvento] ?? 'encerrado' }}">
        {{ $rotulosStatus[$statusEvento] ?? $statusEvento }}
      </span>
    </h1>
    <p class="inst-aval-sub">{{ $evento['inicio'] }} a {{ $evento['fim'] }} · Avaliações dos usuários</p>

    {{-- Resumo --}}
    <div class="inst-card">
      <div class="aval-resumo">
        <div>
          <p class="aval-rotulo">Nota média</p>
          <div class="aval-nota">
            <strong>{{ $total > 0 ? number_format($media, 1, ',', '') : '-' }}</strong><span>/5</span>
          </div>
          <div class="aval-estrelas" role="img" aria-label="Nota média {{ number_format($media, 1, ',', '') }} de 5">
            {!! $estrelas($media) !!}
          </div>
          <div class="aval-base">Baseado em {{ $total }} {{ $total === 1 ? 'avaliação' : 'avaliações' }}</div>
        </div>

        <div>
          <p class="aval-rotulo">Distribuição das notas</p>
          <div class="aval-dist">
            @foreach([5, 4, 3, 2, 1] as $n)
              @php($qtd = $distribuicao[$n] ?? 0)
              <div class="aval-linha">
                <span class="estrela">{!! $estrela !!} {{ $n }}</span>
                <span class="aval-barra"><i style="width: {{ round($qtd / $maior * 100) }}%"></i></span>
                <span class="aval-qtd">{{ $qtd }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    {{-- Lista --}}
    <div class="inst-card">
      <div class="aval-lista-head">
        <h2>Avaliação dos usuários</h2>
        <form action="{{ url()->current() }}" method="get">
          <select name="nota" class="aval-filtro" aria-label="Filtrar por nota" onchange="this.form.submit()">
            <option value="">Todas as notas</option>
            @foreach([5, 4, 3, 2, 1] as $n)
              <option value="{{ $n }}" {{ $notaFiltro === $n ? 'selected' : '' }}>{{ $n }} {{ $n === 1 ? 'estrela' : 'estrelas' }}</option>
            @endforeach
          </select>
        </form>
      </div>

      <div class="aval-lista">
        @forelse($avaliacoes as $a)
          <article class="aval-item">
            <div class="aval-avatar">{{ $iniciais($a['nome']) }}</div>
            <div>
              <div class="aval-topo">
                <strong>{{ $a['nome'] }}</strong>
                <span class="data">{{ $a['data'] }}</span>
              </div>
              <div class="aval-estrelas" role="img" aria-label="Nota {{ $a['nota'] }} de 5">
                {!! $estrelas($a['nota']) !!}
                <span class="num">{{ $a['nota'] }}/5</span>
              </div>
              @if($a['comentario'] !== '')
                <p class="aval-texto">{{ $a['comentario'] }}</p>
              @endif
            </div>
          </article>
        @empty
          <div class="aval-vazio">
            @if($notaFiltro)
              Nenhuma avaliação com essa nota.
            @else
              Este evento ainda não recebeu avaliações.
            @endif
          </div>
        @endforelse
      </div>
    </div>

  </div>
@endsection