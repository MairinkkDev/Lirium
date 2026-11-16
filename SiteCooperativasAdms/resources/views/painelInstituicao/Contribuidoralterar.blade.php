@extends('layouts.instituicao')

@section('titulo', 'Alterar contribuidor')

@section('content')
  <a class="inst-voltar" href="{{ url('/contribuidor') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
      stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6" />
    </svg>
    Voltar para contribuidor
  </a>

  <form action="{{ url('/contribuidor/' . $contribuidor['id']) }}" method="post" class="inst-form-card" novalidate>
    @csrf
    @method('PATCH')

    <h1 class="inst-form-title">Alterar dados da entrega</h1>
    <p class="inst-form-sub">Altere o material, a quantidade ou a data da entrega</p>

    @if(session('erro'))
      <p class="aviso-erro">{{ session('erro') }}</p>
    @endif

    <p class="aviso-readonly">Nome, CPF e telefone vêm do cadastro do usuário e não podem ser alterados aqui.</p>

    <div class="inst-form-grid">
      <div class="full">
        <label class="inst-label" for="nomeContribuidor">Nome</label>
        <input type="text" id="nomeContribuidor" class="inst-input" value="{{ $contribuidor['nome'] }}" readonly>
      </div>
    </div>

    <hr class="inst-form-sep">

    <div class="inst-form-grid">
      <div>
        <label class="inst-label" for="cpfContribuidor">CPF</label>
        <input type="text" id="cpfContribuidor" class="inst-input" value="{{ $contribuidor['cpf'] }}" readonly>
      </div>

      <div>
        <label class="inst-label" for="telefoneContribuidor">Telefone</label>
        <input type="text" id="telefoneContribuidor" class="inst-input" value="{{ $contribuidor['telefone'] }}" readonly>
      </div>

      <div>
        <label class="inst-label" for="materialContribuidor">Material</label>
        <input type="text" id="materialContribuidor" name="materialContribuidor" list="listaMateriais"
          class="inst-input {{ $errors->has('materialContribuidor') ? 'invalido' : '' }}"
          placeholder="Ex: Plástico" value="{{ old('materialContribuidor', $contribuidor['material']) }}" required>
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
          placeholder="Ex: 11,5" value="{{ old('quantidadeContribuidor', $contribuidor['quantidade']) }}" required>
        @error('quantidadeContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>
    </div>

    <hr class="inst-form-sep">

    <div class="inst-form-grid">
      <div class="full">
        <label class="inst-label" for="dataEntregaContribuidor">Data de entrega</label>
        <input type="date" id="dataEntregaContribuidor" name="dataEntregaContribuidor"
          class="inst-input inst-input-data {{ $errors->has('dataEntregaContribuidor') ? 'invalido' : '' }}"
          value="{{ old('dataEntregaContribuidor', $contribuidor['entrega']) }}" required>
        @error('dataEntregaContribuidor') <span class="inst-erro">{{ $message }}</span> @enderror
      </div>
    </div>

    <div class="inst-form-actions">
      <button type="button" id="btnExcluir" class="btn-form btn-excluir-form">Excluir</button>
      <a href="{{ url('/contribuidor') }}" class="btn-form cancelar">Cancelar</a>
      <button type="submit" class="btn-form salvar">Alterar Dados</button>
    </div>
  </form>

  {{-- Modal de confirmação de exclusão --}}
  <dialog id="dlgDel">
    <form method="POST" action="{{ url('/contribuidor/' . $contribuidor['id']) }}">
      @csrf
      @method('DELETE')
      <h3>Excluir entrega</h3>
      <p class="dlg-info">Excluir a entrega de {{ $contribuidor['nome'] }} ({{ $contribuidor['material'] }})?</p>
      <p class="dlg-nota">A quantidade será descontada do estoque. Essa ação não pode ser desfeita.</p>
      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit" class="btn-perigo">Excluir</button>
      </div>
    </form>
  </dialog>

  <style>
    .inst-input[readonly] { background: #f1f1f1; color: #666; cursor: not-allowed; }
    .aviso-readonly { background: #fff6e0; color: #7a5b00; border-radius: 8px; padding: 8px 12px; font-size: 12px; margin: 0 0 14px; }
    .aviso-erro { background: #fde2e2; color: #9b1c1c; border-radius: 8px; padding: 8px 12px; font-size: 12px; margin: 0 0 14px; }
    .btn-excluir-form { background: #c62828; color: #fff; border: 0; cursor: pointer; margin-right: auto; }

    dialog { border: 0; border-radius: 12px; padding: 22px; width: 340px; box-shadow: 0 10px 40px rgba(0,0,0,.25); }
    dialog::backdrop { background: rgba(0,0,0,.45); }
    dialog h3 { margin: 0 0 14px; color: #0f3d24; }
    .dlg-info { margin: 0 0 6px; font-size: 13px; color: #222; }
    .dlg-nota { margin: 10px 0 0; font-size: 12px; color: #666; }
    .dlg-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px; }
    .dlg-actions button { padding: 8px 16px; border: 0; border-radius: 8px; cursor: pointer; background: #e5e5e5; }
    .dlg-actions button.btn-perigo { background: #c62828; color: #fff; }
  </style>

  <script>
    document.getElementById('btnExcluir').addEventListener('click', () => document.getElementById('dlgDel').showModal());
    document.querySelectorAll('[data-fechar]').forEach(b =>
      b.addEventListener('click', () => b.closest('dialog').close()));
  </script>
@endsection