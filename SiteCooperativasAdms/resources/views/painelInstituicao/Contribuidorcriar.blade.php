@extends('layouts.instituicao')

@section('titulo', 'Cadastrar contribuidor')

@section('content')
  @php
    $materiais = $materiais ?? [];
  @endphp

  <a class="inst-voltar" href="{{ url('/contribuidor') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
      stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6" />
    </svg>
    Voltar para contribuidor
  </a>

  <form action="{{ url('/contribuidor') }}" method="post" class="inst-form-card" novalidate>
    @csrf

    <h1 class="inst-form-title">Cadastrar Contribuidor</h1>
    <p class="inst-form-sub">Registre a entrega de um usuário já cadastrado, informando o CPF</p>

    @if(session('erro'))
      <p class="aviso-erro">{{ session('erro') }}</p>
    @endif

    <div class="inst-form-grid">
      <div>
        <label class="inst-label" for="cpfContribuidor">CPF</label>
        <input type="text" id="cpfContribuidor" name="cpfContribuidor" inputmode="numeric" maxlength="14"
          class="inst-input {{ $errors->has('cpfContribuidor') ? 'invalido' : '' }}"
          placeholder="000.000.000-00" value="{{ old('cpfContribuidor') }}" required>
        @error('cpfContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="inst-label" for="telefoneContribuidor">Telefone (opcional)</label>
        <input type="tel" id="telefoneContribuidor" name="telefoneContribuidor" inputmode="tel" maxlength="15"
          class="inst-input {{ $errors->has('telefoneContribuidor') ? 'invalido' : '' }}"
          placeholder="(11) 95173-9135" value="{{ old('telefoneContribuidor') }}">
        @error('telefoneContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="inst-label" for="materialContribuidor">Material</label>
        <input type="text" id="materialContribuidor" name="materialContribuidor" list="listaMateriais"
          class="inst-input {{ $errors->has('materialContribuidor') ? 'invalido' : '' }}"
          placeholder="Ex: Plástico" value="{{ old('materialContribuidor') }}" required>
        <datalist id="listaMateriais">
          @foreach($materiais as $m)
            <option value="{{ $m }}"></option>
          @endforeach
        </datalist>
        @error('materialContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="inst-label" for="quantidadeContribuidor">Quantidade (kg)</label>
        <input type="text" id="quantidadeContribuidor" name="quantidadeContribuidor" inputmode="decimal"
          class="inst-input {{ $errors->has('quantidadeContribuidor') ? 'invalido' : '' }}"
          placeholder="Ex: 11,5" value="{{ old('quantidadeContribuidor') }}" required>
        @error('quantidadeContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>
    </div>

    <hr class="inst-form-sep">

    <div class="inst-form-grid">
      <div class="full">
        <label class="inst-label" for="dataEntregaContribuidor">Data de entrega</label>
        <input type="date" id="dataEntregaContribuidor" name="dataEntregaContribuidor"
          class="inst-input inst-input-data {{ $errors->has('dataEntregaContribuidor') ? 'invalido' : '' }}"
          value="{{ old('dataEntregaContribuidor') }}" required>
        @error('dataEntregaContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>
    </div>

    <div class="inst-form-actions">
      <a href="{{ url('/contribuidor') }}" class="btn-form cancelar">Cancelar</a>
      <button type="submit" class="btn-form salvar">Cadastrar</button>
    </div>
  </form>

  <style>
    .aviso-erro { background: #fde2e2; color: #9b1c1c; border-radius: 8px; padding: 8px 12px; font-size: 12px; margin: 0 0 14px; }
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