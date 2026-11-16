@extends('layouts.admin')

@section('titulo', $ecoponto->descricaoEcoponto)

@section('content')
  <a class="back-link-page" href="{{ url('/ecoponto') }}">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
      stroke-linecap="round" stroke-linejoin="round">
      <line x1="19" y1="12" x2="5" y2="12" />
      <polyline points="12 19 5 12 12 5" />
    </svg> Voltar para os ecopontos
  </a>

  <div class="page-head">
    <div>
      <h1 class="page-title">{{ $ecoponto->descricaoEcoponto }}</h1>
      <p class="page-sub">
        <span class="badge-status {{ $ecoponto->statusEcoponto === 'ativo' ? 'active' : 'suspended' }}">
          {{ $ecoponto->statusEcoponto === 'ativo' ? 'Ativo' : 'Inativo' }}
        </span>
      </p>
    </div>
  </div>

  @foreach($errors->all() as $erro)
    <div class="alert-msg" style="background:#fde3e3; border-color:#f3c9c9; color:#c73f3f;">{{ $erro }}</div>
  @endforeach

  <div class="panel" style="margin-bottom:18px;">
    <div class="panel-title">Endereço</div>

    <form action="{{ route('ecoponto.atualizar', $ecoponto->idEcoponto) }}" method="post" id="formEndereco">
      @csrf
      @method('PATCH')

      <div class="form-grid">
        <div class="field-full">
          <label class="form-label">CEP</label>
          <input type="text" name="cepEcoponto" class="campo-endereco" readonly
            value="{{ old('cepEcoponto', $ecoponto->cepEcoponto) }}">
        </div>

        <div>
          <label class="form-label">Logradouro</label>
          <input type="text" name="logradouroEcoponto" class="campo-endereco" readonly
            value="{{ old('logradouroEcoponto', $ecoponto->logradouroEcoponto) }}">
        </div>

        <div>
          <label class="form-label">Número</label>
          <input type="text" name="numeroEcoponto" class="campo-endereco" readonly
            value="{{ old('numeroEcoponto', $ecoponto->numeroEcoponto) }}">
        </div>

        <div>
          <label class="form-label">Complemento</label>
          <input type="text" name="complementoEcoponto" class="campo-endereco" readonly
            value="{{ old('complementoEcoponto', $ecoponto->complementoEcoponto) }}">
        </div>

        <div>
          <label class="form-label">Bairro</label>
          <input type="text" name="bairroEcoponto" class="campo-endereco" readonly
            value="{{ old('bairroEcoponto', $ecoponto->bairroEcoponto) }}">
        </div>

        <div>
          <label class="form-label">Cidade</label>
          <input type="text" name="cidadeEcoponto" class="campo-endereco" readonly
            value="{{ old('cidadeEcoponto', $ecoponto->cidadeEcoponto) }}">
        </div>

        <div>
          <label class="form-label">UF</label>
          <input type="text" name="ufEcoponto" maxlength="2" class="campo-endereco" readonly
            value="{{ old('ufEcoponto', $ecoponto->ufEcoponto) }}">
        </div>
      </div>

      <div class="actions-row" style="margin-top:16px;">
        <button type="button" class="btn-outline" id="btnAlterarEndereco"
          @if($ecoponto->statusEcoponto === 'inativo') disabled @endif>
          Alterar endereço
        </button>

        <button type="submit" class="btn-primary" id="btnSalvarEndereco" style="display:none;">
          Salvar endereço
        </button>
      </div>

      @if($ecoponto->statusEcoponto === 'inativo')
        <p style="font-size:12.5px; color:var(--text-muted); margin-top:10px;">
          Não é possível alterar o endereço enquanto o ecoponto estiver inativo.
        </p>
      @endif
    </form>
  </div>

  <div class="panel">
    <div class="panel-title">Status</div>
    <div class="actions-row">
      @if($ecoponto->statusEcoponto === 'ativo')
        <form action="{{ route('ecoponto.inativar', $ecoponto->idEcoponto) }}" method="post"
          data-confirmar="Marcar o ecoponto {{ $ecoponto->descricaoEcoponto }} como inativo?">
          @csrf
          @method('PATCH')
          <button type="submit" class="btn-outline" style="color:#c73f3f; border-color:#f3c9c9;">Marcar como inativo</button>
        </form>
      @else
        <form action="{{ route('ecoponto.ativar', $ecoponto->idEcoponto) }}" method="post">
          @csrf
          @method('PATCH')
          <button type="submit" class="btn-primary">Marcar como ativo</button>
        </form>
      @endif

      <form action="{{ route('ecoponto.excluir', $ecoponto->idEcoponto) }}" method="post"
        data-confirmar="Excluir o ecoponto {{ $ecoponto->descricaoEcoponto }}? Esta ação não pode ser desfeita.">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-outline" style="color:#c73f3f; border-color:#f3c9c9;">Excluir</button>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const btnAlterar = document.getElementById('btnAlterarEndereco');
      const btnSalvar = document.getElementById('btnSalvarEndereco');
      const campos = document.querySelectorAll('.campo-endereco');

      if (btnAlterar) {
        btnAlterar.addEventListener('click', function () {
          campos.forEach(campo => campo.removeAttribute('readonly'));
          btnAlterar.style.display = 'none';
          btnSalvar.style.display = 'inline-block';
          if (campos.length) campos[0].focus();
        });
      }
    });
  </script>
@endsection
