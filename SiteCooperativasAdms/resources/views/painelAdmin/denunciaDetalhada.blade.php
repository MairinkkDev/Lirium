@extends('layouts.admin')

@section('titulo', 'Denúncia ' )

@section('content')
  <a class="back-link-page" href="{{ url('/denuncia') }}">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
      stroke-linecap="round" stroke-linejoin="round">
      <line x1="19" y1="12" x2="5" y2="12" />
      <polyline points="12 19 5 12 12 5" />
    </svg> Voltar para as denúncias
  </a>

  @php
    $statusClasse = match($denuncia->statusDenuncia) {
      'pendente' => 'pending',
      'em_analise' => 'analise',
      'resolvida' => 'resolved',
      default => 'pending',
    };
  @endphp

  <div class="page-head">
    <div>
      <h1 class="page-title">Denúncia</h1>
      <p class="page-sub">
        <span class="badge-status {{ $statusClasse }}">{{ ucfirst(str_replace('_', ' ', $denuncia->statusDenuncia)) }}</span>
      </p>
    </div>
  </div>

  <div class="info-panel">
    <div class="info-row">
      <span class="info-label">Usuário</span>
      <span class="info-value">{{ $denuncia->usuario->nomeUsuario ?? '-' }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Categoria</span>
      <span class="info-value">{{ $denuncia->categoria->descricaoCategoriaDenuncia ?? '-' }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Data</span>
      <span class="info-value">{{ \Carbon\Carbon::parse($denuncia->dataCriacaoDenuncia)->format('d/m/Y H:i') }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Descrição</span>
      <span class="info-value">{!! nl2br(e($denuncia->descricaoDenuncia)) !!}</span>
    </div>
  </div>

  <div class="actions-row">
    @if($denuncia->statusDenuncia === 'pendente')
      <form action="{{ route('denuncia.analisar', $denuncia->idDenuncia) }}" method="post">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn-primary">Analisar</button>
      </form>

    @elseif($denuncia->statusDenuncia === 'em_analise')
      <form action="{{ route('denuncia.concluir', $denuncia->idDenuncia) }}" method="post">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn-primary">Concluir denúncia</button>
      </form>

    @else
      <form action="{{ route('denuncia.reabrir', $denuncia->idDenuncia) }}" method="post"
        data-confirmar="Marcar a denúncia de {{ $denuncia->usuario->nomeUsuario }} como pendente novamente?">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn-outline">Marcar como pendente</button>
      </form>
    @endif

    <form action="{{ route('denuncia.excluir', $denuncia->idDenuncia) }}" method="post"
      data-confirmar="Excluir a denúncia #{{ str_pad($denuncia->idDenuncia, 2, '0', STR_PAD_LEFT) }}? Esta ação não pode ser desfeita.">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn-outline" style="color:#c73f3f; border-color:#f3c9c9;">Excluir</button>
    </form>
  </div>
@endsection
