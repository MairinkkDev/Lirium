<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>@yield('titulo', 'Painel Administrativo') - Reciclum</title>
  @include('layouts.partials.estilos')
  @stack('estilos')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>

  <div class="app" id="appShell">
    <aside class="sidebar">
      <div class="brand">
        <div><img class="brand-logo" src="{{ asset('images/icones/logo.png') }}" alt="Logo"></div>
        <div class="brand-name">Reciclum</div>
      </div>

      <nav class="nav">
        <a class="nav-item {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/home') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9.5L12 3l9 6.5" />
              <path d="M5 10v10a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V10" />
            </svg></span>
          <span class="label">Início</span>
        </a>

        <a class="nav-item {{ request()->is('dash') ? 'active' : '' }}" href="{{ url('/dash') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="8" height="8" rx="1.5" />
              <rect x="13" y="3" width="8" height="5" rx="1.5" />
              <rect x="13" y="11" width="8" height="10" rx="1.5" />
              <rect x="3" y="14" width="8" height="7" rx="1.5" />
            </svg></span>
          <span class="label">Dashboard</span>
        </a>

        <a class="nav-item {{ request()->is('denuncia*') ? 'active' : '' }}" href="{{ url('/denuncia') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="7" r="3.2" />
              <path d="M3.5 21c0-3.6 2.5-6 5.5-6s5.5 2.4 5.5 6" />
              <line x1="19" y1="8" x2="19" y2="13" />
              <circle cx="19" cy="16.3" r="0.6" fill="currentColor" stroke="none" />
            </svg></span>
          <span class="label">Denúncias</span>
          @php($qtdDenunciasPendentes = \App\Models\Denuncia::where('statusDenuncia', 'pendente')->count())
          @if($qtdDenunciasPendentes > 0)
          <span class="badge">{{ str_pad($qtdDenunciasPendentes, 2, '0', STR_PAD_LEFT) }}</span>
          @endif
        </a>

        <a class="nav-item {{ request()->is('ecoponto*') ? 'active' : '' }}" href="{{ route('ecoponto.painel') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 a19 9.5C19 14.8 12 21 12 21z" />
              <circle cx="12" cy="9.5" r="2.3" />
            </svg></span>
          <span class="label">Gerenciar Ecopontos</span>
        </a>

        <a class="nav-item {{ request()->is('admin/eventos*') ? 'active' : '' }}"
          href="{{ route('admin.eventos.painel') }}">
          <span class="ic">
            <svg width="35" height="35" viewBox="0 0 24 24"
              fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round">
              <rect x="3" y="5" width="18" height="16" rx="2" />
              <path d="M16 3v4M8 3v4M3 11h18" />
              <path d="m9 16 2 2 4-4" />
            </svg>
          </span>
          <span class="label">Gerenciar Eventos</span>
        </a>

        <a class="nav-item {{ request()->is('admin/instituicoes*') ? 'active' : '' }}"
          href="{{ route('admin.instituicoes') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 21c4-2.6 8-5.6 8-10.2A5 5 0 0 0 12 8a5 5 0 0 0-8 2.8C4 15.4 8 18.4 12 21z" />
              <path d="M12 8V4" />
            </svg></span>
          <span class="label">Gerenciar Cooperativas/ONGs</span>
        </a>
      </nav>

      <div class="nav-bottom">
        <a class="nav-item" href="{{ route('perfil.show') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3" />
              <path
                d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.6-1H3a2 2 0 1 1 0-4h.1A1.7 1.7 0 0 0 4.7 9a1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H9a1.7 1.7 0 0 0 1-1.6V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V9a1.7 1.7 0 0 0 1.6 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.6 1z" />
            </svg></span>
          <span class="label">Meu perfil</span>
        </a>

        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="nav-item">
            <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
              </svg></span>
            <span class="label">Sair</span>
          </button>
        </form>
      </div>
    </aside>

    <header class="header">
      <div class="search-box"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round">
          <circle cx="11" cy="11" r="7" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg><input type="text" placeholder="Buscar no sistema" disabled title="Busca em breve"></div>

      @auth
      <a class="user-menu" href="{{ route('perfil.show') }}" style="text-decoration:none;">
        <div class="user-avatar-sm">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->nomeUsuario, 0, 2)) }}</div>
        <span class="name">{{ auth()->user()->nomeUsuario }}</span>
      </a>
      @endauth
    </header>

    <main class="main">

      @if(session('mensagem'))
      <div class="alert-msg">{{ session('mensagem') }}</div>
      @endif

      @yield('content')

    </main>

    <div class="toast" id="toast"></div>
  </div>

  @include('partials.confirmaMensagem')

  <script>
    let toastTimer = null;

    function showToast(msg) {
      const t = document.getElementById('toast');
      if (!t) return;
      t.textContent = msg;
      t.classList.add('show');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => t.classList.remove('show'), 1600);
    }

    function setTab(el) {
      el.parentElement.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      el.classList.add('active');
    }

    @if(session('mensagem'))
    showToast(@json(session('mensagem')));
    @endif
  </script>

  @stack('scripts')
</body>

</html>