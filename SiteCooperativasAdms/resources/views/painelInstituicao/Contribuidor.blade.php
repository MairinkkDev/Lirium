@extends('layouts.instituicao')

@section('titulo', 'Contribuidores')

@section('content')

  @if(session('sucesso'))
    <div class="alert-ok">{{ session('sucesso') }}</div>
  @endif
  @if(session('erro'))
    <div class="alert-erro">{{ session('erro') }}</div>
  @endif
  @if($errors->any())
    <div class="alert-erro">{{ $errors->first() }}</div>
  @endif

  <div class="inst-page-head">
    <div>
      <h1 class="page-title">Contribuidores</h1>
      <p class="page-sub">Crie, altere e encerre os contribuidores envolvidos</p>
    </div>

    <a href="{{ url('/contribuidor/criar') }}" class="btn-mini btn-cadastrar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
        <circle cx="12" cy="12" r="10" />
        <line x1="12" y1="8" x2="12" y2="16" />
        <line x1="8" y1="12" x2="16" y2="12" />
      </svg>
      Cadastrar
    </a>
  </div>

  <form action="{{ url('/contribuidor') }}" method="get" class="inst-filters">
    <label class="inst-search">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
        <circle cx="11" cy="11" r="7" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
      <input type="text" name="busca" placeholder="Buscar por nome ou CPF" value="{{ $busca }}">
    </label>

    <select name="material" class="inst-select" aria-label="Filtrar por material" onchange="this.form.submit()">
      <option value="">Material</option>
      @foreach($materiais as $m)
        <option value="{{ $m }}" {{ $material === $m ? 'selected' : '' }}>{{ $m }}</option>
      @endforeach
    </select>
  </form>

  <div class="inst-card">
    <div class="inst-table-wrap">
      <table class="inst-table contrib">
        <thead>
          <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Material</th>
            <th>Quantidade</th>
            <th>Data da entrega</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($contribuidores as $c)
            <tr>
              <td>{{ $c['nome'] }}</td>
              <td>{{ $c['cpf'] }}</td>
              <td>{{ $c['telefone'] }}</td>
              <td><span class="chip-material">{{ $c['material'] }}</span></td>
              <td>{{ number_format($c['quantidade'], 1, ',', '.') }} kg</td>
              <td>{{ $c['entrega'] }}</td>
              <td>
                <div class="acoes-linha">
                  <button type="button" class="btn-acao btn-add-qtd"
                          data-add data-id="{{ $c['id'] }}" data-nome="{{ $c['nome'] }}" data-material="{{ $c['material'] }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                      width="12" height="12">
                      <circle cx="12" cy="12" r="10" />
                      <line x1="12" y1="8" x2="12" y2="16" />
                      <line x1="8" y1="12" x2="16" y2="12" />
                    </svg>
                    Adicionar
                  </button>

                  <a href="{{ url('/contribuidor/' . $c['id'] . '/alterar') }}" class="btn-alterar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                      stroke-linejoin="round">
                      <path d="M12 20h9" />
                      <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                    </svg>
                    Alterar
                  </a>

                  <button type="button" class="btn-acao btn-excluir"
                          data-del data-id="{{ $c['id'] }}" data-nome="{{ $c['nome'] }}" data-material="{{ $c['material'] }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                      stroke-linecap="round" stroke-linejoin="round" width="12" height="12">
                      <polyline points="3 6 5 6 21 6" />
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    </svg>
                    Excluir
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="inst-empty">
                @if($busca || $material)
                  Nenhum contribuidor encontrado com esse filtro.
                @else
                  Nenhum contribuidor cadastrado. Use “Cadastrar” para começar.
                @endif
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- ===== MODAL: ADICIONAR QUANTIDADE ===== --}}
  <dialog id="dlgAddQtd">
    <form id="formAddQtd" method="POST" action="">
      @csrf
      <h3>Adicionar material</h3>
      <p class="dlg-info" id="infoAdd"></p>
      <label>Quantidade a adicionar (kg)</label>
      <input type="text" name="quantidadeContribuidor" inputmode="decimal" placeholder="Ex: 5,5" required>
      <p class="dlg-nota">O valor será somado ao que já foi depositado e ao estoque.</p>
      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit">Salvar</button>
      </div>
    </form>
  </dialog>

  {{-- ===== MODAL: CONFIRMAR EXCLUSÃO ===== --}}
  <dialog id="dlgDel">
    <form id="formDel" method="POST" action="">
      @csrf
      @method('DELETE')
      <h3>Excluir entrega</h3>
      <p class="dlg-info" id="infoDel"></p>
      <p class="dlg-nota">A quantidade será descontada do estoque. Essa ação não pode ser desfeita.</p>
      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit" class="btn-perigo">Excluir</button>
      </div>
    </form>
  </dialog>

  <style>
    .alert-ok, .alert-erro { padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 13px; }
    .alert-ok   { background: #dff5e3; color: #1b5e2b; }
    .alert-erro { background: #fde2e2; color: #9b1c1c; }

    .acoes-linha { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
    .btn-acao { display: inline-flex; align-items: center; gap: 4px; border-radius: 6px; padding: 4px 10px;
                font-size: 11px; cursor: pointer; background: #fff; }
    .btn-add-qtd { border: 1px solid #2e9e3f; color: #2e9e3f; }
    .btn-add-qtd:hover { background: #2e9e3f; color: #fff; }
    .btn-excluir { border: 1px solid #c62828; color: #c62828; }
    .btn-excluir:hover { background: #c62828; color: #fff; }

    dialog { border: 0; border-radius: 12px; padding: 22px; width: 340px; box-shadow: 0 10px 40px rgba(0,0,0,.25); }
    dialog::backdrop { background: rgba(0,0,0,.45); }
    dialog h3 { margin: 0 0 14px; color: #0f3d24; }
    dialog label { display: block; font-size: 12px; margin: 10px 0 4px; color: #444; }
    dialog input { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
    .dlg-info { margin: 0 0 6px; font-size: 13px; color: #222; }
    .dlg-nota { margin: 10px 0 0; font-size: 12px; color: #666; }
    .dlg-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px; }
    .dlg-actions button { padding: 8px 16px; border: 0; border-radius: 8px; cursor: pointer; background: #e5e5e5; }
    .dlg-actions button[type=submit] { background: #2e9e3f; color: #fff; }
    .dlg-actions button.btn-perigo { background: #c62828; color: #fff; }
  </style>

  <script>
    const base = "{{ url('/contribuidor') }}";

    document.querySelectorAll('[data-add]').forEach(b => b.addEventListener('click', () => {
      document.getElementById('formAddQtd').action = base + '/' + b.dataset.id + '/adicionar';
      document.getElementById('infoAdd').textContent = b.dataset.nome + ' — ' + b.dataset.material;
      document.getElementById('formAddQtd').reset();
      document.getElementById('dlgAddQtd').showModal();
    }));

    document.querySelectorAll('[data-del]').forEach(b => b.addEventListener('click', () => {
      document.getElementById('formDel').action = base + '/' + b.dataset.id;
      document.getElementById('infoDel').textContent =
        'Excluir a entrega de ' + b.dataset.nome + ' (' + b.dataset.material + ')?';
      document.getElementById('dlgDel').showModal();
    }));

    document.querySelectorAll('[data-fechar]').forEach(b =>
      b.addEventListener('click', () => b.closest('dialog').close()));
  </script>
@endsection