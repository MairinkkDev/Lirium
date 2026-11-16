@extends('layouts.instituicao')

@section('titulo', 'Estoque')

@section('content')

  @if(session('sucesso'))
    <div class="alert-ok">{{ session('sucesso') }}</div>
  @endif
  @if($errors->any())
    <div class="alert-erro">{{ $errors->first() }}</div>
  @endif

  <div class="page-head" style="margin-bottom:0;">
    <div>
      <h1 class="page-title">Estoque</h1>
      <p class="page-sub">Materiais reciclados e preços</p>
    </div>
  </div>

  <div class="inst-stats">
    <div class="inst-stat">
      <div class="lbl">Total em estoque</div>
      <div class="val">{{ number_format($totalEstoque, 0, ',', '.') }}<small>kg</small></div>
    </div>
    <div class="inst-stat">
      <div class="lbl">Tipos de material</div>
      <div class="val">{{ $tiposMaterial }}</div>
    </div>
    <div class="inst-stat">
      <div class="lbl">Entregas recebidas hoje</div>
      <div class="val">{{ $entregasHoje }}</div>
    </div>
  </div>

  <div class="inst-split">

    {{-- ===== Materiais recicláveis ===== --}}
    <section>
      <div class="inst-card">
        <div class="inst-card-head">
          <h2>Materiais Recicláveis</h2>
          <div class="inst-card-actions">
            <button type="button" class="btn-mini" id="btnAddEstoque">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="16" />
                <line x1="8" y1="12" x2="16" y2="12" />
              </svg>
              Adicionar
            </button>
            <button type="button" class="btn-mini" id="btnAltEstoque">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 20h9" />
                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
              </svg>
              Alterar
            </button>
            <button type="button" class="btn-mini" id="btnDelEstoque">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6" />
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                <path d="M10 11v6M14 11v6" />
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
              </svg>
              Excluir
            </button>
          </div>
        </div>
      </div>

      <div class="inst-card">
        <div class="inst-table-wrap">
          <table class="inst-table">
            <thead>
              <tr>
                <th>Material</th>
                <th class="num">Quantidade Atual</th>
                <th class="data">Última Atualização</th>
              </tr>
            </thead>
            <tbody>
              @forelse($materiais as $m)
                <tr>
                  <td>{{ $m['nome'] }}</td>
                  <td class="num">{{ number_format($m['quantidade'], 0, ',', '.') }} kg</td>
                  <td class="data">{{ $m['atualizacao'] }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="inst-empty">Nenhum material cadastrado. Use “Adicionar” para começar.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>

    {{-- ===== Preço por kg ===== --}}
    <section>
      <div class="inst-card">
        <div class="inst-card-head">
          <h2>Preço por kg</h2>
          <div class="inst-card-actions">
            <button type="button" class="btn-mini" id="btnAddPreco">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="16" />
                <line x1="8" y1="12" x2="16" y2="12" />
              </svg>
              Adicionar
            </button>
            <button type="button" class="btn-mini" id="btnAltPreco">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 20h9" />
                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
              </svg>
              Alterar
            </button>
            <button type="button" class="btn-mini" id="btnDelPreco">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6" />
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                <path d="M10 11v6M14 11v6" />
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
              </svg>
              Excluir
            </button>
          </div>
        </div>
      </div>

      <div class="inst-card">
        <div class="inst-table-wrap">
          <table class="inst-table">
            <thead>
              <tr>
                <th>Material</th>
                <th class="num">Preço por kg</th>
              </tr>
            </thead>
            <tbody>
              @forelse($listaPrecos as $p)
                <tr>
                  <td>{{ $p['nome'] }}</td>
                  <td class="num">R$ {{ number_format($p['preco'], 2, ',', '.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="2" class="inst-empty">Nenhum preço cadastrado.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>

  </div>

  {{-- Sugestões de nomes (o usuário também pode digitar um material novo) --}}
  <datalist id="listaMateriais">
    @foreach($catalogo as $c)
      <option value="{{ $c->nomeMaterialReciclavel }}"></option>
    @endforeach
  </datalist>

  {{-- ===== MODAIS: ESTOQUE ===== --}}

  <dialog id="dlgAddEstoque">
    <form method="POST" action="{{ route('estoque.adicionar') }}">
      @csrf
      <h3>Adicionar material</h3>
      <label>Material</label>
      <input type="text" name="nomeMaterial" list="listaMateriais" maxlength="100"
             placeholder="Digite ou escolha um material" value="{{ old('nomeMaterial') }}" required>
      <label>Quantidade inicial (kg)</label>
      <input type="number" name="quantidadeKg" step="0.01" min="0" value="0" required>
      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit">Salvar</button>
      </div>
    </form>
  </dialog>

  <dialog id="dlgAltEstoque">
    <form method="POST" action="{{ route('estoque.alterar') }}">
      @csrf
      @method('PATCH')
      <h3>Alterar quantidade</h3>
      <label>Material</label>
      <select name="idMaterialReciclavel" required>
        @foreach($materiais as $m)
          <option value="{{ $m['id'] }}">{{ $m['nome'] }}</option>
        @endforeach
      </select>
      <label>Nova quantidade (kg)</label>
      <input type="number" name="quantidadeKg" step="0.01" min="0" required>
      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit">Salvar</button>
      </div>
    </form>
  </dialog>

  <dialog id="dlgDelEstoque">
    <form method="POST" action="{{ route('estoque.excluir') }}"
          onsubmit="return confirm('Excluir este material do estoque? O preço dele também será removido.')">
      @csrf
      @method('DELETE')
      <h3>Excluir material</h3>
      <label>Material</label>
      <select name="idMaterialReciclavel" required>
        @foreach($materiais as $m)
          <option value="{{ $m['id'] }}">{{ $m['nome'] }}</option>
        @endforeach
      </select>
      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit" class="btn-perigo">Excluir</button>
      </div>
    </form>
  </dialog>

  {{-- ===== MODAIS: PREÇO ===== --}}

  <dialog id="dlgAddPreco">
    <form method="POST" action="{{ route('estoque.preco.adicionar') }}">
      @csrf
      <h3>Adicionar preço</h3>
      <label>Material</label>
      <input type="text" name="nomeMaterial" list="listaMateriais" maxlength="100"
             placeholder="Digite ou escolha um material" value="{{ old('nomeMaterial') }}" required>
      <label>Preço (R$ por kg)</label>
      <input type="number" name="precoKg" step="0.01" min="0" required>
      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit">Salvar</button>
      </div>
    </form>
  </dialog>

  <dialog id="dlgAltPreco">
    <form method="POST" action="{{ route('estoque.preco.alterar') }}">
      @csrf
      @method('PUT')
      <h3>Alterar preço</h3>
      <label>Material</label>
      <select name="idMaterialReciclavel" required>
        @foreach($listaPrecos as $p)
          <option value="{{ $p['id'] }}">{{ $p['nome'] }}</option>
        @endforeach
      </select>
      <label>Novo preço (R$ por kg)</label>
      <input type="number" name="precoKg" step="0.01" min="0" required>
      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit">Salvar</button>
      </div>
    </form>
  </dialog>

  <dialog id="dlgDelPreco">
    <form method="POST" action="{{ route('estoque.preco.excluir') }}"
          onsubmit="return confirm('Excluir o preço deste material?')">
      @csrf
      @method('DELETE')
      <h3>Excluir preço</h3>
      <label>Material</label>
      <select name="idMaterialReciclavel" required>
        @foreach($listaPrecos as $p)
          <option value="{{ $p['id'] }}">{{ $p['nome'] }}</option>
        @endforeach
      </select>
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

    dialog { border: 0; border-radius: 12px; padding: 22px; width: 340px; box-shadow: 0 10px 40px rgba(0,0,0,.25); }
    dialog::backdrop { background: rgba(0,0,0,.45); }
    dialog h3 { margin: 0 0 14px; color: #0f3d24; }
    dialog label { display: block; font-size: 12px; margin: 10px 0 4px; color: #444; }
    dialog select, dialog input { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
    .dlg-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px; }
    .dlg-actions button { padding: 8px 16px; border: 0; border-radius: 8px; cursor: pointer; background: #e5e5e5; }
    .dlg-actions button[type=submit] { background: #2e9e3f; color: #fff; }
    .dlg-actions button.btn-perigo { background: #c62828; color: #fff; }
  </style>

  <script>
    const abrir = (btn, dlg) =>
      document.getElementById(btn)?.addEventListener('click', () => document.getElementById(dlg).showModal());

    abrir('btnAddEstoque', 'dlgAddEstoque');
    abrir('btnAltEstoque', 'dlgAltEstoque');
    abrir('btnDelEstoque', 'dlgDelEstoque');
    abrir('btnAddPreco',   'dlgAddPreco');
    abrir('btnAltPreco',   'dlgAltPreco');
    abrir('btnDelPreco',   'dlgDelPreco');

    document.querySelectorAll('[data-fechar]').forEach(b =>
      b.addEventListener('click', () => b.closest('dialog').close()));
  </script>
@endsection