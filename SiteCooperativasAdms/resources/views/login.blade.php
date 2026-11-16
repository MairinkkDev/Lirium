@extends('layouts.auth')

@section('titulo', 'Login')

@section('content')
  <div class="auth-logo">
    <div><img class="brand-logo" src="{{ asset('images/icones/logo.png') }}" alt="Logo"></div>
    <span class="brand-name">Reciclum</span>
  </div>

  <h1 class="auth-title center">Bem-vindo de volta!</h1>
  <p class="auth-sub center">Faça login para acessar sua conta e continuar ajudando o meio ambiente.</p>

  <form action="{{  route('login.autenticar') }}" method="post">
    @csrf

    <div class="field">
      <span class="ic-left"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="8" r="4" />
          <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7" />
        </svg></span>
      <input type="email" name="emailUsuario" placeholder="E-mail" value="{{ old('emailUsuario') }}" required>
    </div>
    @error('emailUsuario') <span class="field-error">{{ $message }}</span> @enderror

    <div class="field">
      <span class="ic-left"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="4" y="11" width="16" height="9" rx="2" />
          <path d="M8 11V8a4 4 0 0 1 8 0v3" />
        </svg></span>
      <input type="password" id="loginPass" name="senhaUsuario" placeholder="Senha" required>
      <span class="eye" onclick="togglePass('loginPass', this)"><svg width="20" height="20" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
          <circle cx="12" cy="12" r="3" />
        </svg></span>
    </div>
        @error('senhaUsuario') <span class="field-error">{{ $message }}</span> @enderror

    <div class="remember-row">
      <label class="remember-l" for="lembrarCheckbox">
        <input type="checkbox" name="lembrar" id="lembrarCheckbox" value="1" checked
          style="position:absolute; opacity:0; width:0; height:0;" onchange="syncRemember(this)">
        <span class="checkbox checked" id="lembrarVisual"><svg width="11" height="11" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12" />
          </svg></span><span>Lembrar de mim</span>
      </label>
      <span class="forgot-link">Esqueceu a senha?</span>
    </div>

        <button type="submit" class="btn-primary">Entrar <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="5" y1="12" x2="19" y2="12" />
        <polyline points="12 5 19 12 12 19" />
      </svg></button>


    <button type="button" class="btn-outline" onclick="window.location.href='{{ route('instituicao.cadastro') }}'">
      <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="8" r="4" />
        <path d="M2 21c0-4 3.1-6.5 7-6.5s7 2.5 7 6.5" />
        <line x1="18" y1="8" x2="18" y2="14" />
        <line x1="15" y1="11" x2="21" y2="11" />
      </svg> Criar uma conta
    </button>
  </form>
    @push('scripts')
  <script>
    function syncRemember(cb) {
      document.getElementById('lembrarVisual').classList.toggle('checked', cb.checked);
    }

    function togglePass(id, el) {
      const input = document.getElementById(id);
      const mostrar = input.type === 'password';
      input.type = mostrar ? 'text' : 'password';

      el.innerHTML = mostrar
        ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.9 17.9A10.9 10.9 0 0 1 12 20c-7 0-11-8-11-8a18.5 18.5 0 0 1 5.1-5.9M9.9 4.2A9.1 9.1 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.2 3.2M14.1 14.1a3 3 0 1 1-4.2-4.2"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
        : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>';
    }
  </script>
@endpush
@endsection

