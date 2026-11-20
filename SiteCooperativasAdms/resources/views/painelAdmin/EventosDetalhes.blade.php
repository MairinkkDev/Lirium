@extends('layouts.admin')

@section('titulo', 'Detalhes do Evento')

@push('estilos')
<style>
    .admin-detalhes {
        width: 100%;
    }

    .admin-detalhes .page-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 25px;
    }

    .admin-detalhes .page-title {
        margin: 0;
        font-size: 27px;
        font-weight: 750;
        color: #203b29;
    }

    .admin-detalhes .page-sub {
        margin: 7px 0 0;
        color: #68756c;
        font-size: 14px;
    }

    .admin-detalhes .btn-voltar {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 15px;
        border: 1px solid #d5ded6;
        border-radius: 7px;
        background: #fff;
        color: #536157;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s;
    }

    .admin-detalhes .btn-voltar:hover {
        background: #f3f7f3;
        border-color: #176b3b;
        color: #176b3b;
    }

    .admin-detalhes .detalhes-card {
        margin-bottom: 20px;
        padding: 24px;
        border: 1px solid #e0e7e0;
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
    }

    .admin-detalhes .card-title {
        margin: 0 0 20px;
        padding-bottom: 13px;
        border-bottom: 1px solid #edf1ed;
        color: #203b29;
        font-size: 17px;
        font-weight: 750;
    }

    .admin-detalhes .evento-topo {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 18px;
    }

    .admin-detalhes .evento-nome {
        margin: 0;
        color: #203b29;
        font-size: 22px;
        font-weight: 750;
    }

    .admin-detalhes .evento-descricao {
        margin: 0;
        color: #657268;
        font-size: 14px;
        line-height: 1.7;
        white-space: pre-line;
    }

    .admin-detalhes .chip-status {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
    }

    .admin-detalhes .chip-status.agendado {
        background: #e7f0ff;
        color: #285b9b;
    }

    .admin-detalhes .chip-status.andamento {
        background: #e2f5e8;
        color: #19733c;
    }

    .admin-detalhes .chip-status.encerrado {
        background: #edf0ed;
        color: #59635b;
    }

    .admin-detalhes .chip-status.cancelado {
        background: #ffebeb;
        color: #b83b3b;
    }

    .admin-detalhes .chip-status.pendente {
        background: #fff4d9;
        color: #9a6900;
    }

    .admin-detalhes .chip-status.recusado {
        background: #ffe8e8;
        color: #b83b3b;
    }

    .admin-detalhes .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .admin-detalhes .info-item {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
    }

    .admin-detalhes .info-label {
        color: #78857b;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .admin-detalhes .info-valor {
        color: #35443a;
        font-size: 14px;
        font-weight: 650;
        overflow-wrap: anywhere;
    }

    .admin-detalhes .endereco-completo {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .admin-detalhes .endereco-linha {
        color: #35443a;
        font-size: 14px;
        line-height: 1.5;
    }

    .admin-detalhes .acoes-rodape {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 22px;
    }

    .admin-detalhes .btn-acao {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 7px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 750;
        cursor: pointer;
        text-decoration: none;
        transition: .2s;
    }

    .admin-detalhes .btn-acao svg {
        width: 17px;
        height: 17px;
    }

    .admin-detalhes .btn-aprovar {
        border: 1px solid #218044;
        background: #218044;
        color: #fff;
    }

    .admin-detalhes .btn-aprovar:hover {
        background: #176b3b;
        border-color: #176b3b;
    }

    .admin-detalhes .btn-recusar {
        border: 1px solid #c43d3d;
        background: #fff;
        color: #c43d3d;
    }

    .admin-detalhes .btn-recusar:hover {
        background: #c43d3d;
        color: #fff;
    }

    @media (max-width: 700px) {
        .admin-detalhes .page-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .admin-detalhes .evento-topo {
            flex-direction: column;
        }

        .admin-detalhes .info-grid {
            grid-template-columns: 1fr;
        }

        .admin-detalhes .detalhes-card {
            padding: 18px;
        }

        .admin-detalhes .acoes-rodape {
            flex-direction: column;
            align-items: stretch;
        }

        .admin-detalhes .acoes-rodape form,
        .admin-detalhes .acoes-rodape .btn-acao {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

@php
$rotulos = [
'agendado' => 'Agendado',
'em_andamento' => 'Em andamento',
'concluido' => 'Concluído',
'cancelado' => 'Cancelado',
'pendente' => 'Pendente',
'recusado' => 'Recusado',
];

$classes = [
'agendado' => 'agendado',
'em_andamento' => 'andamento',
'concluido' => 'encerrado',
'cancelado' => 'cancelado',
'pendente' => 'pendente',
'recusado' => 'recusado',
];

$endereco = collect([
$evento->logradouroEnderecoEvento,
$evento->numeroEnderecoEvento,
$evento->complementoEnderecoEvento,
])->filter()->implode(', ');

$localidade = collect([
$evento->bairroEnderecoEvento,
$evento->cidadeEnderecoEvento,
$evento->ufEnderecoEvento,
])->filter()->implode(' - ');
@endphp

<div class="admin-detalhes">

    {{-- Cabeçalho --}}
    <div class="page-head">
        <div>
            <h1 class="page-title">Detalhes do Evento</h1>
            <p class="page-sub">
                Visualize todas as informações do evento cadastrado.
            </p>
        </div>

        <a href="{{ route('admin.eventos.painel') }}" class="btn-voltar">
            <svg width="17" height="17" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
            </svg>
            Voltar aos eventos
        </a>
    </div>


    {{-- Informações principais --}}
    <div class="detalhes-card">

        <div class="evento-topo">
            <div>
                <h2 class="evento-nome">
                    {{ $evento->nomeEvento }}
                </h2>
            </div>

            <span class="chip-status {{ $classes[$evento->statusEvento] ?? 'encerrado' }}">
                {{ $rotulos[$evento->statusEvento] ?? $evento->statusEvento }}
            </span>
        </div>

        <h3 class="card-title">Descrição do evento</h3>

        <p class="evento-descricao">
            {{ $evento->descricaoEvento ?: 'Nenhuma descrição informada.' }}
        </p>

    </div>


    {{-- Instituição responsável --}}
    <div class="detalhes-card">

        <h3 class="card-title">Instituição responsável</h3>

        <div class="info-grid">

            <div class="info-item">
                <span class="info-label">Nome da instituição</span>

                <span class="info-valor">
                    {{ $evento->instituicoes
                        ->pluck('nomeInstituicao')
                        ->filter()
                        ->join(', ') ?: 'Instituição não informada' }}
                </span>
            </div>

        </div>

    </div>


    {{-- Datas do evento --}}
    <div class="detalhes-card">

        <h3 class="card-title">Período do evento</h3>

        <div class="info-grid">

            <div class="info-item">
                <span class="info-label">Data de início</span>

                <span class="info-valor">
                    {{ $evento->dataInicioEvento?->format('d/m/Y') ?? '-' }}
                </span>

                <span class="info-label">
                    Horário:
                    {{ $evento->dataInicioEvento?->format('H:i') ?? '-' }}
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Data de encerramento</span>

                <span class="info-valor">
                    {{ $evento->dataFimEvento?->format('d/m/Y') ?? '-' }}
                </span>

                <span class="info-label">
                    Horário:
                    {{ $evento->dataFimEvento?->format('H:i') ?? '-' }}
                </span>
            </div>

        </div>

    </div>


    {{-- Endereço --}}
    <div class="detalhes-card">

        <h3 class="card-title">Localização do evento</h3>

        <div class="endereco-completo">

            <div class="endereco-linha">
                <strong>Endereço:</strong>
                {{ $endereco ?: '-' }}
            </div>

            <div class="endereco-linha">
                <strong>Bairro / Cidade / Estado:</strong>
                {{ $localidade ?: '-' }}
            </div>

            <div class="endereco-linha">
                <strong>CEP:</strong>
                {{ $evento->cepEnderecoEvento ?: '-' }}
            </div>

        </div>

    </div>


    {{-- Ações administrativas --}}
    @if($evento->statusEvento === 'pendente')

    <div class="detalhes-card">

        <h3 class="card-title">Analisar solicitação</h3>

        <p class="evento-descricao">
            Este evento está aguardando aprovação administrativa.
            Você pode aprovar a solicitação ou recusá-la.
        </p>

        <div class="acoes-rodape">

            {{-- Recusar --}}
            <form
                action="{{ route('admin.eventos.recusar', $evento->idEvento) }}"
                method="POST"
                onsubmit="return confirm('Deseja realmente recusar esta solicitação?')">

                @csrf
                @method('PATCH')

                <button type="submit" class="btn-acao btn-recusar">
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                    Recusar solicitação
                </button>

            </form>

            {{-- Aprovar --}}
            <form
                action="{{ route('admin.eventos.aprovar', $evento->idEvento) }}"
                method="POST"
                onsubmit="return confirm('Deseja realmente aprovar este evento?')">

                @csrf
                @method('PATCH')

                <button type="submit" class="btn-acao btn-aprovar">
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 12 2 2 4-4" />
                        <path d="M12 3a9 9 0 1 0 9 9" />
                    </svg>
                    Aprovar solicitação
                </button>

            </form>

        </div>

    </div>

    @endif

</div>

@endsection