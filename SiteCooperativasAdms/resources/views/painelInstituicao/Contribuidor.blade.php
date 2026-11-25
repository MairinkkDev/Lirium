@extends('layouts.instituicao')

@section('titulo', 'Contribuidores')

@section('content')

<div class="contribuidores-wrap">

    @if(session('sucesso'))
        <div class="alert-ok">{{ session('sucesso') }}</div>
    @endif

    @if(session('erro'))
        <div class="alert-erro">{{ session('erro') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-erro">{{ $errors->first() }}</div>
    @endif

    {{-- CABEÇALHO --}}
    <div class="inst-page-head">
        <div>
            <h1 class="page-title">Contribuidores</h1>
            <p class="page-sub">Veja o total entregue por cada contribuidor e acesse o histórico</p>
        </div>

        <a href="{{ url('/contribuidor/criar') }}" class="btn-mini btn-cadastrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.6" stroke-linecap="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="16"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
            </svg>
            Cadastrar Contribuidor
        </a>
    </div>

    {{-- CARDS DE RESUMO --}}
    <div class="inst-stats">

        <div class="inst-stat">
            <div class="lbl">Total de contribuidores</div>
            <div class="val">
                {{ number_format($totalContribuidores, 0, ',', '.') }}
            </div>
        </div>

        <div class="inst-stat">
            <div class="lbl">Total de materiais recebidos</div>
            <div class="val">
                {{ number_format($totalMaterial, 1, ',', '.') }}
                <small>kg</small>
            </div>
        </div>

        <div class="inst-stat">
            <div class="lbl">Entregas de hoje</div>
            <div class="val">
                {{ number_format($totalHoje, 0, ',', '.') }}
            </div>
        </div>

    </div>

    {{-- FILTROS --}}
    <form action="{{ url('/contribuidor') }}" method="get" class="inst-filters">

        <label class="inst-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round">
                <circle cx="11" cy="11" r="7"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>

            <input
                type="text"
                name="busca"
                placeholder="Buscar por nome ou CPF"
                value="{{ $busca }}"
            >
        </label>

        <button type="submit" class="btn-mini btn-buscar">
            Buscar
        </button>

        <select
            name="material"
            class="inst-select"
            aria-label="Filtrar por material"
            onchange="this.form.submit()"
        >
            <option value="">Todos</option>

            @foreach($materiais as $m)
                <option value="{{ $m }}" {{ $material === $m ? 'selected' : '' }}>
                    {{ $m }}
                </option>
            @endforeach
        </select>

    </form>

    {{-- TABELA DE CONTRIBUIDORES --}}
    <div class="inst-card">

        <div class="inst-table-wrap">

            <table class="inst-table contrib">

                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Entregas</th>
                        <th>Total entregue</th>
                        <th>Última entrega</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($contribuidores as $c)

                        <tr>
                            <td>{{ $c['nome'] }}</td>
                            <td>{{ $c['cpf'] }}</td>
                            <td>{{ $c['telefone'] }}</td>

                            <td>
                                <span class="chip-material">
                                    {{ $c['entregas'] }}
                                </span>
                            </td>

                            <td>
                                {{ number_format($c['total'], 1, ',', '.') }} kg
                            </td>

                            <td>{{ $c['ultima'] }}</td>

                            <td>
                                <div class="acoes-linha">

                                    {{-- VISUALIZAR HISTÓRICO --}}
                                    <a
                                        href="{{ url('/contribuidor/' . $c['id'] . '/historico') }}"
                                        class="btn-acao btn-visualizar"
                                        title="Visualizar histórico"
                                        aria-label="Visualizar histórico"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2.4"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <span class="btn-txt">Visualizar</span>
                                    </a>
                                    {{-- EXCLUIR (última entrega) --}}
                                    <button
                                        type="button"
                                        class="btn-acao btn-excluir"
                                        data-del
                                        data-id="{{ $c['ultima_id'] }}"
                                        data-nome="{{ $c['nome'] }}"
                                        data-material="{{ $c['ultima_material'] }}"
                                        data-qtd="{{ number_format($c['ultima_kg'], 1, ',', '.') }}"
                                        data-data="{{ $c['ultima'] }}"
                                        title="Excluir a última entrega registrada"
                                        aria-label="Excluir a última entrega registrada"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2.4"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        </svg>
                                        <span class="btn-txt">Excluir</span>
                                    </button>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="inst-empty">
                                @if($busca || $material)
                                    Nenhum contribuidor encontrado com esse filtro.
                                @else
                                    Nenhum contribuidor cadastrado. Use “Registrar entrega” para começar.
                                @endif
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- MODAL: CONFIRMAR EXCLUSÃO --}}
<dialog id="dlgDel">

    <form id="formDel" method="POST" action="">
        @csrf
        @method('DELETE')

        <h3>Excluir entrega</h3>

        <p class="dlg-info" id="infoDel"></p>

        <p class="dlg-nota">
            A quantidade será descontada do estoque. Essa ação não pode ser desfeita.
        </p>

        <div class="dlg-actions">
            <button type="button" data-fechar>Cancelar</button>
            <button type="submit" class="btn-perigo">Excluir</button>
        </div>

    </form>

</dialog>

<script>

    const base = "{{ url('/contribuidor') }}";

    // EXCLUIR ÚLTIMA ENTREGA

    document.querySelectorAll('[data-del]').forEach(b =>
        b.addEventListener('click', () => {

            document.getElementById('formDel').action =
                base + '/' + b.dataset.id;

            document.getElementById('infoDel').textContent =
                'Excluir a última entrega de ' + b.dataset.nome +
                ' (' + b.dataset.material + ', ' + b.dataset.qtd +
                ' kg, em ' + b.dataset.data + ')?';

            document.getElementById('dlgDel').showModal();

        })
    );

    // FECHAR MODAL

    document.querySelectorAll('[data-fechar]').forEach(b =>
        b.addEventListener('click', () =>
            b.closest('dialog').close()
        )
    );

</script>

<style>

    /* ESTRUTURA GERAL */

    .contribuidores-wrap {
        display: flex;
        flex-direction: column;
        gap: 18px;
        width: 100%;
        min-width: 0;
        margin: 0 auto;
        padding: 0;
        box-sizing: border-box;
    }

    .contribuidores-wrap * {
        box-sizing: border-box;
    }

    /* ALERTAS */

    .alert-ok,
    .alert-erro {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .alert-ok {
        background: #dff5e3;
        color: #1b5e2b;
    }

    .alert-erro {
        background: #fde2e2;
        color: #9b1c1c;
    }

    /* CABEÇALHO */

    .contribuidores-wrap .inst-page-head {
        margin-bottom: 0;
    }

    /* CARDS DE RESUMO */

    .contribuidores-wrap .inst-stats {
        display: flex;
        justify-content: center;
        align-items: stretch;
        gap: 18px;
        margin: 10px auto;
        width: 100%;
    }

    .contribuidores-wrap .inst-stat {
        flex: 0 1 280px;
        width: 280px;
        text-align: center;
        padding: 16px 20px;
        box-shadow: none;
        border: 1px solid #d0d0d0;
        border-radius: 8px;
    }

    .contribuidores-wrap .inst-stat .lbl {
        font-size: 12px;
    }

    .contribuidores-wrap .inst-stat .val {
        font-size: 30px;
    }

    /* FILTROS */

    .contribuidores-wrap .inst-filters {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        width: 100%;
    }

    .contribuidores-wrap .btn-cadastrar {
    padding: 7px 12px;
    font-size: 14px;
    gap: 9px;
    border-radius: 40px;
}

    /* BOTÃO DE FILTRO (igual ao de Eventos) */

    .contribuidores-wrap .inst-select {
        min-width: 145px;
        width: auto;
        padding: 11px 35px 11px 14px;
        font-size: 14px;
        border: 1px solid #d0d0d0;
        border-radius: 8px;
        background-color: #fff;
        color: #333;
        cursor: pointer;
    }

    /* CAMPO DE BUSCA */

    .contribuidores-wrap .inst-search {
        flex: 0 1 380px;
        width: 380px;
        min-width: 180px;
    }

    .contribuidores-wrap .inst-search input {
        width: 100%;
        font-size: 14px;
        padding: 5px 12px;
    }

    /* BOTÃO BUSCAR */

    .contribuidores-wrap .btn-buscar {
        padding: 11px 22px;
        font-size: 14px;
        border: 0;
        border-radius: 40px;
        background: #2e9e3f;
        color: white;
        cursor: pointer;
    }

    .contribuidores-wrap .btn-buscar:hover {
        background: #247d32;
    }

    /* TABELA */

    .contribuidores-wrap .inst-card {
        box-shadow: none;
        border: 1px solid #d0d0d0;
        border-radius: 8px;
        margin: 0;
        overflow: hidden;
        container-type: inline-size;
        container-name: tabela;
        width: 100%;
        min-width: 0;
    }

    /* SEM BARRA DE ROLAGEM */

    .contribuidores-wrap .inst-table-wrap {
        display: block;
        width: 100%;
        max-width: 100%;
        overflow: visible !important;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .contribuidores-wrap .inst-table-wrap::-webkit-scrollbar {
        display: none !important;
        width: 0;
        height: 0;
    }

    .contribuidores-wrap .inst-table {
        width: 100% !important;
        min-width: 100% !important;
        table-layout: fixed;
        border-collapse: collapse;
        border-spacing: 0;
        margin: 0;
    }

    /* TÍTULOS DAS COLUNAS */

    .contribuidores-wrap .inst-table th {
        font-size: clamp(10px, 1.3cqw, 16px);
        font-weight: 700;
        padding: 16px 6px;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* DADOS DA TABELA */

    .contribuidores-wrap .inst-table td {
        font-size: clamp(10px, 1.25cqw, 15px);
        font-weight: 500;
        padding: 14px 6px;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* LARGURA DAS COLUNAS */

    .contribuidores-wrap .inst-table th:nth-child(1),
    .contribuidores-wrap .inst-table td:nth-child(1) {
        width: 14%;
        text-align: left;
        padding-left: 14px;
    }

    .contribuidores-wrap .inst-table th:nth-child(2),
    .contribuidores-wrap .inst-table td:nth-child(2) { width: 12%; }

    .contribuidores-wrap .inst-table th:nth-child(3),
    .contribuidores-wrap .inst-table td:nth-child(3) { width: 13%; }

    .contribuidores-wrap .inst-table th:nth-child(4),
    .contribuidores-wrap .inst-table td:nth-child(4) { width: 8%; }

    .contribuidores-wrap .inst-table th:nth-child(5),
    .contribuidores-wrap .inst-table td:nth-child(5) { width: 12%; }

    .contribuidores-wrap .inst-table th:nth-child(6),
    .contribuidores-wrap .inst-table td:nth-child(6) { width: 12%; }

    .contribuidores-wrap .inst-table th:nth-child(7),
    .contribuidores-wrap .inst-table td:nth-child(7) {
        width: 29%;
        padding-left: 6px;
        padding-right: 6px;
        overflow: visible;
        white-space: nowrap;
    }

    .chip-material {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        white-space: nowrap;
        font-size: clamp(10px, 1.25cqw, 15px);
        vertical-align: middle;
    }

    /* BOTÃO VISUALIZAR */

    .acoes-linha {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: center;
        flex-wrap: nowrap;
        white-space: nowrap;
        width: 100%;
        min-width: 0;
    }

    .btn-acao {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        border-radius: 6px;
        padding: 7px 10px;
        font-size: clamp(11px, 1.1cqw, 13px);
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        flex: 0 1 auto;
        min-width: 0;
        text-decoration: none;
        background: #fff;
    }

    .btn-acao svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }

    .btn-visualizar {
        border: 1px solid #2e9e3f;
        color: #2e9e3f;
    }

    .btn-visualizar:hover {
        background: #2e9e3f;
        color: #fff;
    }

    .btn-excluir {
        border: 1px solid #c62828;
        color: #c62828;
    }

    .btn-excluir:hover {
        background: #c62828;
        color: #fff;
    }

    /* MODAL */

    dialog {
        border: 0;
        border-radius: 12px;
        padding: 24px;
        width: 360px;
        max-width: calc(100% - 30px);

    }

    dialog::backdrop {
        background: rgba(0,0,0,.45);
    }

    dialog h3 {
        margin: 0 0 14px;
        color: #0f3d24;
    }

    .dlg-info {
        margin: 0 0 6px;
        font-size: 14px;
        color: #222;
    }

    .dlg-nota {
        margin: 10px 0 0;
        font-size: 13px;
        color: #666;
    }

    .dlg-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .dlg-actions button {
        padding: 9px 18px;
        border: 0;
        border-radius: 8px;
        cursor: pointer;
        background: #e5e5e5;
    }

    .dlg-actions button.btn-perigo {
        background: #c62828;
        color: #fff;
    }

    /* RESPONSIVIDADE */

    @media (max-width: 900px) {

        .contribuidores-wrap .inst-stats {
            flex-wrap: wrap;
        }

        .contribuidores-wrap .inst-filters {
            flex-wrap: wrap;
        }

        .contribuidores-wrap .inst-search {
            flex: 1 1 250px;
            width: auto;
        }

    }

    @media (max-width: 600px) {

        .contribuidores-wrap .inst-stat {
            flex: 1 1 100%;
            width: 100%;
        }

        .contribuidores-wrap .inst-filters .inst-select {
            width: 100%;
        }

    }

    /* AJUSTE PELA LARGURA DA TABELA: tudo em uma linha, sem rolagem.
       As fontes acompanham a largura (cqw), então o texto sempre cabe. */

    @container tabela (max-width: 1120px) {

        .contribuidores-wrap .btn-txt {
            display: none;
        }

        .contribuidores-wrap .acoes-linha {
            gap: 5px;
        }

        .contribuidores-wrap .acoes-linha .btn-acao {
            padding: 8px;
        }

        .contribuidores-wrap .acoes-linha .btn-acao svg {
            width: 15px;
            height: 15px;
        }

        .contribuidores-wrap .inst-table th:nth-child(1),
        .contribuidores-wrap .inst-table td:nth-child(1) { width: 20%; }

        .contribuidores-wrap .inst-table th:nth-child(2),
        .contribuidores-wrap .inst-table td:nth-child(2) { width: 14%; }

        .contribuidores-wrap .inst-table th:nth-child(3),
        .contribuidores-wrap .inst-table td:nth-child(3) { width: 15%; }

        .contribuidores-wrap .inst-table th:nth-child(4),
        .contribuidores-wrap .inst-table td:nth-child(4) { width: 9%; }

        .contribuidores-wrap .inst-table th:nth-child(5),
        .contribuidores-wrap .inst-table td:nth-child(5) { width: 13%; }

        .contribuidores-wrap .inst-table th:nth-child(6),
        .contribuidores-wrap .inst-table td:nth-child(6) { width: 13%; }

        .contribuidores-wrap .inst-table th:nth-child(7),
        .contribuidores-wrap .inst-table td:nth-child(7) { width: 16%; }

    }

    @container tabela (max-width: 620px) {

        .contribuidores-wrap .inst-table th,
        .contribuidores-wrap .inst-table td {
            padding-left: 2px;
            padding-right: 2px;
        }

        .contribuidores-wrap .chip-material {
            padding: 3px 6px;
        }

        .contribuidores-wrap .acoes-linha {
            gap: 3px;
        }

        .contribuidores-wrap .acoes-linha .btn-acao {
            padding: 5px;
        }

        .contribuidores-wrap .acoes-linha .btn-acao svg {
            width: 12px;
            height: 12px;
        }

    }

</style>

@endsection