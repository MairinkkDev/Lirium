
    @extends('layouts.admin')

    @section('titulo', 'Cooperativas e ONGs')

    @section('content')

    <div class="page-head">
        <div>
            <h1 class="page-title">Gerenciar Cooperativas e ONGs</h1>
            <p class="page-sub">Visualize e gerencie as organizações cadastradas</p>
        </div>
    </div>

    <div class="stats-row" style="margin-bottom:18px;">
        <div class="stat-card">
            <div class="stat-top">Total</div>
            <div class="stat-value">
                {{ number_format($totalInstituicoes, 0, ',', '.') }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">Ativas</div>
            <div class="stat-value" style="color:var(--green-1)">
                {{ number_format($instituicoesAtivas, 0, ',', '.') }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">Inativas</div>
            <div class="stat-value" style="color:#e14b4b">
                {{ number_format($instituicoesInativas, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <form action="{{ route('admin.instituicoes') }}" method="GET" class="filters-row">

        <div class="search-box">
            <svg width="13" height="13" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round">
                <circle cx="11" cy="11" r="7" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>

            <input
                type="text"
                name="nome"
                placeholder="Buscar organização..."
                value="{{ $nome }}"
            >
        </div>

        <select name="tipo" class="native-select" onchange="this.form.submit()">
            <option value="todos" {{ $tipo == 'todos' ? 'selected' : '' }}>
                Todos os tipos
            </option>

            @foreach($tipos as $t)
                <option
                    value="{{ $t->idTipoInstituicao }}"
                    {{ $tipo == $t->idTipoInstituicao ? 'selected' : '' }}
                >
                    {{ $t->nomeTipoInstituicao }}
                </option>
            @endforeach
        </select>

        <select name="status" class="native-select" onchange="this.form.submit()">
            <option value="todos" {{ $status == 'todos' ? 'selected' : '' }}>
                Todos os status
            </option>

            <option value="ativo" {{ $status == 'ativo' ? 'selected' : '' }}>
                Ativas
            </option>

            <option value="inativo" {{ $status == 'inativo' ? 'selected' : '' }}>
                Inativas
            </option>
        </select>

        <button type="submit" class="select-btn" style="border:none; cursor:pointer;">
            Filtrar
        </button>

        @if($nome || $tipo != 'todos' || $status != 'todos')
            <a href="{{ route('admin.instituicoes') }}" class="btn-reset-link">
                Limpar filtros
            </a>
        @endif

    </form>

    <div class="table-panel">
        <table class="data-table">

            <thead>
                <tr>
                    <th>Instituição</th>
                    <th>Tipo</th>
                    <th>CNPJ</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>

                @forelse($instituicao as $i)

                    <tr>
                        <td>{{ $i->nomeInstituicao }}</td>

                        <td>
                            {{ $i->tipoInstituicao->nomeTipoInstituicao ?? 'Não informado' }}
                        </td>

                        <td>
                            {{ $i->cnpjInstituicao ?? 'Não informado' }}
                        </td>

                        <td>
                            {{ $i->emailInstituicao ?? 'Não informado' }}
                        </td>

                        <td>
                            @forelse($i->telefones as $telefone)
                                <div>{{ $telefone->numTelefoneInstituicao }}</div>
                            @empty
                                Não informado
                            @endforelse
                        </td>

                        <td>
                            <span class="badge-status {{ $i->statusInstituicao === 'ativo' ? 'active' : 'suspended' }}">
                                {{ $i->statusInstituicao === 'ativo' ? 'Ativa' : 'Inativa' }}
                            </span>
                        </td>

                        <td style="display:flex; gap:8px;">

                            <a
                                class="icon-btn"
                                href="{{ route('instituicao.detalhe', $i->idInstituicao) }}"
                                title="Visualizar"
                            >
                                <svg width="15" height="15" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>

                            @if($i->statusInstituicao === 'ativo')

                                    <form
                                        class="inline-form"
                                        action="{{ route('instituicao.suspender', $i->idInstituicao) }}"
                                        method="POST"
                                        data-confirmar="Deseja suspender a instituição {{ $i->nomeInstituicao }}?"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="icon-btn danger text-btn"
                                            title="Suspender"
                                        >
                                            Suspender
                                        </button>
                                    </form>

                                @else

                                    <form
                                        class="inline-form"
                                        action="{{ route('instituicao.reativar', $i->idInstituicao) }}"
                                        method="POST"
                                        data-confirmar="Deseja reativar a instituição {{ $i->nomeInstituicao }}?"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="icon-btn text-btn"
                                            title="Reativar"
                                        >
                                            Reativar
                                        </button>
                                    </form>

                                @endif
                            </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">
                            @if($nome || $tipo != 'todos' || $status != 'todos')
                                Nenhuma instituição encontrada com esse filtro.
                            @else
                                Nenhuma instituição cadastrada.
                            @endif
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        {{ $instituicao->links('vendor.pagination.simples') }}

    </div>

    @endsection
