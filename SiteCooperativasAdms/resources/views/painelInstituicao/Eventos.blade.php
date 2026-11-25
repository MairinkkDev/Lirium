@extends('layouts.instituicao')

@section('titulo', 'Eventos')

@section('content')

@php
    $abas = [
        'todos'        => 'Todas',
        'pendente'     => 'Pendentes',
        'em_andamento' => 'Em Andamento',
        'agendado'     => 'Agendados',
        'concluido'    => 'Encerrados',
        'recusado'     => 'Recusados',
    ];

    $rotulos = [
        'pendente'     => 'Pendente',
        'em_andamento' => 'Em andamento',
        'agendado'     => 'Agendado',
        'concluido'    => 'Encerrado',
        'cancelado'    => 'Cancelado',
        'recusado'     => 'Recusado',
    ];

    $classes = [
        'pendente'     => 'pendente',
        'em_andamento' => 'andamento',
        'agendado'     => 'agendado',
        'concluido'    => 'encerrado',
        'cancelado'    => 'encerrado',
        'recusado'     => 'recusado',
    ];
@endphp

<div class="eventos-wrap">

    {{-- CABEÇALHO --}}
    <div class="inst-page-head">

        <div>
            <h1 class="page-title">Eventos</h1>
            <p class="page-sub">Crie, altere e encerre os eventos da sua instituição</p>
        </div>

        <a href="{{ route('evento.criar.form') }}" class="btn-mini btn-cadastrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.6" stroke-linecap="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="16"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
            </svg>
            Criar evento
        </a>

    </div>

    {{-- FILTROS --}}
    <form
        action="{{ route('evento.painel') }}"
        method="GET"
        class="inst-filters"
    >


        {{-- CAMPO DE BUSCA --}}
        <label class="inst-search">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round">
                <circle cx="11" cy="11" r="7"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>

            <input
                type="text"
                name="busca"
                placeholder="Buscar evento por nome"
                value="{{ $busca }}"
            >

        </label>

        {{-- BOTÃO BUSCAR --}}
        <button type="submit" class="btn-mini btn-buscar">
            Buscar
        </button>

        <select
            name="status"
            class="inst-select"
            aria-label="Filtrar eventos por status"
            onchange="this.form.submit()"
        >

            @foreach($abas as $valor => $rotulo)
                <option
                    value="{{ $valor }}"
                    {{ $status === $valor ? 'selected' : '' }}
                >
                    {{ $rotulo }}
                </option>
            @endforeach

        </select>

    </form>

    {{-- TABELA DE EVENTOS --}}
    <div class="inst-card">

        <div class="inst-table-wrap">

            <table class="inst-table eventos">

                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Início</th>
                        <th>Encerramento</th>
                        <th>Status</th>
                        <th>Nota</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($eventos as $e)

                        <tr>

                            <td>{{ $e->nomeEvento }}</td>

                            <td>
                                {{ $e->dataInicioEvento?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td>
                                {{ $e->dataFimEvento?->format('d/m/Y') ?? '-' }}
                            </td>

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

@elseif(in_array($e->statusEvento, ['cancelado', 'recusado']))

    <td></td>
    <td>-</td>

@elseif($e->statusEvento === 'pendente')

    {{-- aguardando admin: pode alterar, mas não encerrar --}}
    <td></td>
    <td>
        <a href="{{ route('evento.detalhe', $e->idEvento) }}" class="btn-alterar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>
            </svg>
            Alterar
        </a>
    </td>

@else {{-- agendado e em_andamento --}}

    {{-- mantenha aqui os dois <td> que você já tem: Alterar + Encerrar --}}


                                <td>
                                    <a
                                        href="{{ route('evento.detalhe', $e->idEvento) }}"
                                        class="btn-alterar"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2.4"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M12 20h9"/>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>
                                        </svg>
                                        Alterar
                                    </a>
                                </td>

                                <td>
                                    <form
                                        action="{{ route('evento.encerrar', $e->idEvento) }}"
                                        method="POST"
                                        onsubmit="return confirm('Encerrar este evento?');"
                                        style="display:inline;"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn-alterar btn-encerrar">
                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2.4"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <rect x="5" y="5" width="14" height="14" rx="2"/>
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

    {{-- PAGINAÇÃO --}}
    @if($eventos->hasPages())

        <div style="margin-top:16px;">
            {{ $eventos->links() }}
        </div>

    @endif

</div>

<style>

/* ESTRUTURA GERAL */

.eventos-wrap {
    display: flex;
    flex-direction: column;
    gap: 18px;
    width: 100%;
    min-width: 0;
    margin: 0 auto;
    padding: 0;
    box-sizing: border-box;
}

.eventos-wrap .chip-status.pendente { background: #fff4d6; color: #8a6100; }
.eventos-wrap .chip-status.recusado { background: #fde4e4; color: #c62828; }

.eventos-wrap * {
    box-sizing: border-box;
}

/* CABEÇALHO */

.eventos-wrap .inst-page-head {
    margin-bottom: 0;
}

/* FILTROS */

.eventos-wrap .inst-filters {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
    width: 100%;
}

/* BOTÃO DE FILTRO */

.eventos-wrap .inst-select {
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

.eventos-wrap .inst-search {
    flex: 0 1 380px;
    width: 380px;
    min-width: 180px;
}

.eventos-wrap .inst-search input {
    width: 100%;
    font-size: 14px;
    padding: 5px 12px;
}

/* BOTÃO BUSCAR */

.eventos-wrap .btn-buscar {
    padding: 11px 22px;
    font-size: 14px;
    border: 0;
    border-radius: 40px;
    background: #2e9e3f;
    color: white;
    cursor: pointer;
}

.eventos-wrap .btn-buscar:hover {
    background: #247d32;
}

/* TABELA */

.eventos-wrap .inst-card {
    border: 1px solid #d0d0d0;
    border-radius: 8px;
    margin: 0;
    overflow: visible;
    width: 100%;
    min-width: 0;
    box-shadow: none;
}

/* SEM BARRA DE ROLAGEM */

.eventos-wrap .inst-table-wrap {
    display: block;
    width: 100%;
    max-width: 100%;
    overflow: visible !important;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.eventos-wrap .inst-table-wrap::-webkit-scrollbar {
    display: none !important;
    width: 0;
    height: 0;
}

.eventos-wrap .inst-table {
    width: 100% !important;
    min-width: 100% !important;
    table-layout: auto;
    border-collapse: collapse;
    border-spacing: 0;
    margin: 0;
}

/* TÍTULOS DAS COLUNAS */

.eventos-wrap .inst-table th {
    font-size: 15px;
    font-weight: 700;
    padding: 19px 9px;
    vertical-align: middle;
    text-align: center;
    white-space: nowrap;
    overflow: visible;
    text-overflow: clip;
}

/* DADOS DA TABELA */

.eventos-wrap .inst-table td {
    font-size: 14px;
    font-weight: 500;
    padding: 17px 9px;
    vertical-align: middle;
    text-align: center;
    white-space: nowrap;
    overflow: visible;
    text-overflow: clip;
}

/* LARGURA DAS COLUNAS */

.eventos-wrap .inst-table th:nth-child(1),
.eventos-wrap .inst-table td:nth-child(1) {
    width: 25%;
    text-align: left;
    padding-left: 14px;
}

.eventos-wrap .inst-table th:nth-child(2),
.eventos-wrap .inst-table td:nth-child(2) {
    width: 13%;
}

.eventos-wrap .inst-table th:nth-child(3),
.eventos-wrap .inst-table td:nth-child(3) {
    width: 15%;
}

.eventos-wrap .inst-table th:nth-child(4),
.eventos-wrap .inst-table td:nth-child(4) {
    width: 15%;
}

.eventos-wrap .inst-table th:nth-child(5),
.eventos-wrap .inst-table td:nth-child(5) {
    width: 8%;
}

.eventos-wrap .inst-table th:nth-child(6),
.eventos-wrap .inst-table td:nth-child(6) {
    width: 24%;
    padding-left: 10px;
    padding-right: 10px;
}

/* NOTA DO EVENTO */

.eventos-wrap .nota-evento {
    font-weight: 600;
}

/* BOTÕES DE AÇÃO */


.eventos-wrap .btn-cadastrar {
    padding: 7px 12px;
    font-size: 14px;
    gap: 9px;
    border-radius: 40px;
}

.eventos-wrap .btn-cadastrar svg {
    width: 20px;
    height: 20px;
}

.eventos-wrap .btn-alterar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    padding: 10px 9px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    flex: 0 1 auto;
    min-width: 0;
    text-decoration: none;
    color: #2e9e3f;
    border-color: #2e9e3f;
    background: #fff;
}


.eventos-wrap .btn-alterar:hover {
     background: #247d32;
    color: #fff;
}

.eventos-wrap .btn-alterar svg {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

/* BOTÃO ENCERRAR */

.eventos-wrap .btn-encerrar {
    border: 1px solid #c62828;
    color: #c62828;
}

.eventos-wrap .btn-encerrar:hover {
    background: #c62828;
    color: #fff;
}


/* STATUS */


.eventos-wrap .chip-status {
    display: inline-block;
    white-space: nowrap;
    vertical-align: middle;
    padding: 5px 12px;
    font-size: inherit;
    font-weight: 600;
    line-height: 1.2;
    border-radius: 999px;
}
/* RESPONSIVIDADE */

@media (max-width: 1100px) {

    .eventos-wrap .inst-table th {
        font-size: 13px;
        padding: 15px 5px;
    }
.eventos-wrap .chip-status {
    font-size: 7px;
    padding: 3px 7px;
}
    .eventos-wrap .inst-table td {
        font-size: 12px;
        padding: 13px 5px;
    }

    .eventos-wrap .btn-alterar {
        font-size: 10px;
        padding: 8px 5px;
        gap: 4px;
    }

    .eventos-wrap .btn-alterar svg {
        width: 11px;
        height: 11px;
    }

}

@media (max-width: 900px) {

    .eventos-wrap .inst-filters {
        flex-wrap: wrap;
    }

    .eventos-wrap .inst-search {
        flex: 1 1 250px;
        width: auto;
    }


    .eventos-wrap .chip-status {
    font-size: 7px;
    padding: 3px 6px;
}
    .eventos-wrap .inst-table th {
        font-size: 11px;
        padding: 10px 3px;
    }

    .eventos-wrap .inst-table td {
        font-size: 10px;
        padding: 9px 3px;
    }

    .eventos-wrap .btn-alterar {
        font-size: 8px;
        padding: 6px 3px;
        gap: 2px;
    }

    .eventos-wrap .btn-alterar svg {
        width: 9px;
        height: 9px;
    }

}

@media (max-width: 600px) {

    .eventos-wrap .inst-table th {
        font-size: 9px;
        padding: 7px 2px;
    }

    .eventos-wrap .inst-table td {
        font-size: 8px;
        padding: 7px 2px;
    }

    .eventos-wrap .btn-alterar {
        font-size: 7px;
        padding: 4px 2px;
        gap: 1px;
    }

    .eventos-wrap .chip-status {
    font-size: 7px;
    padding: 2px 3px;
}

    .eventos-wrap .btn-alterar svg {
        width: 8px;
        height: 8px;
    }

    

}

</style>

@endsection