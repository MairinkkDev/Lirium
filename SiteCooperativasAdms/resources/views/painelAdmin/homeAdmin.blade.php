@extends('layouts.admin')

@section('titulo', 'Início')

@section('content')
  @php($usuario = auth()->user())

  <h1 class="welcome">Bem-vindo, Administrador!</h1>

  <div class="profile-card">
    <div class="profile-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($usuario->nomeUsuario, 0, 2)) }}</div>
    <div class="profile-info">
      <div class="p-name">{{ $usuario->nomeUsuario }}</div>
    </div>
  </div>

  <div class="menu-list">
    <a class="menu-card" href="{{ url('/dash') }}">
      <div class="menu-icon"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="8" height="8" rx="1.5" />
          <rect x="13" y="3" width="8" height="5" rx="1.5" />
          <rect x="13" y="11" width="8" height="10" rx="1.5" />
          <rect x="3" y="14" width="8" height="7" rx="1.5" />
        </svg></div>
      <div class="menu-text">
        <div class="menu-title">Dashboard</div>
        <div class="menu-sub">Visão geral do sistema</div>
      </div>
      <div class="menu-arrow">›</div>
    </a>

    <a class="menu-card" href="{{ url('/denuncia') }}">
      <div class="menu-icon"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="7" r="3.2" />
          <path d="M3.5 21c0-3.6 2.5-6 5.5-6s5.5 2.4 5.5 6" />
          <line x1="19" y1="8" x2="19" y2="13" />
          <circle cx="19" cy="16.3" r="0.6" fill="currentColor" stroke="none" />
        </svg></div>
      <div class="menu-text">
        <div class="menu-title">Denúncias</div>
        <div class="menu-sub">Visualize e responda às denúncias</div>
      </div>
      <div class="menu-arrow">›</div>
    </a>

    <a class="menu-card" href="{{ route('ecoponto.painel') }}">
      <div class="menu-icon"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z" />
          <circle cx="12" cy="9.5" r="2.3" />
        </svg></div>
      <div class="menu-text">
        <div class="menu-title">Gerenciar Ecopontos</div>
        <div class="menu-sub">Visão geral dos Ecopontos</div>
      </div>
      <div class="menu-arrow">›</div>
    </a>

    <a class="menu-card" href="{{ route('admin.instituicoes') }}">
      <div class="menu-icon"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 21c4-2.6 8-5.6 8-10.2A5 5 0 0 0 12 8a5 5 0 0 0-8 2.8C4 15.4 8 18.4 12 21z" />
          <path d="M12 8V4" />
        </svg></div>
      <div class="menu-text">
        <div class="menu-title">Gerenciar Cooperativas/ONGs</div>
        <div class="menu-sub">Visão geral de cooperativas e ONGs</div>
      </div>
      <div class="menu-arrow">›</div>
    </a>
  </div>
@endsection
