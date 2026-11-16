@extends('layouts.admin')

@section('titulo', 'Dashboard')

@push('estilos')
  <style>
    /* Estilos exclusivos do /dash. Tudo escopado em .dash para não afetar as outras telas do ADM. */
    .dash {
      --dash-orange: #e8892b;
      --dash-card-shadow: 0 1px 3px rgba(15, 40, 25, .06);
    }

    .dash .page-title { font-size: 22px; font-weight: 700; }
    .dash .page-sub   { font-size: 13px; }

    .dash-card {
      background: var(--white);
      border-radius: 12px;
      padding: 18px 20px;
      box-shadow: var(--dash-card-shadow);
    }

    .dash-card-title {
      margin: 0 0 12px;
      font-family: 'Raleway', sans-serif;
      font-size: 13px;
      font-weight: 700;
      color: var(--text-dark);
    }

    .dash-card-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 12px;
    }

    .dash-card-head .dash-card-title { margin: 0; }

    .dash-link {
      font-size: 10.5px;
      font-weight: 700;
      color: var(--green-1);
      text-decoration: none;
      white-space: nowrap;
    }

    .dash-link:hover { text-decoration: underline; }
    .dash-link:focus-visible { outline: 2px solid var(--green-1); outline-offset: 2px; border-radius: 4px; }

    /* ---------- cards de resumo ---------- */
    .dash-stats {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 16px;
      margin-bottom: 16px;
    }

    .dash-stat-top {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 14px;
      font-size: 11.5px;
      font-weight: 600;
      color: var(--text-dark);
    }

    .dash-stat-icon {
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      background: var(--icon-bg);
      border-radius: 8px;
      color: var(--icon-color);
    }

    .dash-stat-icon svg { width: 18px; height: 18px; }

    .dash-stat-value {
      font-size: 26px;
      font-weight: 700;
      line-height: 1.1;
      color: var(--text-dark);
    }

    .dash-var {
      margin-top: 10px;
      font-size: 9.5px;
      font-weight: 600;
      color: var(--text-muted);
    }

    .dash-var b { font-weight: 700; }
    .dash-var.good b { color: var(--green-1); }
    .dash-var.bad b  { color: #e14b4b; }

    .dash-stat.alerta {
      background: #fff4e3;
      border: 1px solid #f3d6a6;
    }

    .dash-stat.alerta .dash-stat-icon { background: #fde3b8; color: #c9700b; }
    .dash-stat.alerta .dash-stat-top,
    .dash-stat.alerta .dash-stat-value { color: #7a4a0a; }
    .dash-stat.alerta .dash-link { display: inline-block; margin-top: 10px; color: #a85c08; }

    /* ---------- linha: gráfico + online ---------- */
    .dash-row {
      display: grid;
      gap: 16px;
      margin-bottom: 16px;
      align-items: stretch;
    }

    .dash-row.chart-online { grid-template-columns: minmax(0, 1.75fr) minmax(0, 1fr); }
    .dash-row.duo          { grid-template-columns: repeat(2, minmax(0, 1fr)); }

    .dash-legenda {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 9px;
      font-weight: 600;
      color: var(--text-muted);
    }

    .dash-legenda i {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--green-1);
    }

    /* gráfico de linha (SVG + rótulos em HTML, escala com a largura) */
    .dash-chart {
      display: grid;
      grid-template-columns: 30px minmax(0, 1fr);
      column-gap: 8px;
    }

    .dash-chart-y {
      position: relative;
      height: 150px;
      font-size: 8px;
      font-weight: 600;
      color: var(--text-light);
      text-align: right;
    }

    .dash-chart-y span {
      position: absolute;
      right: 0;
      transform: translateY(-50%);
    }

    .dash-chart-plot {
      position: relative;
      height: 150px;
    }

    .dash-chart-plot .grade {
      position: absolute;
      left: 0;
      right: 0;
      border-top: 1px solid var(--border);
    }

    .dash-chart-plot svg {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      overflow: visible;
    }

    .dash-chart-plot .ponto {
      position: absolute;
      width: 9px;
      height: 9px;
      background: #fff;
      border: 2px solid var(--green-1);
      border-radius: 50%;
      transform: translate(-50%, -50%);
    }

    .dash-chart-x {
      grid-column: 2;
      display: flex;
      justify-content: space-between;
      margin-top: 8px;
      font-size: 8px;
      font-weight: 600;
      color: var(--text-light);
    }

    /* usuários online */
    .dash-online-valor {
      font-size: 30px;
      font-weight: 700;
      line-height: 1.1;
      color: var(--text-dark);
    }

    .dash-online-sub {
      margin: 4px 0 14px;
      font-size: 9.5px;
      font-weight: 500;
      color: var(--text-muted);
    }

    .dash-bar {
      display: flex;
      height: 8px;
      overflow: hidden;
      background: #e3e7e5;
      border-radius: 999px;
    }

    .dash-bar > i { display: block; height: 100%; }
    .dash-bar .verde   { background: #1f7a43; }
    .dash-bar .laranja { background: var(--dash-orange); }
    .dash-bar .azul    { background: #2563c9; }

    .dash-lista { margin-top: 14px; display: flex; flex-direction: column; gap: 8px; }

    .dash-lista-linha {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      font-size: 10px;
      font-weight: 500;
      color: var(--text-dark);
    }

    .dash-lista-linha .rotulo { display: inline-flex; align-items: center; gap: 8px; }
    .dash-lista-linha b { font-weight: 700; }

    .dash-quad {
      width: 8px;
      height: 8px;
      border-radius: 2px;
      background: #d4d9d6;
    }

    .dash-quad.verde { background: #1f7a43; }

    /* cooperativas e ONGs */
    .dash-duplo {
      display: flex;
      gap: 40px;
      margin-bottom: 14px;
    }

    .dash-duplo .rot {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 9.5px;
      font-weight: 600;
      color: var(--text-muted);
    }

    .dash-duplo .rot i { width: 7px; height: 7px; border-radius: 50%; }
    .dash-duplo .num { margin-top: 4px; font-size: 24px; font-weight: 700; color: var(--text-dark); }

    /* denúncias por status */
    .dash-status { display: flex; flex-direction: column; gap: 12px; }

    .dash-status-topo {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
      font-size: 10px;
      font-weight: 500;
      color: var(--text-dark);
    }

    .dash-status-topo b { font-weight: 700; }

    /* atividades recentes */
    .dash-atividades { padding: 0; overflow: hidden; }
    .dash-atividades .dash-card-head { padding: 16px 20px 12px; margin: 0; }

    .dash-ativ-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 20px;
      border-top: 1px solid var(--border);
    }

    .dash-ativ-icone {
      width: 28px;
      height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      border-radius: 50%;
      background: var(--icon-bg);
      color: var(--icon-color);
    }

    .dash-ativ-icone.denuncia { background: #fdecdc; color: #c9700b; }
    .dash-ativ-icone svg { width: 14px; height: 14px; }

    .dash-ativ-texto { flex: 1; min-width: 0; }

    .dash-ativ-texto strong {
      display: block;
      font-size: 10.5px;
      font-weight: 700;
      color: var(--text-dark);
    }

    .dash-ativ-texto span {
      display: block;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      font-size: 9px;
      font-weight: 500;
      color: var(--text-muted);
    }

    .dash-ativ-lado { display: flex; align-items: center; gap: 14px; flex-shrink: 0; }
    .dash-ativ-lado .badge-status { padding: 3px 11px; font-size: 9px; }
    .dash-ativ-lado .data { font-size: 9px; font-weight: 500; color: var(--text-muted); white-space: nowrap; }

    .dash-vazio { padding: 24px 20px; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--border); }

    @media (max-width: 1100px) {
      .dash-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 900px) {
      .dash-row.chart-online,
      .dash-row.duo { grid-template-columns: 1fr; }
    }

    @media (max-width: 560px) {
      .dash-stats { grid-template-columns: 1fr; }
      .dash-ativ-lado .data { display: none; }
    }
  </style>
@endpush

@section('content')
  @php
    // ---- variação (↑/↓ vs. mês anterior). Para denúncias, queda é bom. ----
    $variacao = function (array $v, bool $quedaEBom = false) {
      if ($v['direcao'] === 'neutro') {
        return ['classe' => '', 'texto' => '— 0%', 'extra' => ''];
      }
      $subiu  = $v['direcao'] === 'up';
      $bom    = $quedaEBom ? !$subiu : $subiu;
      return [
        'classe' => $bom ? 'good' : 'bad',
        'texto'  => ($subiu ? '↑ ' : '↓ ') . $v['percentual'] . '%',
        'extra'  => ($quedaEBom && !$subiu) ? ' (queda é bom)' : '',
      ];
    };

    $varUsuarios     = $variacao($variacaoUsuarios);
    $varInstituicoes = $variacao($variacaoInstituicoes);
    $varDenuncias    = $variacao($variacaoDenuncias, true);

    // ---- gráfico: novos cadastros por dia ----
    $totais = $cadastrosPorDia->pluck('total')->all();
    $qtdDias = count($totais);
    $maxDia = max($totais) ?: 0;

    $arredondar = function (float $x) {
      $p = 10 ** floor(log10($x));
      foreach ([1, 2, 5, 10] as $m) {
        if ($x <= $m * $p) return $m * $p;
      }
      return 10 * $p;
    };

    $passo = $arredondar(max(1, $maxDia / 3));
    $topo  = $passo * 3;

    $pontos = [];
    foreach ($totais as $i => $t) {
      $x = $qtdDias > 1 ? ($i / ($qtdDias - 1)) * 100 : 0;
      $y = 100 - ($t / $topo) * 100;
      $pontos[] = [round($x, 2), round($y, 2)];
    }

    $linha = 'M' . implode(' L', array_map(fn ($p) => $p[0] . ' ' . $p[1], $pontos));
    $area  = $linha . ' L100 100 L0 100 Z';
    $ultimo = end($pontos);
    $totalPeriodo = array_sum($totais);

    $indicesX = [0, 10, 20, $qtdDias - 1];

    // ---- barras ----
    $totalInst   = max($instituicoesAtivas + $instituicoesInativas, 0);
    $pctAtivas   = $totalInst > 0 ? ($instituicoesAtivas / $totalInst) * 100 : 0;
    $pctSusp     = $totalInst > 0 ? ($instituicoesInativas / $totalInst) * 100 : 0;

    $pctDen = function (int $qtd) use ($totalDenuncias) {
      if ($totalDenuncias <= 0 || $qtd <= 0) return 0;
      return max(($qtd / $totalDenuncias) * 100, 2);
    };

    $pctOnline = $totalUsuarios > 0 ? ($usuariosOnline / $totalUsuarios) * 100 : 0;
  @endphp

  <div class="dash">

    <div class="page-head">
      <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-sub">Visão geral do sistema</p>
      </div>
    </div>

    {{-- ===== Cards de resumo ===== --}}
    <div class="dash-stats">
      <div class="dash-card dash-stat">
        <div class="dash-stat-top">
          <span class="dash-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <circle cx="12" cy="10" r="3.2" />
              <path d="M5.8 19c1.2-2.6 3.5-4 6.2-4s5 1.4 6.2 4" />
            </svg></span>
          Usuários cadastrados
        </div>
        <div class="dash-stat-value">{{ number_format($totalUsuarios, 0, ',', '.') }}</div>
        <div class="dash-var {{ $varUsuarios['classe'] }}"><b>{{ $varUsuarios['texto'] }}</b> vs. mês anterior</div>
      </div>

      <div class="dash-card dash-stat">
        <div class="dash-stat-top">
          <span class="dash-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 21c4-2.6 8-5.6 8-10.2A5 5 0 0 0 12 8a5 5 0 0 0-8 2.8C4 15.4 8 18.4 12 21z" />
              <path d="M12 8V4" />
            </svg></span>
          Cooperativas e ONGs
        </div>
        <div class="dash-stat-value">{{ number_format($totalInstituicoes, 0, ',', '.') }}</div>
        <div class="dash-var {{ $varInstituicoes['classe'] }}"><b>{{ $varInstituicoes['texto'] }}</b> vs. mês anterior</div>
      </div>

      <div class="dash-card dash-stat">
        <div class="dash-stat-top">
          <span class="dash-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="7" r="3.2" />
              <path d="M3.5 21c0-3.6 2.5-6 5.5-6s5.5 2.4 5.5 6" />
              <line x1="19" y1="8" x2="19" y2="13" />
              <circle cx="19" cy="16.3" r="0.6" fill="currentColor" stroke="none" />
            </svg></span>
          Denúncias registradas
        </div>
        <div class="dash-stat-value">{{ number_format($totalDenuncias, 0, ',', '.') }}</div>
        <div class="dash-var {{ $varDenuncias['classe'] }}"><b>{{ $varDenuncias['texto'] }}</b> vs. mês anterior{{ $varDenuncias['extra'] }}</div>
      </div>

      <div class="dash-card dash-stat alerta">
        <div class="dash-stat-top">
          <span class="dash-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M10.3 3.9L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z" />
              <line x1="12" y1="9" x2="12" y2="13" />
              <circle cx="12" cy="17" r="0.6" fill="currentColor" stroke="none" />
            </svg></span>
          Denúncias pendentes
        </div>
        <div class="dash-stat-value">{{ number_format($denunciasPendentes, 0, ',', '.') }}</div>
        <a class="dash-link" href="{{ url('/denuncia?status=pendente') }}">Revisar agora ›</a>
      </div>
    </div>

    {{-- ===== Gráfico + usuários online ===== --}}
    <div class="dash-row chart-online">
      <div class="dash-card">
        <div class="dash-card-head">
          <h2 class="dash-card-title">Novos cadastros por dia</h2>
          <span class="dash-legenda"><i></i> Usuários · últimos 30 dias</span>
        </div>

        <div class="dash-chart" role="img"
          aria-label="Gráfico de linha com os novos cadastros por dia nos últimos 30 dias. Total no período: {{ $totalPeriodo }}.">
          <div class="dash-chart-y" aria-hidden="true">
            @foreach([3, 2, 1, 0] as $k)
              <span style="top: {{ (3 - $k) / 3 * 100 }}%">{{ number_format($passo * $k, 0, ',', '.') }}</span>
            @endforeach
          </div>

          <div class="dash-chart-plot">
            @foreach([0, 1, 2, 3] as $k)
              <div class="grade" style="top: {{ $k / 3 * 100 }}%"></div>
            @endforeach

            <svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
              <path d="{{ $area }}" fill="rgba(31,138,76,.12)" />
              <path d="{{ $linha }}" fill="none" stroke="#1f7a43" stroke-width="2" stroke-linejoin="round"
                stroke-linecap="round" vector-effect="non-scaling-stroke" />
            </svg>

            <span class="ponto" style="left: {{ $ultimo[0] }}%; top: {{ $ultimo[1] }}%"></span>
          </div>

          <div class="dash-chart-x" aria-hidden="true">
            @foreach($indicesX as $i)
              <span>{{ $cadastrosPorDia[$i]['data']->format('d/m') }}</span>
            @endforeach
          </div>
        </div>
      </div>

      <div class="dash-card">
        <h2 class="dash-card-title">Usuários online</h2>
        <div class="dash-online-valor">{{ number_format($usuariosOnline, 0, ',', '.') }}</div>
        <p class="dash-online-sub">{{ number_format($percentualOnline, 1, ',', '.') }}% dos {{ number_format($totalUsuarios, 0, ',', '.') }} usuários cadastrados</p>

        <div class="dash-bar" role="img" aria-label="{{ number_format($percentualOnline, 1, ',', '.') }}% dos usuários estão online">
          <i class="verde" style="width: {{ $pctOnline }}%"></i>
        </div>

        <div class="dash-lista">
          <div class="dash-lista-linha">
            <span class="rotulo"><span class="dash-quad verde"></span> Online</span>
            <b>{{ number_format($usuariosOnline, 0, ',', '.') }}</b>
          </div>
          <div class="dash-lista-linha">
            <span class="rotulo"><span class="dash-quad"></span> Offline</span>
            <b>{{ number_format($usuariosOffline, 0, ',', '.') }}</b>
          </div>
        </div>
      </div>
    </div>

    {{-- ===== Cooperativas/ONGs + denúncias por status ===== --}}
    <div class="dash-row duo">
      <div class="dash-card">
        <div class="dash-card-head">
          <h2 class="dash-card-title">Cooperativas e ONGs</h2>
          <a class="dash-link" href="{{ url('/instituicao') }}">Gerenciar</a>
        </div>

        <div class="dash-duplo">
          <div>
            <div class="rot"><i style="background:#1f7a43"></i> Ativas</div>
            <div class="num">{{ number_format($instituicoesAtivas, 0, ',', '.') }}</div>
          </div>
          <div>
            <div class="rot"><i style="background:var(--dash-orange)"></i> Suspensas</div>
            <div class="num">{{ number_format($instituicoesInativas, 0, ',', '.') }}</div>
          </div>
        </div>

        <div class="dash-bar" role="img"
          aria-label="{{ $instituicoesAtivas }} instituições ativas e {{ $instituicoesInativas }} suspensas">
          <i class="verde" style="width: {{ $pctAtivas }}%"></i>
          <i class="laranja" style="width: {{ $pctSusp }}%"></i>
        </div>
      </div>

      <div class="dash-card">
        <div class="dash-card-head">
          <h2 class="dash-card-title">Denúncias por status</h2>
          <a class="dash-link" href="{{ url('/denuncia') }}">Ver denúncias</a>
        </div>

        <div class="dash-status">
          <div>
            <div class="dash-status-topo"><span>Encerradas</span><b>{{ number_format($denunciasResolvidas, 0, ',', '.') }}</b></div>
            <div class="dash-bar"><i class="verde" style="width: {{ $pctDen($denunciasResolvidas) }}%"></i></div>
          </div>
          <div>
            <div class="dash-status-topo"><span>Pendentes</span><b>{{ number_format($denunciasPendentes, 0, ',', '.') }}</b></div>
            <div class="dash-bar"><i class="laranja" style="width: {{ $pctDen($denunciasPendentes) }}%"></i></div>
          </div>
          <div>
            <div class="dash-status-topo"><span>Em análise</span><b>{{ number_format($denunciasEmAnalise, 0, ',', '.') }}</b></div>
            <div class="dash-bar"><i class="azul" style="width: {{ $pctDen($denunciasEmAnalise) }}%"></i></div>
          </div>
        </div>
      </div>
    </div>

    {{-- ===== Atividades recentes ===== --}}
    <div class="dash-card dash-atividades">
      <div class="dash-card-head">
        <h2 class="dash-card-title">Atividades recentes</h2>
        @if($verTodasAtividades)
          <a class="dash-link" href="{{ url('/dash') }}">Ver menos ›</a>
        @else
          <a class="dash-link" href="{{ url('/dash?atividades=todas') }}">Ver todas ›</a>
        @endif
      </div>

      @forelse($atividadesRecentes as $atividade)
        <div class="dash-ativ-item">
          <span class="dash-ativ-icone {{ $atividade['tipo'] }}" aria-hidden="true">
            @if($atividade['tipo'] === 'denuncia')
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                <circle cx="12" cy="12" r="9" />
                <line x1="12" y1="7.5" x2="12" y2="12.5" />
                <circle cx="12" cy="16" r="0.6" fill="currentColor" stroke="none" />
              </svg>
            @elseif($atividade['tipo'] === 'instituicao')
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 21c4-2.6 8-5.6 8-10.2A5 5 0 0 0 12 8a5 5 0 0 0-8 2.8C4 15.4 8 18.4 12 21z" />
              </svg>
            @else
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7" />
              </svg>
            @endif
          </span>

          <div class="dash-ativ-texto">
            <strong>{{ $atividade['titulo'] }}</strong>
            <span>{{ $atividade['detalhe'] }}</span>
          </div>

          <div class="dash-ativ-lado">
            @if($atividade['badge'])
              <span class="badge-status {{ $atividade['badge'][1] }}">{{ $atividade['badge'][0] }}</span>
            @endif
            <span class="data">{{ $atividade['data']->format('d/m · H:i') }}</span>
          </div>
        </div>
      @empty
        <div class="dash-vazio">Nenhuma atividade registrada ainda.</div>
      @endforelse
    </div>

  </div>
@endsection