@extends('layouts.auth')

@section('titulo', 'Login')
@section('auth-page-class', 'auth-login-page')
@section('auth-card-class', 'auth-login-wrapper')

@section('content')

<div class="login-card">

  {{-- ========== PAINEL ESQUERDO (verde) ========== --}}
  <div class="login-lado">
    <div class="login-marca">
      <img class="brand-logo" src="{{ asset('images/icones/logo.png') }}" alt="Logo">
      <span class="brand-name">Reciclum</span>
    </div>

    <div class="login-lado-texto">
      <h2>Novo por aqui?</h2>
      <p>Cadastre sua instituição e faça parte da nossa rede de sustentabilidade.</p>

      <a href="{{ route('instituicao.cadastro') }}" class="btn-ghost">Criar uma conta</a>
    </div>
  </div>

  {{-- ========== PAINEL DIREITO (formulário) ========== --}}
  <div class="login-form-area">
    <h1 class="login-titulo">Bem-vindo de volta!</h1>
    <p class="login-sub">Faça login para acessar sua conta e continuar ajudando o meio ambiente.</p>

    <form action="{{ route('login.autenticar') }}" method="post" class="login-form">
      @csrf

      <div class="field">
        <span class="ic-left"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="5" width="18" height="14" rx="2" />
            <path d="M3 7l9 6 9-6" />
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

      <button type="submit" class="btn-entrar">Entrar</button>
    </form>
  </div>

</div>

<style>
  /* O wrapper do layout deixa de limitar o cartão */
  .auth-login-wrapper {
    max-width: none !important;
    width: 100% !important;
    padding: 0 !important;
    background: transparent !important;
    border: 0 !important;
    box-shadow: none !important;
  }

  .auth-page.auth-login-page { padding: 24px !important; }

  /* ===== Cartão dividido ===== */
  .login-card {
    width: 100%;
    max-width: 920px;
    min-height: 520px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 0.9fr 1.1fr;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 12px 35px rgba(0, 0, 0, .16);
  }

  /* ===== Painel esquerdo ===== */
  .login-lado {
    background: linear-gradient(135deg, #1f8a4c 0%, #0e5c34 100%);
    color: #fff;
    padding: 34px 36px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    text-align: center;
  }

  .login-marca {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .login-marca .brand-logo { width: 44px; height: 44px; }
  .login-marca .brand-name {
    font-family: 'Raleway', sans-serif;
    font-weight: 300;
    font-size: 26px;
    color: #fff;
  }

  .login-lado-texto { margin: auto 0; }
  .login-lado-texto h2 {
    margin: 0 0 10px;
    font-family: 'Raleway', sans-serif;
    font-size: 30px;
    font-weight: 700;
  }
  .login-lado-texto p {
    margin: 0 0 26px;
    font-family: 'Raleway', sans-serif;
    font-size: 14px;
    font-weight: 300;
    line-height: 1.5;
    opacity: .95;
  }

  .btn-ghost {
    display: inline-block;
    padding: 11px 34px;
    border: 1.5px solid #fff;
    border-radius: 30px;
    color: #fff;
    font-family: 'Raleway', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    text-decoration: none;
    transition: background .15s, color .15s;
  }
  .btn-ghost:hover { background: #fff; color: #0e5c34; }

  /* espaçador para centralizar o texto verticalmente */
  .login-lado::after { content: ''; height: 44px; }

  /* ===== Painel direito ===== */
  .login-form-area {
    padding: 44px 56px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .login-titulo {
    margin: 0 0 6px;
    text-align: center;
    font-family: 'Raleway', sans-serif;
    font-size: 30px;
    font-weight: 700;
    color: #1f8a4c;
  }
  .login-sub {
    margin: 0 0 26px;
    text-align: center;
    font-family: 'Raleway', sans-serif;
    font-size: 13px;
    font-weight: 300;
    line-height: 1.5;
    color: #6b7871;
  }

  /* ===== Campos (cinza, sem borda, como na referência) ===== */
  .login-card .field {
    position: relative;
    margin: 0 0 12px;
  }
  .login-card .field input {
    width: 100%;
    height: 46px;
    padding: 0 44px 0 46px;
    border: 0;
    border-radius: 6px;
    background: #eceff0;
    font-size: 14px;
    outline: none;
  }
  .login-card .field input:focus {
    background: #e3e9e5;
    box-shadow: 0 0 0 2px rgba(31, 138, 76, .25);
  }
  .login-card .field .ic-left { left: 14px; color: #6f7c75; }
  .login-card .field .eye     { right: 14px; color: #6f7c75; }

  .login-card .field-error { margin: -6px 0 10px; }

  .login-card .remember-row { margin: 6px 0 22px; }

  /* ===== Botão Entrar (pílula verde) ===== */
  .btn-entrar {
    display: block;
    width: 220px;
    margin: 0 auto;
    padding: 13px;
    border: 0;
    border-radius: 30px;
    background: #1f8a4c;
    color: #fff;
    font-family: 'Raleway', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    cursor: pointer;
    transition: background .15s;
  }
  .btn-entrar:hover { background: #0e5c34; }

  /* ===== Telas pequenas: painéis empilhados ===== */
  @media (max-width: 760px) {
    .login-card { grid-template-columns: 1fr; min-height: 0; }
    .login-lado { padding: 26px 24px; gap: 18px; }
    .login-lado::after { display: none; }
    .login-lado-texto { margin: 0; }
    .login-lado-texto p { margin-bottom: 18px; }
    .login-form-area { padding: 30px 24px 34px; }
  }
</style>

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