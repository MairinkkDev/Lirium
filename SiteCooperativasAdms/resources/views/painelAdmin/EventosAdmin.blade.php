@extends('layouts.admin')

@section('titulo', 'Gerenciar Eventos')

@push('estilos')
<style>
    .admin-eventos {
        width: 100%;
    }

    .admin-eventos .inst-page-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 25px;
    }

    .admin-eventos .page-title {
        margin: 0;
        font-size: 27px;
        font-weight: 750;
    }

    .admin-eventos .page-sub {
        margin: 7px 0 0;
        color: #68756c;
        font-size: 14px;
    }

    .admin-eventos .inst-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        border-bottom: 1px solid #dce4dc;
        margin-bottom: 20px;
    }

    .admin-eventos .inst-tab {
        padding: 11px 15px;
        color: #657268;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: .2s;
    }

    .admin-eventos .inst-tab:hover {
        color: #176b3b;
    }

    .admin-eventos .inst-tab.active {
        color: #176b3b;
        border-bottom-color: #176b3b;
    }

    .admin-eventos .inst-filters {
        display: flex;
        flex-wrap: wrap;
        align-items: end;
        gap: 12px;
        margin-top: 20px;
    }

    .admin-eventos .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        min-width: 145px;
    }

    .admin-eventos .filter-group label {
        font-size: 12px;
        font-weight: 700;
        color: #526056;
    }

    .admin-eventos .filter-group input,
    .admin-eventos .filter-group select {
        width: 100%;
        min-height: 42px;
        padding: 9px 11px;
        border: 1px solid #d5ded6;
        border-radius: 7px;
        background: #fff;
        color: #26352a;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .admin-eventos .filter-group input:focus,
    .admin-eventos .filter-group select:focus {
        border-color: #258447;
        box-shadow: 0 0 0 3px rgba(37, 132, 71, .10);
    }

    .admin-eventos .filter-actions {
        display: flex;
        gap: 8px;
    }

    .admin-eventos .btn-filtrar {
        min-height: 42px;
        padding: 0 17px;
        border: none;
        border-radius: 7px;
        background: #176b3b;
        color: #fff;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .admin-eventos .btn-limpar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 14px;
        border: 1px solid #d5ded6;
        border-radius: 7px;
        color: #536157;
        font-size: 13px;
        text-decoration: none;
    }

    .admin-eventos .inst-card {
        margin-top: 20px;
        overflow: hidden;
        border: 1px solid #e0e7e0;
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
    }

    .admin-eventos .inst-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .admin-eventos .inst-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        white-space: nowrap;
    }

    .admin-eventos .inst-table th {
        padding: 15px 16px;
        background: #f3f7f3;
        color: #526056;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .admin-eventos .inst-table td {
        padding: 15px 16px;
        border-top: 1px solid #edf1ed;
        color: #35443a;
        font-size: 13px;
    }

    .admin-eventos .inst-table tbody tr:hover {
        background: #fafcf9;
    }

    .admin-eventos .evento-nome {
        color: #203b29;
        font-weight: 700;
    }

    .admin-eventos .instituicao-nome {
        color: #59695e;
        font-weight: 600;
    }

    .admin-eventos .chip-status {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 750;
    }

    .admin-eventos .chip-status.agendado {
        background: #e7f0ff;
        color: #285b9b;
    }

    .admin-eventos .chip-status.andamento {
        background: #e2f5e8;
        color: #19733c;
    }

    .admin-eventos .chip-status.encerrado {
        background: #edf0ed;
        color: #59635b;
    }

    .admin-eventos .chip-status.cancelado {
        background: #ffebeb;
        color: #b83b3b;
    }

    .admin-eventos .chip-status.pendente {
        background: #fff4d9;
        color: #9a6900;
    }

    .admin-eventos .acoes-evento {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .admin-eventos .btn-detalhe {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 11px;
        border: 1px solid #cbdccc;
        border-radius: 7px;
        background: #f5faf5;
        color: #176b3b;
        font-family: inherit;
        font-size: 12px;
        font-weight: 750;
        text-decoration: none;
        transition: .2s;
    }

    .admin-eventos .btn-detalhe:hover {
        background: #176b3b;
        color: #fff;
        border-color: #176b3b;
    }

    .admin-eventos .btn-detalhe svg {
        width: 15px;
        height: 15px;
    }

    .admin-eventos .btn-acao {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 7px;
        background: #fff;
        cursor: pointer;
        transition: .2s;
    }

    .admin-eventos .btn-acao svg {
        width: 18px;
        height: 18px;
    }

    .admin-eventos .btn-aprovar {
        border: 1px solid #b9dfc3;
        color: #218044;
    }

    .admin-eventos .btn-aprovar:hover {
        background: #218044;
        color: #fff;
        border-color: #218044;
    }

    .admin-eventos .btn-recusar {
        border: 1px solid #f0c4c4;
        color: #c43d3d;
    }

    .admin-eventos .btn-recusar:hover {
        background: #c43d3d;
        color: #fff;
        border-color: #c43d3d;
    }

    .admin-eventos .inst-empty {
        padding: 35px !important;
        text-align: center;
        color: #7b887e !important;
    }

    .admin-eventos .paginacao {
        margin-top: 18px;
    }
        .admin-eventos .chip-status.recusado {
        background: #ffebeb;
        color: #b83b3b;
    }

    /* Mensagem de sucesso */
    .admin-eventos .alerta-sucesso {
        margin-bottom: 18px;
        padding: 13px 16px;
        border: 1px solid #b9dfc3;
        border-radius: 8px;
        background: #e8f6ec;
        color: #19733c;
        font-size: 14px;
        font-weight: 600;
    }

    /* Modal de confirmação */
    .modal-confirmacao {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(20, 30, 24, .55);
    }

    .modal-confirmacao.aberto {
        display: flex;
    }

    .modal-confirmacao .modal-caixa {
        width: 100%;
        max-width: 420px;
        padding: 28px 26px 22px;
        border-radius: 14px;
        background: #fff;
        text-align: center;
        box-shadow: 0 18px 50px rgba(0, 0, 0, .25);
        animation: modalEntrada .18s ease-out;
    }

    @keyframes modalEntrada {
        from { opacity: 0; transform: translateY(10px) scale(.97); }
        to   { opacity: 1; transform: none; }
    }

    .modal-confirmacao .modal-icone {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        border-radius: 50%;
    }

    .modal-confirmacao .modal-icone svg {
        width: 30px;
        height: 30px;
    }

    .modal-confirmacao.tipo-aprovar .modal-icone { background: #e2f5e8; color: #218044; }
    .modal-confirmacao.tipo-recusar .modal-icone { background: #ffebeb; color: #c43d3d; }

    .modal-confirmacao .modal-titulo {
        margin: 0 0 8px;
        font-size: 19px;
        font-weight: 750;
        color: #203b29;
    }

    .modal-confirmacao .modal-texto {
        margin: 0 0 22px;
        font-size: 14px;
        line-height: 1.55;
        color: #59695e;
    }

    .modal-confirmacao .modal-texto strong {
        color: #26352a;
    }

    .modal-confirmacao .modal-botoes {
        display: flex;
        gap: 10px;
    }

    .modal-confirmacao .modal-botoes > * {
        flex: 1;
    }

    .modal-confirmacao .btn-modal {
        min-height: 44px;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .modal-confirmacao .btn-modal-cancelar {
        border: 1px solid #d5ded6;
        background: #fff;
        color: #536157;
    }

    .modal-confirmacao .btn-modal-cancelar:hover {
        background: #f3f7f3;
    }

    .modal-confirmacao .btn-modal-confirmar {
        border: none;
        color: #fff;
    }

    .modal-confirmacao.tipo-aprovar .btn-modal-confirmar { background: #218044; }
    .modal-confirmacao.tipo-recusar .btn-modal-confirmar { background: #c43d3d; }

    .modal-confirmacao .btn-modal-confirmar:disabled {
        opacity: .6;
        cursor: wait;
    }

    @media (max-width: 700px) {
        .admin-eventos .inst-page-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .admin-eventos .inst-filters {
            align-items: stretch;
        }

        .admin-eventos .filter-group {
            min-width: 100%;
        }

        .admin-eventos .filter-actions {
            width: 100%;
        }

        .admin-eventos .filter-actions>* {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')

@php
$abas = [
'todos' => 'Todos',
'agendado' => 'Agendados',
'em_andamento' => 'Em andamento',
'concluido' => 'Concluídos',
'cancelado' => 'Cancelados',
'pendente' => 'Solicitações',
'recusado' => 'recusado',  
];

$rotulos = [
'agendado' => 'Agendado',
'em_andamento' => 'Em andamento',
'concluido' => 'Concluído',
'cancelado' => 'Cancelado',
'recusado' => 'recusado',  
'pendente' => 'Pendente',
];

$classes = [
'agendado' => 'agendado',
'em_andamento' => 'andamento',
'concluido' => 'encerrado',
'cancelado' => 'cancelado',
'pendente' => 'pendente',
'recusado' => 'recusado',  
];
@endphp

<div class="admin-eventos">

    {{-- Cabeçalho --}}
    <div class="inst-page-head">
        <div>
            <h1 class="page-title">Eventos</h1>
            <p class="page-sub">
                Visualize e gerencie os eventos cadastrados pelas instituições.
            </p>
        </div>
    </div>

    {{-- Filtros por status --}}
    <nav class="inst-tabs" aria-label="Filtrar eventos por status">
        @foreach($abas as $valor => $rotulo)
        <a href="{{ route('admin.eventos.painel', array_merge(
                    request()->except('page', 'status'),
                    ['status' => $valor === 'todos' ? null : $valor]
                )) }}"
            class="inst-tab {{ $status === $valor ? 'active' : '' }}"
            @if($status===$valor) aria-current="page" @endif>
            {{ $rotulo }}
        </a>
        @endforeach
    </nav>

    {{-- Filtros de busca e data --}}
    <form action="{{ route('admin.eventos.painel') }}" method="GET" class="inst-filters">

        @if($status !== 'todos')
        <input type="hidden" name="status" value="{{ $status }}">
        @endif

        <div class="filter-group">
            <label for="busca">Evento ou instituição</label>
            <input
                type="text"
                id="busca"
                name="busca"
                placeholder="Buscar por nome..."
                value="{{ $busca }}">
        </div>

        <div class="filter-group">
            <label for="dataInicio">Data inicial</label>
            <input
                type="date"
                id="dataInicio"
                name="dataInicio"
                value="{{ $dataInicio ?? '' }}">
        </div>

        <div class="filter-group">
            <label for="dataFim">Data final</label>
            <input
                type="date"
                id="dataFim"
                name="dataFim"
                value="{{ $dataFim ?? '' }}">
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-filtrar">
                Filtrar
            </button>

            <a href="{{ route('admin.eventos.painel') }}" class="btn-limpar">
                Limpar
            </a>
        </div>

    </form>

    {{-- Tabela de eventos --}}
    <div class="inst-card">
        <div class="inst-table-wrap">

            <table class="inst-table eventos">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Instituição</th>
                        <th>Início</th>
                        <th>Encerramento</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($eventos as $e)

                    <tr>
                        <td class="evento-nome">
                            {{ $e->nomeEvento }}
                        </td>

                        <td class="instituicao-nome">
                            {{ $e->instituicoes
                                    ->pluck('nomeInstituicao')
                                    ->filter()
                                    ->join(', ') ?: 'Instituição não informada' }}
                        </td>

                        <td>
                            {{ $e->dataInicioEvento?->format('d/m/Y H:i') ?? '-' }}
                        </td>

                        <td>
                            {{ $e->dataFimEvento?->format('d/m/Y H:i') ?? '-' }}
                        </td>

                        <td>
                            <span class="chip-status {{ $classes[$e->statusEvento] ?? 'encerrado' }}">
                                {{ $rotulos[$e->statusEvento] ?? $e->statusEvento }}
                            </span>
                        </td>

                        <td>
                            <div class="acoes-evento">

                                {{-- Ver detalhes --}}
                                <a href="{{ route('admin.eventos.detalhe', $e->idEvento) }}"
                                    class="btn-detalhe"
                                    title="Ver detalhes">

                                    <svg viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>

                                    Detalhes
                                </a>

                                {{-- Ações exclusivas das solicitações --}}
                               @if($e->statusEvento === 'pendente')

    {{-- Aprovar --}}
    <button type="button"
        class="btn-acao btn-aprovar"
        title="Aprovar solicitação"
        aria-label="Aprovar solicitação"
        data-tipo="aprovar"
        data-url="{{ route('admin.eventos.aprovar', $e->idEvento) }}"
        data-evento="{{ $e->nomeEvento }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="m9 12 2 2 4-4" />
            <path d="M12 3a9 9 0 1 0 9 9" />
            <path d="M16 5h5v5" />
        </svg>
    </button>

    {{-- Recusar --}}
    <button type="button"
        class="btn-acao btn-recusar"
        title="Recusar solicitação"
        aria-label="Recusar solicitação"
        data-tipo="recusar"
        data-url="{{ route('admin.eventos.recusar', $e->idEvento) }}"
        data-evento="{{ $e->nomeEvento }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg>
    </button>

@endif

                            </div>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="inst-empty">
                            @if($status === 'pendente')
                            Nenhuma solicitação de evento encontrada.
                            @elseif($busca !== '' || $status !== 'todos' || !empty($dataInicio) || !empty($dataFim))
                            Nenhum evento encontrado com esses filtros.
                            @else
                            Nenhum evento cadastrado no sistema.
                            @endif
                        </td>
                    </tr>

                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{-- Paginação --}}
    @if($eventos->hasPages())
    <div class="paginacao">
        {{ $eventos->links() }}
    </div>
    @endif

</div>

{{-- MODAL DE CONFIRMAÇÃO --}}
<div class="modal-confirmacao" id="modalConfirmacao" role="dialog" aria-modal="true"
    aria-labelledby="modalTitulo" aria-hidden="true">
    <div class="modal-caixa">

        <div class="modal-icone" id="modalIcone"></div>

        <h2 class="modal-titulo" id="modalTitulo"></h2>
        <p class="modal-texto" id="modalTexto"></p>

        <form method="POST" id="modalForm">
            @csrf
            @method('PATCH')

            <div class="modal-botoes">
                <button type="button" class="btn-modal btn-modal-cancelar" id="modalCancelar">
                    Cancelar
                </button>
                <button type="submit" class="btn-modal btn-modal-confirmar" id="modalConfirmar"></button>
            </div>
        </form>

    </div>
</div>

<script>
(function () {
    const modal     = document.getElementById('modalConfirmacao');
    const form      = document.getElementById('modalForm');
    const titulo    = document.getElementById('modalTitulo');
    const texto     = document.getElementById('modalTexto');
    const icone     = document.getElementById('modalIcone');
    const confirmar = document.getElementById('modalConfirmar');

    const iconeAprovar = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
    const iconeRecusar = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';

    function abrir(botao) {
        const tipo   = botao.dataset.tipo;
        const evento = botao.dataset.evento;

        form.action = botao.dataset.url;
        modal.classList.remove('tipo-aprovar', 'tipo-recusar');
        modal.classList.add('tipo-' + tipo);

        texto.textContent = '';
        const nome = document.createElement('strong');
        nome.textContent = evento;

        if (tipo === 'aprovar') {
            titulo.textContent    = 'Aprovar evento?';
            icone.innerHTML       = iconeAprovar;
            confirmar.textContent = 'Sim, aprovar';
            texto.append('O evento ', nome, ' será publicado e ficará visível para os usuários do aplicativo.');
        } else {
            titulo.textContent    = 'Recusar solicitação?';
            icone.innerHTML       = iconeRecusar;
            confirmar.textContent = 'Sim, recusar';
            texto.append('A solicitação do evento ', nome, ' será recusada e não será divulgada.');
        }

        confirmar.disabled = false;
        modal.classList.add('aberto');
        modal.setAttribute('aria-hidden', 'false');
        confirmar.focus();
    }

    function fechar() {
        modal.classList.remove('aberto');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.querySelectorAll('[data-tipo][data-url]').forEach(function (botao) {
        botao.addEventListener('click', function () { abrir(botao); });
    });

    document.getElementById('modalCancelar').addEventListener('click', fechar);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) fechar();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('aberto')) fechar();
    });

    // evita duplo clique
    form.addEventListener('submit', function () {
        confirmar.disabled = true;
        confirmar.textContent = 'Aguarde...';
    });
})();
</script>

@endsection