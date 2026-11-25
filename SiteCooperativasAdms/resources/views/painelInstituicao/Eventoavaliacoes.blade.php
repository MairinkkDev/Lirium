@extends('layouts.instituicao') 

@section('titulo', 'Avaliações do evento')
@push('estilos')
<style>
  /* ===== Avaliações do evento: tudo um pouco maior e mais visível ===== */

  .inst-aval { width: 100%; }

  .inst-aval .inst-aval-titulo {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    font-size: 30px !important;
    font-weight: 750;
    line-height: 1.2;
    color: #14281b;
  }

  .inst-aval .chip-status {
    padding: 7px 15px !important;
    font-size: 14px !important;
    font-weight: 700;
  }

  .inst-aval .inst-aval-sub {
    margin-top: 8px;
    margin-bottom: 26px;
    font-size: 16px !important;
    color: #4d5c52;
  }

  .inst-aval .inst-card {
    padding: 30px 34px;
    border-radius: 14px;
  }

  /* ----- resumo ----- */
  .inst-aval .aval-resumo { gap: 48px; }

  .inst-aval .aval-rotulo {
    margin-bottom: 12px;
    font-size: 16px !important;
    font-weight: 700;
    color: #26382c;
  }

  .inst-aval .aval-nota strong {
    font-size: 64px !important;
    font-weight: 800;
    line-height: 1;
    color: #14281b;
  }

  .inst-aval .aval-nota span {
    font-size: 22px !important;
    font-weight: 600;
    color: #5f6e64;
  }

  .inst-aval .aval-base {
    margin-top: 10px;
    font-size: 15px !important;
    color: #4d5c52;
  }

  /* ----- estrelas (apagadas em cinza, acesas em dourado) ----- */
  .inst-aval .aval-estrelas {
    display: flex;
    align-items: center;
    gap: 4px;
    margin: 10px 0;
  }

  .inst-aval .aval-estrelas svg {
    width: 28px !important;
    height: 28px !important;
    fill: #d3d9d5;
  }

  .inst-aval .aval-estrelas svg.on { fill: #f2a900; }

  .inst-aval .aval-estrelas .num {
    margin-left: 8px;
    font-size: 15px;
    font-weight: 700;
    color: #26382c;
  }

  /* ----- distribuição ----- */
  .inst-aval .aval-dist { display: flex; flex-direction: column; gap: 14px; }

  .inst-aval .aval-linha {
    display: grid;
    grid-template-columns: 54px minmax(0, 1fr) 40px;
    align-items: center;
    gap: 14px;
    font-size: 16px;
  }

  .inst-aval .aval-linha .estrela {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 16px !important;
    font-weight: 700;
    color: #26382c;
  }

  .inst-aval .aval-linha .estrela svg {
    width: 18px !important;
    height: 18px !important;
    fill: #f2a900;
  }

  .inst-aval .aval-barra {
    display: block;
    height: 14px !important;
    overflow: hidden;
    border-radius: 999px;
    background: #e3e7e5;
  }

  .inst-aval .aval-barra i {
    display: block;
    height: 100%;
    border-radius: 999px;
    background: #1f7a43;
  }

  .inst-aval .aval-qtd {
    font-size: 16px !important;
    font-weight: 700;
    text-align: right;
    color: #26382c;
  }

  /* ----- lista ----- */
  .inst-aval .aval-lista-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 8px;
  }

  .inst-aval .aval-lista-head h2 {
    font-size: 21px !important;
    font-weight: 750;
    color: #14281b;
  }

  .inst-aval .aval-filtro {
    min-height: 44px;
    padding: 9px 16px;
    font-size: 15px !important;
    border-radius: 9px;
  }

  .inst-aval .aval-item {
    display: flex;
    align-items: flex-start;
    gap: 18px;
    padding: 22px 0;
  }

  .inst-aval .aval-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 56px !important;
    height: 56px !important;
    font-size: 19px !important;
    font-weight: 700;
    border-radius: 50%;
  }

  .inst-aval .aval-topo {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 14px;
  }

  .inst-aval .aval-topo strong {
    font-size: 18px !important;
    font-weight: 700;
    color: #14281b;
  }

  .inst-aval .aval-topo .data {
    font-size: 14px !important;
    color: #5f6e64;
  }

  .inst-aval .aval-item .aval-estrelas svg {
    width: 22px !important;
    height: 22px !important;
  }

  .inst-aval .aval-texto {
    margin-top: 6px;
    font-size: 16px !important;
    line-height: 1.6;
    color: #2b3a30;
  }

  .inst-aval .aval-vazio {
    padding: 40px 0;
    font-size: 17px !important;
    text-align: center;
    color: #5f6e64;
  }

  .inst-voltar { font-size: 16px; }
  .inst-voltar svg { width: 20px; height: 20px; }

  @media (max-width: 700px) {
    .inst-aval .inst-aval-titulo { font-size: 24px !important; }
    .inst-aval .inst-card { padding: 22px 20px; }
    .inst-aval .aval-nota strong { font-size: 52px !important; }
    .inst-aval .aval-estrelas svg { width: 24px !important; height: 24px !important; }
    .inst-aval .aval-lista-head { flex-wrap: wrap; }
  }
</style>
@endpush
@section('content')
  {{-- Recebe do EventoController@avaliacoes:
       $dadosEvento       -> nome, inicio, fim, status
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
    $statusEvento  = $dadosEvento['status'] ?? 'concluido';
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
      {{ $dadosEvento['nome'] }}
      <span class="chip-status {{ $classesStatus[$statusEvento] ?? 'encerrado' }}">
        {{ $rotulosStatus[$statusEvento] ?? $statusEvento }}
      </span>
    </h1>
    <p class="inst-aval-sub">{{ $dadosEvento['inicio'] }} a {{ $dadosEvento['fim'] }} · Avaliações dos usuários</p>

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