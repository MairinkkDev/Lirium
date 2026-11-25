@extends('layouts.instituicao')

@section('titulo', 'Cadastrar contribuidor')

@section('content')

  <a class="inst-voltar" href="{{ url('/contribuidor') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
      stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6" />
    </svg>
    Voltar para contribuidor
  </a>

  <div class="criar-wrap">

  <form action="{{ url('/contribuidor') }}" method="post" class="inst-form-card" novalidate>
    @csrf

    <h1 class="inst-form-title">Cadastrar Contribuidor</h1>
    <p class="inst-form-sub">Cadastre uma nova pessoa informando nome, CPF e telefone</p>

    @if(session('erro'))
      <p class="aviso-erro">{{ session('erro') }}</p>
    @endif

    <div class="inst-form-grid">
      <div class="full">
        <label class="inst-label" for="nomeContribuidor">Nome</label>
        <input type="text" id="nomeContribuidor" name="nomeContribuidor" maxlength="100"
          class="inst-input {{ $errors->has('nomeContribuidor') ? 'invalido' : '' }}"
          value="{{ old('nomeContribuidor') }}" required>
        @error('nomeContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="inst-label" for="cpfContribuidor">CPF</label>
        <input type="text" id="cpfContribuidor" name="cpfContribuidor" inputmode="numeric" maxlength="14"
          class="inst-input {{ $errors->has('cpfContribuidor') ? 'invalido' : '' }}"
          placeholder="000.000.000-00" value="{{ old('cpfContribuidor') }}" required>
        @error('cpfContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="inst-label" for="telefoneContribuidor">Telefone</label>
        <input type="tel" id="telefoneContribuidor" name="telefoneContribuidor" inputmode="tel" maxlength="15"
          class="inst-input {{ $errors->has('telefoneContribuidor') ? 'invalido' : '' }}"
          placeholder="(11) 95173-9135" value="{{ old('telefoneContribuidor') }}" required>
        @error('telefoneContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>
    </div>

    <div class="inst-form-actions">
      <a href="{{ url('/contribuidor') }}" class="btn-form cancelar">Cancelar</a>
      <button type="submit" class="btn-form salvar">Cadastrar</button>
    </div>
  </form>

  </div>

  <style>
    /* A área principal do layout tem max-width e fica à esquerda; aqui ela ocupa
       toda a largura e o bloco de cadastro é centralizado na página. */
    .main { max-width: none; }

    .criar-wrap {
      width: 100%;
      max-width: 820px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 20px;
      min-width: 0;
    }

    .criar-wrap * { box-sizing: border-box; }

    .inst-voltar {
      display: inline-flex;
      align-items: center;
      margin: 0 0 20px;
      font-size: 20px;
      font-weight: 600;
      gap: 8px;
    }

    .inst-voltar svg { width: 24px; height: 24px; }

    .criar-wrap .inst-form-card {
      width: 100%;
      max-width: none;
      margin: 0;
      padding: 44px 48px;
      border: 2px solid #d0d0d0;
      border-radius: 12px;
      box-shadow: none;
    }

    .criar-wrap .inst-form-title {
      font-size: 36px;
      text-align: center;
      margin: 0 0 8px;
    }

    .criar-wrap .inst-form-sub {
      font-size: 18px;
      text-align: center;
      margin: 0 0 30px;
    }

    .criar-wrap .inst-form-grid { gap: 24px; }

    .criar-wrap .inst-label {
      display: block;
      font-size: 17px;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .criar-wrap .inst-input {
      width: 100%;
      font-size: 19px;
      padding: 15px 18px;
      height: auto;
      border-radius: 10px;
    }

    .criar-wrap .inst-erro { font-size: 15px; margin-top: 6px; display: block; }

    .criar-wrap .inst-form-actions {
      display: flex;
      justify-content: center;
      gap: 18px;
      margin-top: 34px;
    }

    .criar-wrap .btn-form {
      font-size: 19px;
      font-weight: 600;
      padding: 15px 40px;
      border-radius: 10px;
      min-width: 160px;
      text-align: center;
    }

    .aviso-erro { background: #fde2e2; color: #9b1c1c; border-radius: 8px; padding: 12px 16px; font-size: 16px; margin: 0 0 20px; }

    @media (max-width: 600px) {
      .criar-wrap .inst-form-card { padding: 28px 20px; }
      .criar-wrap .inst-form-title { font-size: 28px; }
      .criar-wrap .inst-form-sub { font-size: 16px; }
      .criar-wrap .inst-form-actions { flex-direction: column; }
      .criar-wrap .btn-form { width: 100%; }
    }
  </style>

  <script>
    const mascaraCpf = v => {
      v = v.replace(/\D/g, '').slice(0, 11);
      return v.replace(/^(\d{3})(\d)/, '$1.$2')
              .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
              .replace(/\.(\d{3})(\d)/, '.$1-$2');
    };

    const mascaraTel = v => {
      v = v.replace(/\D/g, '').slice(0, 11);
      if (v.length > 10) return v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
      if (v.length > 6)  return v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
      if (v.length > 2)  return v.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
      return v ? '(' + v : '';
    };

    [['cpfContribuidor', mascaraCpf], ['telefoneContribuidor', mascaraTel]].forEach(([id, fn]) => {
      const campo = document.getElementById(id);
      if (!campo) return;
      campo.value = fn(campo.value);
      campo.addEventListener('input', () => campo.value = fn(campo.value));
    });
  </script>
@endsection