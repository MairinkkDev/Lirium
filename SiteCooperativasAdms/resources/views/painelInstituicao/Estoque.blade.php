
@extends('layouts.instituicao')

@section('titulo', 'Estoque')

@section('content')

<div class="estoque-wrap">

    @if(session('sucesso'))
        <div class="alert-ok">{{ session('sucesso') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-erro">{{ $errors->first() }}</div>
    @endif

    <div class="page-head" style="margin-bottom:0;">
        <div>
            <h1 class="page-title">Estoque</h1>
            <p class="page-sub">Materiais reciclados e preços</p>
        </div>
    </div>

    @php
        $dadosPizza = collect($materiais)
            ->map(fn ($m) => ['name' => $m['nome'], 'value' => (float) $m['quantidade']])
            ->values();
    @endphp

    {{-- ===== Gráficos ===== --}}
    <div class="graficos-grid">

        {{-- Gráfico de linha menor --}}
        <div class="grafico-card grafico-linha">
            <h2>Material recebido por mês</h2>
            <div id="graficoLinha" class="grafico"></div>
        </div>

        {{-- Gráfico de pizza maior --}}
        <div class="grafico-card grafico-pizza">
            <h2>Quilos por material</h2>
            <div id="graficoPizza" class="grafico"></div>
        </div>

    </div>

    {{-- ===== Cards de resumo (abaixo dos gráficos) ===== --}}
    <div class="inst-stats">

        <div class="inst-stat">
            <div class="lbl">Total em estoque</div>
            <div class="val">{{ number_format($totalEstoque, 0, ',', '.') }}<small>kg</small></div>
        </div>

        <div class="inst-stat">
            <div class="lbl">Tipos de material</div>
            <div class="val">{{ $tiposMaterial }}</div>
        </div>

        <div class="inst-stat">
            <div class="lbl">Entregas recebidas hoje</div>
            <div class="val">{{ $entregasHoje }}</div>
        </div>

    </div>

    <div class="inst-split">

        {{-- ===== Materiais recicláveis ===== --}}
        <section>

            <div class="inst-card">
                <div class="inst-card-head">
                    <h2>Materiais Recicláveis</h2>

                    <div class="inst-card-actions">

                        <button type="button" class="btn-mini" id="btnAddEstoque">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="16" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                            </svg>
                            Adicionar material
                        </button>

                        <button type="button" class="btn-mini" id="btnAltEstoque">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                            </svg>
                            Alterar quantidade do material
                        </button>

                        <button type="button" class="btn-mini" id="btnDelEstoque">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                <path d="M10 11v6M14 11v6" />
                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                            </svg>
                            Excluir
                        </button>

                    </div>
                </div>
            </div>

            <div class="inst-card">
                <div class="inst-table-wrap">
                    <table class="inst-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th class="num">Quantidade Atual</th>
                                <th class="data">Última Atualização</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($materiais as $m)
                                <tr>
                                    <td>{{ $m['nome'] }}</td>
                                    <td class="num">{{ number_format($m['quantidade'], 0, ',', '.') }} kg</td>
                                    <td class="data">{{ $m['atualizacao'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="inst-empty">
                                        Nenhum material cadastrado. Use “Adicionar” para começar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

        {{-- ===== Preço por kg ===== --}}
        <section>

            <div class="inst-card">
                <div class="inst-card-head">
                    <h2>Preço por kg</h2>

                    <div class="inst-card-actions">

                        <button type="button" class="btn-mini" id="btnAddPreco">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="16" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                            </svg>
                            Adicionar valor
                        </button>

                        <button type="button" class="btn-mini" id="btnAltPreco">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                            </svg>
                            Alterar preço do material
                        </button>

                        <button type="button" class="btn-mini" id="btnDelPreco">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                <path d="M10 11v6M14 11v6" />
                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                            </svg>
                            Excluir
                        </button>

                    </div>
                </div>
            </div>

            <div class="inst-card">
                <div class="inst-table-wrap">
                    <table class="inst-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th class="num">Preço por kg</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($listaPrecos as $p)
                                <tr>
                                    <td>{{ $p['nome'] }}</td>
                                    <td class="num">R$ {{ number_format($p['preco'], 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="inst-empty">Nenhum preço cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

    </div>

</div>

{{-- Sugestões de nomes (o usuário também pode digitar um material novo) --}}
<datalist id="listaMateriais">
    @foreach($catalogo as $c)
        <option value="{{ $c->nomeMaterialReciclavel }}"></option>
    @endforeach
</datalist>

{{-- ===== MODAIS: ESTOQUE ===== --}}

<dialog id="dlgAddEstoque">
    <form method="POST" action="{{ route('estoque.adicionar') }}">
        @csrf

        <h3>Adicionar material</h3>

        <label>Material</label>
        <input type="text" name="nomeMaterial" list="listaMateriais" maxlength="100"
               placeholder="Digite ou escolha um material" value="{{ old('nomeMaterial') }}" required>

        <label>Quantidade inicial (kg)</label>
        <input type="number" name="quantidadeKg" step="0.01" min="0" value="0" required>

        <div class="dlg-actions">
            <button type="button" data-fechar>Cancelar</button>
            <button type="submit">Salvar</button>
        </div>
    </form>
</dialog>

<dialog id="dlgAltEstoque">
    <form method="POST" action="{{ route('estoque.alterar') }}">
        @csrf
        @method('PATCH')

        <h3>Alterar quantidade</h3>

        <label>Material</label>
        <select name="idMaterialReciclavel" required>
            @foreach($materiais as $m)
                <option value="{{ $m['id'] }}">{{ $m['nome'] }}</option>
            @endforeach
        </select>

        <label>Nova quantidade (kg)</label>
        <input type="number" name="quantidadeKg" step="0.01" min="0" required>

        <div class="dlg-actions">
            <button type="button" data-fechar>Cancelar</button>
            <button type="submit">Salvar</button>
        </div>
    </form>
</dialog>

<dialog id="dlgDelEstoque">
    <form method="POST" action="{{ route('estoque.excluir') }}"
          onsubmit="return confirm('Excluir este material do estoque? O preço dele também será removido.')">
        @csrf
        @method('DELETE')

        <h3>Excluir material</h3>

        <label>Material</label>
        <select name="idMaterialReciclavel" required>
            @foreach($materiais as $m)
                <option value="{{ $m['id'] }}">{{ $m['nome'] }}</option>
            @endforeach
        </select>

        <div class="dlg-actions">
            <button type="button" data-fechar>Cancelar</button>
            <button type="submit" class="btn-perigo">Excluir</button>
        </div>
    </form>
</dialog>

{{-- ===== MODAIS: PREÇO ===== --}}

<dialog id="dlgAddPreco">
    <form method="POST" action="{{ route('estoque.preco.adicionar') }}">
        @csrf

        <h3>Adicionar preço</h3>

        <label>Material</label>
        <input type="text" name="nomeMaterial" list="listaMateriais" maxlength="100"
               placeholder="Digite ou escolha um material" value="{{ old('nomeMaterial') }}" required>

        <label>Preço (R$ por kg)</label>
        <input type="number" name="precoKg" step="0.01" min="0" required>

        <div class="dlg-actions">
            <button type="button" data-fechar>Cancelar</button>
            <button type="submit">Salvar</button>
        </div>
    </form>
</dialog>

<dialog id="dlgAltPreco">
    <form method="POST" action="{{ route('estoque.preco.alterar') }}">
        @csrf
        @method('PUT')

        <h3>Alterar preço</h3>

        <label>Material</label>
        <select name="idMaterialReciclavel" required>
            @foreach($listaPrecos as $p)
                <option value="{{ $p['id'] }}">{{ $p['nome'] }}</option>
            @endforeach
        </select>

        <label>Novo preço (R$ por kg)</label>
        <input type="number" name="precoKg" step="0.01" min="0" required>

        <div class="dlg-actions">
            <button type="button" data-fechar>Cancelar</button>
            <button type="submit">Salvar</button>
        </div>
    </form>
</dialog>

<dialog id="dlgDelPreco">
    <form method="POST" action="{{ route('estoque.preco.excluir') }}"
          onsubmit="return confirm('Excluir o preço deste material?')">
        @csrf
        @method('DELETE')

        <h3>Excluir preço</h3>

        <label>Material</label>
        <select name="idMaterialReciclavel" required>
            @foreach($listaPrecos as $p)
                <option value="{{ $p['id'] }}">{{ $p['nome'] }}</option>
            @endforeach
        </select>

        <div class="dlg-actions">
            <button type="button" data-fechar>Cancelar</button>
            <button type="submit" class="btn-perigo">Excluir</button>
        </div>
    </form>
</dialog>

<style>

    .alert-ok, .alert-erro {
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 14px;
        font-size: 13px;
    }

    .alert-ok {
        background: #dff5e3;
        color: #1b5e2b;
    }

    .alert-erro {
        background: #fde2e2;
        color: #9b1c1c;
    }

    dialog {
        border: 0;
        border-radius: 12px;
        padding: 22px;
        width: 340px;
        box-shadow: 0 10px 40px rgba(0,0,0,.25);
    }

    dialog::backdrop {
        background: rgba(0,0,0,.45);
    }

    dialog h3 {
        margin: 0 0 14px;
        color: #0f3d24;
    }

    dialog label {
        display: block;
        font-size: 12px;
        margin: 10px 0 4px;
        color: #444;
    }

    dialog select, dialog input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
    }

    .dlg-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 18px;
    }

    .dlg-actions button {
        padding: 8px 16px;
        border: 0;
        border-radius: 8px;
        cursor: pointer;
        background: #e5e5e5;
    }

    .dlg-actions button[type=submit] {
        background: #2e9e3f;
        color: #fff;
    }

    .dlg-actions button.btn-perigo {
        background: #c62828;
        color: #fff;
    }

    /* ===== Layout do estoque ===== */

    html, body {
        height: 100%;
        overflow: hidden;
    }

    .app {
        height: 100vh;
        min-height: 0;
        grid-template-rows: auto minmax(0, 1fr);
    }

    /* Só a área de conteúdo rola, sem barra visível */

    .main {
        max-width: none;
        min-height: 0;
        padding: 16px 24px;
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .main::-webkit-scrollbar {
        display: none;
        width: 0;
        height: 0;
    }

    .estoque-wrap {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 0 0 55vh;
        display: flex;
        flex-direction: column;
        gap: 45px;
        border: 0;
        background: transparent;
    }

    .estoque-wrap .graficos-grid {
    margin-top: -30px;
}

    .estoque-wrap .alert-ok,
    .estoque-wrap .alert-erro {
        margin: 0;
    }

    /* Título no canto superior esquerdo */

    .estoque-wrap .page-head {
        justify-content: flex-start;
        text-align: left;
        margin: 0 !important;
    }

    .estoque-wrap .page-title {
        font-size: 26px;
        margin: 0;
    }

    .estoque-wrap .page-sub {
        font-size: 14px;
    }

    /* Cards de resumo no centro, abaixo dos gráficos */

    .estoque-wrap .inst-stats {
        display: flex !important;
        justify-content: center !important;
        align-items: stretch;
        gap: 14px;
        margin: 8px auto !important;
        width: 100%;
    }

    .estoque-wrap .inst-stat {
        flex: 0 0 240px;
        width: 240px;
        text-align: center;
        padding: 10px 16px;
        box-shadow: none;
        border: 1px solid #d0d0d0;
    }

    .estoque-wrap .inst-stat .lbl {
        font-size: 13px;
    }

    .estoque-wrap .inst-stat .val {
        font-size: 28px;
    }

  /* ===== Gráficos ===== */

.graficos-grid {
    display: grid;
    grid-template-columns: 0.9fr 1.1fr;
    gap: -10px;
    height: 340px;
    align-items: stretch;
    margin: 0;
    padding: 0;
}

.grafico-card {
    min-width: 0;
    min-height: 0;
    height: 340px;
    display: flex;
    flex-direction: column;
    border: 0;
    padding: 0;
    margin: 0;
    background: transparent;
}

.grafico-linha h2 {
    font-size: 16px;
    position: relative;
    top: 20px;
    left: -5px;
    text-align: center;
}

.grafico-pizza h2 {
    font-size: 16px;
    position: relative;
    top: -5px;
    left: -5px;
    text-align: center;
}

.grafico {
    width: 100%;
    height: 310px;
    min-height: 310px;
    flex: none;
}

.grafico-pizza {
    transform: translate(0, 76px);
}

@media (max-width: 900px) {
    .graficos-grid,
    .estoque-wrap .inst-split {
        grid-template-columns: 1fr;
    }

    .graficos-grid {
        height: auto;
    }

    .grafico-card {
        height: 320px;
    }

    .grafico {
        height: 290px;
        min-height: 290px;
    }

    .grafico-pizza {
        transform: translateY(30px);
    }
}
    /* Tabelas com altura natural */

    .estoque-wrap .inst-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin: 0;
        align-items: start;
    }

    .estoque-wrap .inst-split > section {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
        /* Em telas menores as duas colunas não cabem (botões longos): empilha as tabelas */
    @media (max-width: 1540px) {
        .estoque-wrap .inst-split {
            grid-template-columns: minmax(0, 1fr);
        }

        .estoque-wrap .inst-split > section {
            min-width: 0;
        }
    }

    .estoque-wrap .inst-card {
        box-shadow: none;
        border: 1px solid #d0d0d0;
        margin: 0;
    }

    .estoque-wrap .inst-card-head h2 {
        font-size: 17px;
    }

    .estoque-wrap .inst-table th,
    .estoque-wrap .inst-table td {
        font-size: 15px;
        padding: 11px 18px;
      }

    /* Botões */

   .estoque-wrap .btn-mini {
    padding: 9px 14px;
    font-size: 13px;
    gap: 7px;
    border-radius: 15px;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.estoque-wrap .btn-mini svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

.estoque-wrap .inst-card-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;
}

@media (max-width: 1100px) {
    .estoque-wrap .inst-card-head {
        flex-wrap: wrap;
        gap: 10px;
    }

    .estoque-wrap .inst-card-actions {
        flex-wrap: wrap;
    }
}

    dialog {
        box-shadow: none;
        border: 1px solid #d0d0d0;
    }


</style>

<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>

<script>

    const dadosPizza = @json($dadosPizza);
    const mesesLabel = @json($mesesLabel);
    const mesesValores = @json($mesesValores);

    // ===== Gráfico de linha =====

    const linha = echarts.init(document.getElementById('graficoLinha'));

    linha.setOption({

        tooltip: {
            trigger: 'axis',
            valueFormatter: v => v + ' kg'
        },

        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },


        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: mesesLabel
        },

        yAxis: {
            type: 'value',
            name: 'kg'
        },

        series: [{

            name: 'Material recebido',
            type: 'line',
            smooth: true,
            data: mesesValores,

            itemStyle: {
                color: '#2e9e3f'
            },

            areaStyle: {
                opacity: 0.12
            }

        }]

    });

    // ===== Gráfico de pizza =====

    const pizza = echarts.init(document.getElementById('graficoPizza'));

    pizza.setOption({

        tooltip: {
            trigger: 'item',
            formatter: '{b}: {c} kg ({d}%)'
        },

        legend: { 
            top:'66%',
            left: 'center'
        },

        series: [{

            name: 'Quilos por material',
            type: 'pie',
            radius: ['70%', '110%'],

            center: ['50%', '60%'],

            startAngle: 180,
            endAngle: 360,

            label: {
                formatter: '{b}\n{c} kg'
            },

            data: dadosPizza

        }]

    });

    // Ajusta os gráficos quando a tela muda de tamanho

    const ajustar = () => {
        pizza.resize();
        linha.resize();
    };

    window.addEventListener('resize', ajustar);
    window.addEventListener('load', ajustar);

</script>

<script>

    const abrir = (btn, dlg) =>
        document.getElementById(btn)?.addEventListener('click', () =>
            document.getElementById(dlg).showModal()
        );

    abrir('btnAddEstoque', 'dlgAddEstoque');
    abrir('btnAltEstoque', 'dlgAltEstoque');
    abrir('btnDelEstoque', 'dlgDelEstoque');

    abrir('btnAddPreco', 'dlgAddPreco');
    abrir('btnAltPreco', 'dlgAltPreco');
    abrir('btnDelPreco', 'dlgDelPreco');

    document.querySelectorAll('[data-fechar]').forEach(b =>
        b.addEventListener('click', () =>
            b.closest('dialog').close()
        )
    );

</script>

@endsection
