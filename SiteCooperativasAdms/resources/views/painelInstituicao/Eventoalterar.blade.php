@extends('layouts.instituicao')

@section('titulo', 'Alterar evento')

@push('estilos')
<style>
  /* Texto digitado bem visível */
  .inst-form-card .inst-input {
    color: #111d14 !important;            /* texto quase preto */
    font-size: 15px !important;
    font-weight: 500;
    background: #fff !important;
    border: 1.5px solid #b9c9be !important;
    caret-color: #1f8a4c;                 /* cursor verde, fácil de achar */
  }

  /* Placeholder mais claro que o texto digitado, para diferenciar */
  .inst-form-card .inst-input::placeholder {
    color: #8fa095 !important;
    font-weight: 400;
  }

  /* Campo em foco bem destacado enquanto escreve */
  .inst-form-card .inst-input:focus {
    outline: none !important;
    border-color: #1f8a4c !important;
    box-shadow: 0 0 0 4px rgba(31, 138, 76, .18) !important;
  }

  /* Rótulos mais legíveis */
  .inst-form-card .inst-label {
    color: #1d3a27;
    font-weight: 700;
  }

  /* Campo com erro */
  .inst-form-card .inst-input.invalido {
    border-color: #dc2626 !important;
  }
</style>
@endpush

@section('content')
  {{-- Recebe $evento (model Evento) do EventoController@detalhe.
       Se abrir a tela sem controller (só para ver o visual), usa dados de exemplo. --}}
  @php
    if (($evento ?? null) instanceof \App\Models\Evento) {
      $evento = [
        'id'          => $evento->idEvento,
        'nome'        => $evento->nomeEvento,
        'descricao'   => $evento->descricaoEvento,
        'cep'         => $evento->cepEnderecoEvento,
        'cidade'      => $evento->cidadeEnderecoEvento,
        'uf'          => $evento->ufEnderecoEvento,
        'logradouro'  => $evento->logradouroEnderecoEvento,
        'numero'      => $evento->numeroEnderecoEvento,
        'bairro'      => $evento->bairroEnderecoEvento,
        'complemento' => $evento->complementoEnderecoEvento,
        'inicio'      => optional($evento->dataInicioEvento)->format('Y-m-d'),
        'fim'         => optional($evento->dataFimEvento)->format('Y-m-d'),
        'status'      => $evento->statusEvento,
      ];
    } else {
      $evento = [
        'id'          => 1,
        'nome'        => 'Mutirão de coleta de eletrônicos...',
        'descricao'   => 'Traga aparelhos antigos para descarte correto...',
        'cep'         => '08400-147',
        'cidade'      => 'São Paulo',
        'uf'          => 'SP',
        'logradouro'  => 'Rua do retorno das laranjeiras',
        'numero'      => '54',
        'bairro'      => '',
        'complemento' => '',
        'inicio'      => '2026-10-05',
        'fim'         => '2026-10-30',
        'status'      => 'em_andamento',
      ];
    }
    $ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
    $podeEncerrar = in_array($evento['status'], ['agendado', 'em_andamento'], true);
  @endphp

  <a class="inst-voltar" href="{{ route('evento.painel') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
      stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6" />
    </svg>
    Voltar para eventos
  </a>

  <div class="inst-form-layout">

    <form action="{{ route('evento.atualizar', $evento['id']) }}" method="post" class="inst-form-card" novalidate>
      @csrf
      @method('PATCH')
      {{-- O controller exige statusEvento; como a tela não tem seletor de status, mantém o atual --}}
      <input type="hidden" name="statusEvento" value="{{ old('statusEvento', $evento['status']) }}">
      {{-- Bairro e complemento não aparecem no design, mas o controller exige o bairro: mantém o valor atual --}}
      <input type="hidden" name="bairroEnderecoEvento" value="{{ old('bairroEnderecoEvento', $evento['bairro']) }}">
      <input type="hidden" name="complementoEnderecoEvento" value="{{ old('complementoEnderecoEvento', $evento['complemento']) }}">

      <h1 class="inst-form-title">Alterar evento</h1>
      <p class="inst-form-sub">As alterações aparecem para os usuários do app assim que forem salvas.</p>

      <div class="inst-form-grid">
        <div class="full">
          <label class="inst-label" for="nomeEvento">Título</label>
          <input type="text" id="nomeEvento" name="nomeEvento" maxlength="150"
            class="inst-input {{ $errors->has('nomeEvento') ? 'invalido' : '' }}"
            placeholder="Ex: Mutirão de coleta de eletrônicos..." value="{{ old('nomeEvento', $evento['nome']) }}"
            required>
          @error('nomeEvento') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>
      </div>

      <hr class="inst-form-sep">

      <div class="inst-form-grid">
        <div class="full">
          <label class="inst-label" for="descricaoEvento">Descrição</label>
          <textarea id="descricaoEvento" name="descricaoEvento"
            class="inst-input inst-textarea {{ $errors->has('descricaoEvento') ? 'invalido' : '' }}"
            placeholder="Ex: Traga aparelhos antigos para descarte correto...">{{ old('descricaoEvento', $evento['descricao']) }}</textarea>
          @error('descricaoEvento') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>
      </div>

      <hr class="inst-form-sep">

      <h2 class="inst-form-secao">Endereço</h2>

      <div class="inst-form-grid endereco">
        <div>
          <label class="inst-label" for="cepEnderecoEvento">CEP</label>
          <input type="text" id="cepEnderecoEvento" name="cepEnderecoEvento" inputmode="numeric" maxlength="9"
            class="inst-input box {{ $errors->has('cepEnderecoEvento') ? 'invalido' : '' }}"
            value="{{ old('cepEnderecoEvento', $evento['cep']) }}" required>
          @error('cepEnderecoEvento') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="inst-label" for="cidadeEnderecoEvento">Cidade</label>
          <input type="text" id="cidadeEnderecoEvento" name="cidadeEnderecoEvento" maxlength="100"
            class="inst-input box {{ $errors->has('cidadeEnderecoEvento') ? 'invalido' : '' }}"
            value="{{ old('cidadeEnderecoEvento', $evento['cidade']) }}" required>
          @error('cidadeEnderecoEvento') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="inst-label" for="ufEnderecoEvento">UF</label>
          <select id="ufEnderecoEvento" name="ufEnderecoEvento"
            class="inst-input box select {{ $errors->has('ufEnderecoEvento') ? 'invalido' : '' }}" required>
            <option value="" hidden></option>
            @foreach($ufs as $uf)
              <option value="{{ $uf }}" {{ old('ufEnderecoEvento', $evento['uf']) === $uf ? 'selected' : '' }}>{{ $uf }}</option>
            @endforeach
          </select>
          @error('ufEnderecoEvento') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>

        <div class="span-2">
          <label class="inst-label" for="logradouroEnderecoEvento">Endereço</label>
          <input type="text" id="logradouroEnderecoEvento" name="logradouroEnderecoEvento" maxlength="150"
            class="inst-input box {{ $errors->has('logradouroEnderecoEvento') ? 'invalido' : '' }}"
            value="{{ old('logradouroEnderecoEvento', $evento['logradouro']) }}" required>
          @error('logradouroEnderecoEvento') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="inst-label" for="numeroEnderecoEvento">Número</label>
          <input type="text" id="numeroEnderecoEvento" name="numeroEnderecoEvento" maxlength="10"
            class="inst-input box {{ $errors->has('numeroEnderecoEvento') ? 'invalido' : '' }}"
            value="{{ old('numeroEnderecoEvento', $evento['numero']) }}" required>
          @error('numeroEnderecoEvento') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>
      </div>

      <hr class="inst-form-sep">

      <div class="inst-form-grid datas">
        <div>
          <label class="inst-label" for="dataInicioEvento">Data de início</label>
          <input type="date" id="dataInicioEvento" name="dataInicioEvento"
            class="inst-input box {{ $errors->has('dataInicioEvento') ? 'invalido' : '' }}"
            value="{{ old('dataInicioEvento', $evento['inicio']) }}" required>
          @error('dataInicioEvento') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="inst-label" for="dataFimEvento">Data de encerramento</label>
          <input type="date" id="dataFimEvento" name="dataFimEvento"
            class="inst-input box {{ $errors->has('dataFimEvento') ? 'invalido' : '' }}"
            value="{{ old('dataFimEvento', $evento['fim']) }}">
          @error('dataFimEvento') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="inst-form-actions" style="margin-top:22px;">
        <a href="{{ route('evento.painel') }}" class="btn-form cancelar">Cancelar</a>
        <button type="submit" class="btn-form salvar">Salvar alterações</button>
      </div>
    </form>

    @if($podeEncerrar)
      <aside class="inst-aviso-perigo">
        <h2>Encerrar Evento</h2>
        <form action="{{ route('evento.encerrar', $evento['id']) }}" method="post"
          class="inst-aviso-perigo-corpo"
          data-confirmar="Encerrar o evento {{ $evento['nome'] }}? Essa ação não pode ser desfeita.">
          @csrf
          @method('PATCH')
          <p>O evento deixa de aparecer como ativo e os usuários passam a poder avaliá-lo. Essa ação não pode ser desfeita.</p>
          <button type="submit" class="btn-perigo">Encerrar</button>
        </form>
      </aside>
    @endif

  </div>
@endsection