@extends('layouts.admin')

@section('titulo', 'Denúncias')

@section('content')
  <div class="page-head">
    <div>
      <h1 class="page-title">Denúncias</h1>
      <p class="page-sub">Acompanhe e gerencie as denúncias registradas no sistema</p>
    </div>
  </div>

  <div class="tabs">
    <a href="{{ url('/denuncia') }}" class="tab {{ $status === 'todas' ? 'active' : '' }}" style="text-decoration:none; padding: 20px; padding-inline: 40px;">Todas</a>
    <a href="{{ url('/denuncia') }}?status=pendente" class="tab {{ $status === 'pendente' ? 'active' : '' }}" style="text-decoration:none; padding: 20px; padding-inline: 40px;">Pendentes</a>
    <a href="{{ url('/denuncia') }}?status=em_analise" class="tab {{ $status === 'em_analise' ? 'active' : '' }}" style="text-decoration:none; padding: 20px; padding-inline: 40px;">Em análise</a>
    <a href="{{ url('/denuncia') }}?status=resolvida" class="tab {{ $status === 'resolvida' ? 'active' : '' }}" style="text-decoration:none; padding: 20px; padding-inline: 40px;">Resolvidas</a>
  </div>

  <div class="table-panel">
    <table class="data-table">
      <thead>
        <tr>
          
          <th>Usuário</th>
          <th>Categoria</th>
          <th>Descrição</th>
          <th>Status</th>
          <th>Data</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @forelse($denuncia as $d)
          @php
            $statusClasse = match($d->statusDenuncia) {
              'pendente' => 'pending',
              'em_analise' => 'analise',
              'resolvida' => 'resolved',
              default => 'pending',
            };
          @endphp
          <tr>
            
            <td>{{ $d->usuario->nomeUsuario ?? '-' }}</td>
            <td>{{ $d->categoria->descricaoCategoriaDenuncia ?? '-' }}</td>
            <td>{{ \Illuminate\Support\Str::limit($d->descricaoDenuncia, 40) }}</td>
            <td><span class="badge-status {{ $statusClasse }}">{{ ucfirst(str_replace('_', ' ', $d->statusDenuncia)) }}</span></td>
            <td>{{ \Carbon\Carbon::parse($d->dataCriacaoDenuncia)->format('d/m/Y H:i') }}</td>
            <td gap:10px;">
              <a class="icon-btn" href="{{ route('denuncia.detalhe', $d->idDenuncia) }}" title="Visualizar">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </a>
              <form class="inline-form" action="{{ route('denuncia.excluir', $d->idDenuncia) }}" method="post"
                data-confirmar="Excluir a denúncia #{{ str_pad($d->idDenuncia, 2, '0', STR_PAD_LEFT) }}? Esta ação não pode ser desfeita.">
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
            <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">Nenhuma denúncia registrada.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{ $denuncia->links('vendor.pagination.simples') }}
  </div>
@endsection
