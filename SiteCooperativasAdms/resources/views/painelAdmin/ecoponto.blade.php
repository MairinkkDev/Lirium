@extends('layouts.admin')

@section('titulo', 'Ecopontos')

@section('content')
  <div class="page-head">
    <div>
      <h1 class="page-title">Gerenciar Ecopontos</h1>
      <p class="page-sub">Visão geral dos Ecopontos ({{ number_format($ecoponto->total(), 0, ',', '.') }} no total)</p>
    </div>
  </div>

  <form action="{{ route('ecoponto.painel') }}" method="get" class="filters-row">
    <div class="search-box"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round">
        <circle cx="11" cy="11" r="7" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg><input type="text" name="descricao" placeholder="Pesquisar pelo nome do ecoponto..." value="{{ $descricao }}"></div>

    <button type="submit" class="select-btn" style="border:none; cursor:pointer;">Pesquisar</button>

    @if($descricao)
      <a href="{{ route('ecoponto.painel') }}" class="btn-reset-link">Limpar pesquisa</a>
    @endif
  </form>

  <div class="table-panel">
    <table class="data-table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Cidade</th>
          <th>UF</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @forelse($ecoponto as $e)
          <tr>
            <td>{{ $e->descricaoEcoponto }}</td>
            <td>{{ $e->cidadeEcoponto }}</td>
            <td>{{ $e->ufEcoponto }}</td>
            <td><span class="badge-status {{ $e->statusEcoponto === 'ativo' ? 'active' : 'suspended' }}">{{ $e->statusEcoponto === 'ativo' ? 'Ativo' : 'Inativo' }}</span></td>
            <td gap:8px;">
              <a class="icon-btn" href="{{ route('ecoponto.detalhe', $e->idEcoponto) }}" title="Visualizar">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </a>
              <form class="inline-form" action="{{ route('ecoponto.excluir', $e->idEcoponto) }}" method="post"
                data-confirmar="Excluir o ecoponto {{ $e->descricaoEcoponto }}? Esta ação não pode ser desfeita.">
                @csrf
                @method('DELETE')
                <button type="submit" class="icon-btn danger" title="Excluir">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    <path d="M10 11v6M14 11v6" />
                  </svg>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center; padding:40px; color:var(--text-muted);">
              @if($descricao)
                Nenhum ecoponto encontrado com esse nome.
              @else
                Nenhum ecoponto cadastrado.
              @endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{ $ecoponto->links('vendor.pagination.simples') }}
  </div>
@endsection
