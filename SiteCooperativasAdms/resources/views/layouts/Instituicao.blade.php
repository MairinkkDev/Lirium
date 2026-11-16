<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>@yield('titulo', 'Painel da Instituição') - Reciclum</title>
  @include('layouts.partials.estilos')
  @include('layouts.partials.estilosInstituicao')
  @stack('estilos')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body class="inst">

  <div class="app" id="appShell">
    <aside class="sidebar">
      <div class="brand">
        <div><img class="brand-logo" src="{{ asset('images/icones/logo.png') }}" alt="Logo"></div>
        <div class="brand-name">Reciclum</div>
      </div>

      <nav class="nav">
        <a class="nav-item {{ request()->is('estoque*') ? 'active' : '' }}" href="{{ url('/estoque') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="8" height="8" rx="1.5" />
              <rect x="13" y="3" width="8" height="5" rx="1.5" />
              <rect x="13" y="11" width="8" height="10" rx="1.5" />
              <rect x="3" y="14" width="8" height="7" rx="1.5" />
            </svg></span>
          <span class="label">Estoque</span>
        </a>

        <a class="nav-item {{ request()->is('contribuidor*') ? 'active' : '' }}" href="{{ url('/contribuidor') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="8" r="4" />
              <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7" />
            </svg></span>
          <span class="label">Contribuidor</span>
        </a>

        <a class="nav-item {{ request()->is('eventos*') ? 'active' : '' }}" href="{{ route('evento.painel') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z" />
              <circle cx="12" cy="9.5" r="2.3" />
            </svg></span>
          <span class="label">Eventos</span>
        </a>
      </nav>

      <div class="nav-bottom">
        <a class="nav-item" href="{{ route('perfil.show') }}">
          <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3" />
              <path
                d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.6-1H3a2 2 0 1 1 0-4h.1A1.7 1.7 0 0 0 4.7 9a1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H9a1.7 1.7 0 0 0 1-1.6V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V9a1.7 1.7 0 0 0 1.6 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.6 1z" />
            </svg></span>
          <span class="label">Configurações</span>
        </a>
       <form action="{{ route('logout') }}" method="POST">
  @csrf
  <button type="submit" class="nav-item">
    <span class="ic"><svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
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
  @php
    $uLogado  = auth()->user();
    $fotoTopo = $uLogado->instituicao ? $uLogado->instituicao->foto_url : $uLogado->foto_url;
  @endphp
  <a class="user-menu" href="{{ route('perfil.show') }}" style="text-decoration:none;">
    <div class="user-avatar-sm" style="overflow:hidden;">
      @if($fotoTopo)
        <img src="{{ $fotoTopo }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">
      @else
        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($uLogado->nomeUsuario, 0, 2)) }}
      @endif
    </div>
    <span class="name">{{ $uLogado->nomeUsuario }}</span>
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

    @if(session('mensagem'))
      showToast(@json(session('mensagem')));
    @endif
  </script>

  @stack('scripts')
</body>

</html>