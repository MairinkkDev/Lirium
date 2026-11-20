@extends('layouts.instituicao')

@section('titulo', 'Solicitar evento')

@push('estilos')
<style>
  /* Texto digitado bem visível */
  .inst-form-card .inst-input {
    color: #111d14 !important;
    font-size: 15px !important;
    font-weight: 500;
    background: #fff !important;
    border: 1.5px solid #b9c9be !important;
    caret-color: #1f8a4c;
  }

  /* Placeholder mais claro que o texto digitado */
  .inst-form-card .inst-input::placeholder {
    color: #8fa095 !important;
    font-weight: 400;
  }

  /* Campo em foco */
  .inst-form-card .inst-input:focus {
    outline: none !important;
    border-color: #1f8a4c !important;
    box-shadow: 0 0 0 4px rgba(31, 138, 76, .18) !important;
  }

  /* Rótulos */
  .inst-form-card .inst-label {
    color: #1d3a27;
    font-weight: 700;
  }

  /* Campo com erro */
  .inst-form-card .inst-input.invalido {
    border-color: #dc2626 !important;
  }

  /* Aviso sobre a aprovação */
  .inst-aviso-solicitacao {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 15px 17px;
    margin: 20px 0 25px;
    background: #edf7f0;
    border: 1px solid #c5e3ce;
    border-radius: 10px;
    color: #245c36;
    font-size: 14px;
    line-height: 1.6;
  }

  .inst-aviso-solicitacao svg {
    flex-shrink: 0;
    width: 21px;
    height: 21px;
    margin-top: 1px;
  }

  .inst-aviso-solicitacao strong {
    display: block;
    margin-bottom: 2px;
  }
</style>
@endpush

@section('content')

@php
$ufs = [
'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF',
'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA',
'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS',
'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
];
@endphp

<a class="inst-voltar" href="{{ route('evento.painel') }}">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2.6" stroke-linecap="round"
    stroke-linejoin="round">
    <polyline points="15 18 9 12 15 6" />
  </svg>
  Voltar para eventos
</a>

<form action="{{ route('evento.store') }}"
  method="POST"
  class="inst-form-card"
  novalidate>

  @csrf

  <h1 class="inst-form-title">Solicitar evento</h1>

  <p class="inst-form-sub">
    Preencha os dados para enviar uma solicitação de evento.
    Após o envio, ela será analisada pela administração antes
    de ser divulgada aos usuários do aplicativo.
  </p>

  <div class="inst-aviso-solicitacao">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
      stroke-width="2" stroke-linecap="round"
      stroke-linejoin="round">
      <circle cx="12" cy="12" r="10" />
      <path d="M12 8v4" />
      <path d="M12 16h.01" />
    </svg>

    <div>
      <strong>Atenção: aprovação necessária</strong>
      Após o envio, seu evento ficará com o status
      <strong>pendente</strong> até que um administrador
      analise e aprove ou recuse a solicitação.
    </div>
  </div>

  <div class="inst-form-grid">
    <div class="full">
      <label class="inst-label" for="nomeEvento">
        Título
      </label>

      <input
        type="text"
        id="nomeEvento"
        name="nomeEvento"
        maxlength="150"
        class="inst-input {{ $errors->has('nomeEvento') ? 'invalido' : '' }}"
        placeholder="Ex: Mutirão de coleta de eletrônicos..."
        value="{{ old('nomeEvento') }}"
        required>

      @error('nomeEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>
  </div>

  <hr class="inst-form-sep">

  <div class="inst-form-grid">
    <div class="full">
      <label class="inst-label" for="descricaoEvento">
        Descrição
      </label>

      <textarea
        id="descricaoEvento"
        name="descricaoEvento"
        class="inst-input inst-textarea {{ $errors->has('descricaoEvento') ? 'invalido' : '' }}"
        placeholder="Ex: Traga aparelhos antigos para descarte correto...">{{ old('descricaoEvento') }}</textarea>

      @error('descricaoEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>
  </div>

  <hr class="inst-form-sep">

  <h2 class="inst-form-secao">Endereço</h2>

  <div class="inst-form-grid endereco">

    <div>
      <label class="inst-label" for="cepEnderecoEvento">
        CEP
      </label>

      <input
        type="text"
        id="cepEnderecoEvento"
        name="cepEnderecoEvento"
        inputmode="numeric"
        maxlength="9"
        class="inst-input box {{ $errors->has('cepEnderecoEvento') ? 'invalido' : '' }}"
        value="{{ old('cepEnderecoEvento') }}"
        required>

      @error('cepEnderecoEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>

    <div>
      <label class="inst-label" for="cidadeEnderecoEvento">
        Cidade
      </label>

      <input
        type="text"
        id="cidadeEnderecoEvento"
        name="cidadeEnderecoEvento"
        maxlength="100"
        class="inst-input box {{ $errors->has('cidadeEnderecoEvento') ? 'invalido' : '' }}"
        value="{{ old('cidadeEnderecoEvento') }}"
        required>

      @error('cidadeEnderecoEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>

    <div>
      <label class="inst-label" for="ufEnderecoEvento">
        UF
      </label>

      <select
        id="ufEnderecoEvento"
        name="ufEnderecoEvento"
        class="inst-input box select {{ $errors->has('ufEnderecoEvento') ? 'invalido' : '' }}"
        required>
        <option value="" hidden>Selecione</option>

        @foreach($ufs as $uf)
        <option
          value="{{ $uf }}"
          {{ old('ufEnderecoEvento') === $uf ? 'selected' : '' }}>
          {{ $uf }}
        </option>
        @endforeach
      </select>

      @error('ufEnderecoEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>

    <div class="span-2">
      <label class="inst-label" for="logradouroEnderecoEvento">
        Endereço
      </label>

      <input
        type="text"
        id="logradouroEnderecoEvento"
        name="logradouroEnderecoEvento"
        maxlength="150"
        class="inst-input box {{ $errors->has('logradouroEnderecoEvento') ? 'invalido' : '' }}"
        value="{{ old('logradouroEnderecoEvento') }}"
        required>

      @error('logradouroEnderecoEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>

    <div>
      <label class="inst-label" for="numeroEnderecoEvento">
        Número
      </label>

      <input
        type="text"
        id="numeroEnderecoEvento"
        name="numeroEnderecoEvento"
        maxlength="10"
        class="inst-input box {{ $errors->has('numeroEnderecoEvento') ? 'invalido' : '' }}"
        value="{{ old('numeroEnderecoEvento') }}"
        required>

      @error('numeroEnderecoEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>

    <div>
      <label class="inst-label" for="bairroEnderecoEvento">
        Bairro
      </label>

      <input
        type="text"
        id="bairroEnderecoEvento"
        name="bairroEnderecoEvento"
        maxlength="100"
        class="inst-input box {{ $errors->has('bairroEnderecoEvento') ? 'invalido' : '' }}"
        value="{{ old('bairroEnderecoEvento') }}"
        required>

      @error('bairroEnderecoEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>

    <div class="span-2">
      <label class="inst-label" for="complementoEnderecoEvento">
        Complemento (opcional)
      </label>

      <input
        type="text"
        id="complementoEnderecoEvento"
        name="complementoEnderecoEvento"
        maxlength="100"
        class="inst-input box {{ $errors->has('complementoEnderecoEvento') ? 'invalido' : '' }}"
        value="{{ old('complementoEnderecoEvento') }}">

      @error('complementoEnderecoEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>

  </div>

  <hr class="inst-form-sep">

  <h2 class="inst-form-secao">Período do evento</h2>

  <div class="inst-form-grid datas">

    <div>
      <label class="inst-label" for="dataInicioEvento">
        Data de início
      </label>

      <input
        type="date"
        id="dataInicioEvento"
        name="dataInicioEvento"
        class="inst-input box {{ $errors->has('dataInicioEvento') ? 'invalido' : '' }}"
        value="{{ old('dataInicioEvento') }}"
        required>

      @error('dataInicioEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>

    <div>
      <label class="inst-label" for="dataFimEvento">
        Data de encerramento
      </label>

      <input
        type="date"
        id="dataFimEvento"
        name="dataFimEvento"
        class="inst-input box {{ $errors->has('dataFimEvento') ? 'invalido' : '' }}"
        value="{{ old('dataFimEvento') }}">

      @error('dataFimEvento')
      <span class="inst-erro">{{ $message }}</span>
      @enderror
    </div>

  </div>

  <div class="inst-form-actions" style="margin-top:22px;">

    <a href="{{ route('evento.painel') }}"
      class="btn-form cancelar">
      Cancelar
    </a>

    <button type="submit" class="btn-form salvar">
      Enviar solicitação
    </button>

  </div>

</form>
@endsection