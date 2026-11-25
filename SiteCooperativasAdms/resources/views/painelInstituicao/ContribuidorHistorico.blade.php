@extends('layouts.instituicao')

@section('titulo', 'Histórico do contribuidor')

@section('content')

  <div class="historico-wrap">

    <a class="inst-voltar" href="{{ url('/contribuidor') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
        stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6" />
      </svg>
      Voltar para contribuidor
    </a>

    @if(session('sucesso'))
      <div class="alert-ok">{{ session('sucesso') }}</div>
    @endif

    @if(session('erro'))
      <div class="alert-erro">{{ session('erro') }}</div>
    @endif

    @if($errors->any())
      <div class="alert-erro">{{ $errors->first() }}</div>
    @endif

    {{-- DADOS DO CONTRIBUIDOR (centralizado) --}}
    <div class="inst-form-card hist-dados">

      <div class="hist-head">
        <h1 class="inst-form-title">Histórico de entregas</h1>
        <p class="inst-form-sub">Últimas entregas registradas por este contribuidor</p>

        <button type="button" class="btn-mini btn-cadastrar btn-registrar-grande" id="btnRegistrar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="16" />
            <line x1="8" y1="12" x2="16" y2="12" />
          </svg>
          Registrar entrega
        </button>
      </div>

      <p class="aviso-readonly">Nome, CPF e telefone não podem ser alterados.</p>

      <div class="inst-form-grid">
        <div class="full">
          <label class="inst-label" for="nomeContribuidor">Nome</label>
          <input type="text" id="nomeContribuidor" class="inst-input" value="{{ $pessoa['nome'] }}" readonly>
        </div>

        <div>
          <label class="inst-label" for="cpfContribuidor">CPF</label>
          <input type="text" id="cpfContribuidor" class="inst-input" value="{{ $pessoa['cpf'] }}" readonly>
        </div>

        <div>
          <label class="inst-label" for="telefoneContribuidor">Telefone</label>
          <input type="text" id="telefoneContribuidor" class="inst-input" value="{{ $pessoa['telefone'] }}" readonly>
        </div>
      </div>

      <hr class="inst-form-sep">

      {{-- RESUMO --}}
      <div class="hist-resumo">

        <div class="hist-stat">
          <div class="lbl">Total entregue</div>
          <div class="val">{{ number_format($totalKg, 1, ',', '.') }} <small>kg</small></div>
        </div>

        <div class="hist-stat">
          <div class="lbl">Entregas</div>
          <div class="val">{{ number_format($totalEntregas, 0, ',', '.') }}</div>
        </div>

      </div>

    </div>

    {{-- TABELA DE ENTREGAS --}}
    <div class="inst-card hist-card">

      <div class="inst-table-wrap">

        <table class="inst-table hist">

          <thead>
            <tr>
              <th>Material</th>
              <th>Quantidade</th>
              <th>Data da entrega</th>
              <th>Ações</th>
            </tr>
          </thead>

          <tbody>

            @foreach($entregas as $e)

              <tr>
                <td>
                  <span class="chip-material">
                    {{ $e->material->nomeMaterialReciclavel ?? '-' }}
                  </span>
                </td>

                <td>{{ number_format((float) $e->quantidadeKg, 1, ',', '.') }} kg</td>

                <td>{{ $e->created_at ? $e->created_at->format('d/m/Y') : '-' }}</td>

                <td>
                  <div class="acoes-linha">

                    {{-- ADICIONAR QUANTIDADE --}}
                    <button
                      type="button"
                      class="btn-acao btn-add-qtd"
                      data-add
                      data-id="{{ $e->idContribuicaoUsuario }}"
                      data-nome="{{ $pessoa['nome'] }}"
                      data-material="{{ $e->material->nomeMaterialReciclavel ?? '-' }}"
                    >
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"
                        stroke-linecap="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="16" />
                        <line x1="8" y1="12" x2="16" y2="12" />
                      </svg>
                      Adicionar
                    </button>

                    {{-- ALTERAR --}}
                    <button
                      type="button"
                      class="btn-acao btn-alterar"
                      data-alt
                      data-id="{{ $e->idContribuicaoUsuario }}"
                      data-nome="{{ $pessoa['nome'] }}"
                      data-material="{{ $e->material->nomeMaterialReciclavel ?? '-' }}"
                      data-qtd="{{ str_replace('.', ',', (string) (float) $e->quantidadeKg) }}"
                    >
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                      </svg>
                      Alterar
                    </button>

                    {{-- EXCLUIR --}}
                    <button
                      type="button"
                      class="btn-acao btn-excluir"
                      data-del
                      data-id="{{ $e->idContribuicaoUsuario }}"
                      data-nome="{{ $pessoa['nome'] }}"
                      data-material="{{ $e->material->nomeMaterialReciclavel ?? '-' }}"
                      data-qtd="{{ number_format((float) $e->quantidadeKg, 1, ',', '.') }}"
                      data-data="{{ $e->created_at ? $e->created_at->format('d/m/Y') : '-' }}"
                    >
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                      </svg>
                      Excluir
                    </button>

                  </div>
                </td>
              </tr>

            @endforeach

          </tbody>

        </table>

      </div>

    </div>

    {{-- PAGINAÇÃO --}}
    @if($entregas->hasPages())
      <div style="margin-top:4px;">
        {{ $entregas->links() }}
      </div>
    @endif

  </div>

  {{-- MODAL: REGISTRAR ENTREGA (só materiais que a instituição tem no estoque) --}}
  <dialog id="dlgRegistrar">
    <form method="POST" action="{{ url('/contribuidor/' . $pessoa['id'] . '/entregas') }}">
      @csrf
      <input type="hidden" name="_form" value="registrar">

      <h3>Registrar entrega</h3>
      <p class="dlg-info">{{ $pessoa['nome'] }}</p>

      <label>Material</label>
      @if($materiais->isEmpty())
        <p class="dlg-nota">Nenhum material cadastrado no estoque da instituição.</p>
      @else
        <select name="idMaterialReciclavel" required>
          <option value="" disabled {{ old('idMaterialReciclavel') ? '' : 'selected' }}>Escolha um material</option>
          @foreach($materiais as $m)
            <option value="{{ $m->idMaterialReciclavel }}"
              {{ (string) old('idMaterialReciclavel') === (string) $m->idMaterialReciclavel ? 'selected' : '' }}>
              {{ $m->nomeMaterialReciclavel }}
            </option>
          @endforeach
        </select>
      @endif

      <label>Quantidade (kg)</label>
      <input type="text" name="quantidadeContribuidor" inputmode="decimal" placeholder="Ex: 5,5"
        value="{{ old('quantidadeContribuidor') }}" required>

      <p class="dlg-nota">A entrega é registrada com a data de hoje e soma ao estoque.</p>

      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit" {{ $materiais->isEmpty() ? 'disabled' : '' }}>Salvar</button>
      </div>
    </form>
  </dialog>

  {{-- MODAL: ADICIONAR QUANTIDADE --}}
  <dialog id="dlgAddQtd">
    <form id="formAddQtd" method="POST" action="">
      @csrf
      <h3>Adicionar material</h3>
      <p class="dlg-info" id="infoAdd"></p>

      <label>Quantidade a adicionar (kg)</label>
      <input type="text" name="quantidadeContribuidor" inputmode="decimal" placeholder="Ex: 5,5" required>

      <p class="dlg-nota">O valor será somado a esta entrega e ao estoque.</p>

      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit">Salvar</button>
      </div>
    </form>
  </dialog>

  {{-- MODAL: ALTERAR QUANTIDADE (só a quantidade do material escolhido) --}}
  <dialog id="dlgAltEntrega">
    <form id="formAltEntrega" method="POST" action="">
      @csrf
      @method('PATCH')

      <h3>Alterar quantidade</h3>
      <p class="dlg-info" id="infoAlt"></p>

      <label>Nova quantidade (kg)</label>
      <input type="text" name="quantidadeContribuidor" id="altQtd" inputmode="decimal" placeholder="Ex: 5,5" required>

      <p class="dlg-nota">O estoque será ajustado com a diferença.</p>

      <div class="dlg-actions">
        <button type="button" data-fechar>Cancelar</button>
        <button type="submit">Salvar</button>
      </div>
    </form>
  </dialog>

  {{-- MODAL: CONFIRMAR EXCLUSÃO --}}
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
    /* LINK DE VOLTAR (maior) */

    .inst-voltar {
      font-size: 18px;
      font-weight: 600;
      gap: 8px;
    }

    .inst-voltar svg {
      width: 22px;
      height: 22px;
    }

    /* A área principal do layout tem max-width e fica à esquerda; aqui ela
       ocupa toda a largura e o conteúdo é centralizado na página. */
    .main { max-width: none; }

    .historico-wrap {
      display: flex;
      flex-direction: column;
      gap: 18px;
      width: 100%;
      max-width: 960px;
      margin: 0 auto;
      min-width: 0;
    }

    .historico-wrap .inst-voltar {
      align-self: flex-start;
      margin-bottom: 0;
    }

    .historico-wrap * { box-sizing: border-box; }

    .alert-ok, .alert-erro { padding: 12px 16px; border-radius: 8px; font-size: 15px; }
    .alert-ok { background: #dff5e3; color: #1b5e2b; }
    .alert-erro { background: #fde2e2; color: #9b1c1c; }

    /* DADOS DO CONTRIBUIDOR: centralizado e com fonte maior */

    .historico-wrap .hist-dados {
      width: 100%;
      max-width: none;
      margin: 0;
      text-align: center;
      position: relative;
      box-shadow: none;
      border: 1px solid #d0d0d0;
      border-radius: 8px;
    }

    .hist-head {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      gap: 4px;
      margin-bottom: 14px;
    }

    .hist-dados .inst-form-title { font-size: 24px; margin: 0; text-align: center; }
    .hist-dados .inst-form-sub { font-size: 14px; margin: 0 0 6px; text-align: center; }

    .aviso-readonly {
      background: #fff6e0;
      color: #7a5b00;
      border-radius: 8px;
      padding: 8px 12px;
      font-size: 13px;
      margin: 0 0 14px;
      text-align: center;
    }

    .hist-dados .inst-label { font-size: 13px; font-weight: 600; text-align: center; display: block; }

    .hist-dados .inst-input {
      font-size: 15px;
      text-align: center;
      padding: 9px 12px;
    }

    .inst-input[readonly] { background: #f1f1f1; color: #555; cursor: not-allowed; }

    /* BOTÃO REGISTRAR ENTREGA (maior) */

    .historico-wrap .btn-registrar-grande {
      position: absolute;
      top: 35px;
      right: 14px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 40px;
      cursor: pointer;
      margin: 0;
    }

    .historico-wrap .btn-registrar-grande svg { width: 16px; height: 16px; flex-shrink: 0; }

    /* RESUMO */

    .hist-resumo {
      display: flex;
      justify-content: center;
      gap: 16px;
      margin-bottom: 6px;
    }

    .hist-stat {
      flex: 0 1 220px;
      text-align: center;
      padding: 12px 16px;
      border: 1px solid #d0d0d0;
      border-radius: 8px;
    }

    .hist-stat .lbl { font-size: 13px; color: #555; }
    .hist-stat .val { font-size: 26px; font-weight: 700; color: #0f3d24; }
    .hist-stat .val small { font-size: 14px; font-weight: 600; }

    .chip-material {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 5px;
      white-space: nowrap;
      font-size: inherit;
    }

    /* TABELA (tudo em uma linha, sem rolagem) */

    .historico-wrap .hist-card {
      box-shadow: none;
      border: 1px solid #d0d0d0;
      border-radius: 8px;
      margin: 0;
      overflow: hidden;
      container-type: inline-size;
      container-name: tabela;
      width: 100%;
      min-width: 0;
    }

    .historico-wrap .inst-table-wrap {
      display: block;
      width: 100%;
      overflow: visible !important;
    }

    .historico-wrap .inst-table {
      width: 100% !important;
      table-layout: fixed;
      border-collapse: collapse;
      margin: 0;
    }

    .historico-wrap .inst-table th {
      font-size: clamp(13px, 2cqw, 18px);
      font-weight: 700;
      padding: 18px 8px;
      text-align: center;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .historico-wrap .inst-table td {
      font-size: clamp(13px, 1.9cqw, 17px);
      font-weight: 500;
      padding: 16px 8px;
      text-align: center;
      vertical-align: middle;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .historico-wrap .inst-table th:nth-child(1),
    .historico-wrap .inst-table td:nth-child(1) { width: 24%; text-align: left; padding-left: 14px; }

    .historico-wrap .inst-table th:nth-child(2),
    .historico-wrap .inst-table td:nth-child(2) { width: 18%; }

    .historico-wrap .inst-table th:nth-child(3),
    .historico-wrap .inst-table td:nth-child(3) { width: 20%; }

    .historico-wrap .inst-table th:nth-child(4),
    .historico-wrap .inst-table td:nth-child(4) { width: 38%; overflow: visible; }

    /* BOTÕES DE AÇÃO */

    .acoes-linha {
      display: flex;
      gap: 8px;
      align-items: center;
      justify-content: center;
      flex-wrap: nowrap;
      white-space: nowrap;
    }

    .btn-acao {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      border-radius: 6px;
      padding: 8px 11px;
      font-size: clamp(12px, 1.7cqw, 14px);
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
      text-decoration: none;
      background: #fff;
    }

    .btn-acao svg { width: 15px; height: 15px; flex-shrink: 0; }

    .btn-add-qtd { border: 1px solid #2e9e3f; color: #2e9e3f; }
    .btn-add-qtd:hover { background: #2e9e3f; color: #fff; }

    .btn-alterar { border: 1px solid #d6a000; color: #9a7200; }
    .btn-alterar:hover { background: #d6a000; color: #fff; }

    .btn-excluir { border: 1px solid #c62828; color: #c62828; }
    .btn-excluir:hover { background: #c62828; color: #fff; }

    /* Tabela estreita: botões só com ícone */
    @container tabela (max-width: 760px) {
      .historico-wrap .acoes-linha .btn-acao { font-size: 0; gap: 0; padding: 8px; }
      .historico-wrap .acoes-linha .btn-acao svg { width: 16px; height: 16px; }
    }

    /* MODAIS */

    dialog { border: 0; border-radius: 12px; padding: 24px; width: 380px; max-width: calc(100% - 30px); box-shadow: 0 10px 40px rgba(0,0,0,.25); }
    dialog::backdrop { background: rgba(0,0,0,.45); }
    dialog h3 { margin: 0 0 14px; color: #0f3d24; font-size: 20px; }
    dialog label { display: block; font-size: 14px; margin: 12px 0 4px; color: #444; }
    dialog input { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; font-size: 15px; }
    dialog select { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; font-size: 15px; background: #fff; }
    .dlg-info { margin: 0 0 6px; font-size: 15px; color: #222; }
    .dlg-nota { margin: 12px 0 0; font-size: 13px; color: #666; }
    .dlg-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
    .dlg-actions button { padding: 9px 18px; border: 0; border-radius: 8px; cursor: pointer; background: #e5e5e5; font-size: 14px; }
    .dlg-actions button[type="submit"] { background: #2e9e3f; color: #fff; }
    .dlg-actions button[type="submit"]:disabled { background: #b5b5b5; cursor: not-allowed; }
    .dlg-actions button.btn-perigo { background: #c62828; color: #fff; }

    @media (max-width: 700px) {
      .historico-wrap .btn-registrar-grande {
        position: static;
        margin: 8px auto 0;
      }
    }

    @media (max-width: 600px) {
      .hist-stat { flex: 1 1 100%; }
      .hist-resumo { flex-wrap: wrap; }
      .hist-dados .inst-form-title { font-size: 22px; }
    }
  </style>

  <script>
    const base = "{{ url('/contribuidor') }}";

    // REGISTRAR ENTREGA
    document.getElementById('btnRegistrar').addEventListener('click', () =>
      document.getElementById('dlgRegistrar').showModal()
    );

    @if(old('_form') === 'registrar')
      // reabre o modal se a validação falhou
      document.getElementById('dlgRegistrar').showModal();
    @endif

    // ADICIONAR QUANTIDADE
    document.querySelectorAll('[data-add]').forEach(b =>
      b.addEventListener('click', () => {
        document.getElementById('formAddQtd').reset();
        document.getElementById('formAddQtd').action = base + '/' + b.dataset.id + '/adicionar';
        document.getElementById('infoAdd').textContent = b.dataset.nome + ' — ' + b.dataset.material;
        document.getElementById('dlgAddQtd').showModal();
      })
    );

    // ALTERAR ENTREGA
    document.querySelectorAll('[data-alt]').forEach(b =>
      b.addEventListener('click', () => {
        document.getElementById('formAltEntrega').action = base + '/' + b.dataset.id + '/quantidade';
        document.getElementById('infoAlt').textContent = b.dataset.nome + ' — ' + b.dataset.material;
        document.getElementById('altQtd').value = b.dataset.qtd;
        document.getElementById('dlgAltEntrega').showModal();
      })
    );

    // EXCLUIR ENTREGA
    document.querySelectorAll('[data-del]').forEach(b =>
      b.addEventListener('click', () => {
        document.getElementById('formDel').action = base + '/' + b.dataset.id;
        document.getElementById('infoDel').textContent =
          'Excluir a entrega de ' + b.dataset.nome + ' (' + b.dataset.material + ', ' +
          b.dataset.qtd + ' kg, em ' + b.dataset.data + ')?';
        document.getElementById('dlgDel').showModal();
      })
    );

    // FECHAR MODAIS
    document.querySelectorAll('[data-fechar]').forEach(b =>
      b.addEventListener('click', () => b.closest('dialog').close())
    );
  </script>

@endsection