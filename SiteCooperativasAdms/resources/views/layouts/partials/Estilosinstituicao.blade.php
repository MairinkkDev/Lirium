{{-- Estilos exclusivos do CONTEÚDO das telas da INSTITUIÇÃO.
     Sidebar, header e área principal usam o mesmo estilo do ADM (partial estilos).
     Tudo fica escopado em body.inst, então nenhuma tela do ADM é afetada. --}}
<style>
  body.inst {
    --inst-grad: linear-gradient(90deg, #1f8a4c 0%, #4cae4f 100%);
    --inst-card-shadow: 0 4px 10px rgba(15, 40, 25, .12);
  }

  /* ============ CABEÇALHO DA PÁGINA ============ */
  body.inst .page-title {
    font-size: 26px;
    font-weight: 700;
  }

  body.inst .page-sub {
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 500;
  }

  /* ============ CARDS DE RESUMO ============ */
  .inst-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 22px;
    max-width: 640px;
    margin: 22px auto 28px;
  }

  .inst-stat {
    background: var(--white);
    border-radius: 10px;
    padding: 14px 18px 16px;
    box-shadow: var(--inst-card-shadow);
  }

  .inst-stat .lbl {
    font-size: 10.5px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 6px;
  }

  .inst-stat .val {
    font-size: 26px;
    font-weight: 700;
    color: var(--text-dark);
    line-height: 1.1;
  }

  .inst-stat .val small {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    margin-left: 2px;
  }

  /* ============ PAINÉIS DE TABELA (2 colunas) ============ */
  .inst-split {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 28px;
    align-items: start;
  }

  .inst-card {
    background: var(--white);
    border-radius: 10px;
    box-shadow: var(--inst-card-shadow);
  }

  .inst-card+.inst-card {
    margin-top: 16px;
  }

  .inst-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 20px;
  }

  .inst-card-head h2 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
  }

  .inst-card-actions {
    display: flex;
    gap: 10px;
  }

  /* Botões pequenos verdes: Adicionar / Alterar */
  .btn-mini {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border: none;
    border-radius: 999px;
    background: var(--inst-grad);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: filter .15s;
  }

  .btn-mini:hover {
    filter: brightness(1.1);
  }

  .btn-mini:focus-visible {
    outline: 2px solid var(--green-2);
    outline-offset: 2px;
  }

  .btn-mini svg {
    width: 11px;
    height: 11px;
  }

  /* Tabela */
  .inst-table-wrap {
    padding: 8px 20px 14px;
    overflow-x: auto;
  }

  .inst-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
  }

  .inst-table th {
    font-weight: 500;
    font-size: 12px;
    color: var(--text-dark);
    text-align: left;
    padding: 12px 0 14px;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }

  .inst-table td {
    font-weight: 700;
    color: var(--text-dark);
    padding: 11px 0;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }

  .inst-table tr:last-child td {
    border-bottom: none;
  }

  .inst-table .num {
    text-align: right;
  }

  .inst-table td.num {
    font-weight: 700;
  }

  .inst-table td.data {
    font-weight: 500;
    text-align: right;
    padding-left: 20px;
  }

  .inst-table th.data {
    text-align: right;
    padding-left: 20px;
  }

  .inst-empty {
    text-align: center;
    color: var(--text-muted);
    padding: 32px 0 !important;
    font-weight: 500 !important;
  }


  /* ============ CONTRIBUIDORES ============ */
  .inst-page-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
  }

  .btn-cadastrar {
    padding: 8px 18px;
    font-size: 11px;
    gap: 7px;
    box-shadow: 0 3px 8px rgba(15, 40, 25, .18);
  }

  .btn-cadastrar svg {
    width: 15px;
    height: 15px;
  }

  .inst-filters {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    margin: 22px 0 18px;
  }

  .inst-search {
    display: flex;
    align-items: center;
    gap: 9px;
    width: 280px;
    max-width: 100%;
    background: var(--white);
    border: 1px solid var(--text-dark);
    border-radius: 999px;
    padding: 7px 16px;
    color: var(--text-dark);
  }

  .inst-search svg {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
  }

  .inst-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-family: 'Raleway', sans-serif;
    font-size: 11px;
    font-weight: 500;
    color: var(--text-dark);
  }

  .inst-search input::placeholder {
    color: var(--text-muted);
  }

  .inst-search:focus-within {
    border-color: var(--green-1);
    box-shadow: 0 0 0 2px rgba(31, 138, 76, .18);
  }

  .inst-select {
    appearance: none;
    -webkit-appearance: none;
    background: var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2316241d' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;
    border: 1px solid var(--text-dark);
    border-radius: 8px;
    padding: 6px 30px 6px 14px;
    font-family: 'Raleway', sans-serif;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-dark);
    cursor: pointer;
  }

  .inst-select:focus-visible {
    outline: 2px solid var(--green-1);
    outline-offset: 2px;
  }

  .inst-table.contrib th,
  .inst-table.contrib td {
    text-align: center;
    padding-left: 10px;
    padding-right: 10px;
  }

  .inst-table.contrib th:first-child,
  .inst-table.contrib td:first-child {
    text-align: left;
    padding-left: 0;
  }

  .inst-table.contrib td {
    font-size: 11px;
  }

  .inst-table.contrib td.fraco {
    font-weight: 600;
  }

  .chip-material {
    display: inline-block;
    background: #e6e9e7;
    color: var(--text-muted);
    border-radius: 999px;
    padding: 2px 9px;
    font-size: 8.5px;
    font-weight: 600;
    white-space: nowrap;
  }

  .btn-alterar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-width: 76px;
    padding: 4px 12px;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 999px;
    box-shadow: 0 2px 5px rgba(15, 40, 25, .18);
    font-family: 'Raleway', sans-serif;
    font-size: 8.5px;
    font-weight: 700;
    color: var(--text-dark);
    cursor: pointer;
    text-decoration: none;
    transition: border-color .15s, color .15s;
  }

  .btn-alterar:hover {
    border-color: var(--green-1);
    color: var(--green-2);
  }

  .btn-alterar:focus-visible {
    outline: 2px solid var(--green-1);
    outline-offset: 2px;
  }

  .btn-alterar svg {
    width: 10px;
    height: 10px;
  }


  /* ============ EVENTOS ============ */
  .inst-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 22px;
    margin: 26px 0 18px;
  }

  .inst-tab {
    min-width: 112px;
    padding: 7px 18px;
    text-align: center;
    background: var(--white);
    border-radius: 8px;
    box-shadow: var(--inst-card-shadow);
    font-family: 'Raleway', sans-serif;
    font-size: 10.5px;
    font-weight: 600;
    color: var(--text-dark);
    text-decoration: none;
    transition: filter .15s;
  }

  .inst-tab:hover {
    filter: brightness(.96);
  }

  .inst-tab.active {
    background: var(--inst-grad);
    color: #fff;
  }

  .inst-tab:focus-visible {
    outline: 2px solid var(--green-1);
    outline-offset: 2px;
  }

  .inst-search.sm {
    width: 190px;
    padding: 4px 14px;
    border-color: var(--text-dark);
  }

  .inst-search.sm input {
    text-align: center;
    font-size: 9px;
  }

  .inst-table.eventos {
    min-width: 640px;
  }

  .inst-table.eventos th,
  .inst-table.eventos td {
    text-align: center;
    padding-left: 10px;
    padding-right: 10px;
    font-size: 11px;
  }

  .inst-table.eventos th:first-child,
  .inst-table.eventos td:first-child {
    text-align: left;
    padding-left: 0;
  }

  .inst-table.eventos td {
    padding-top: 9px;
    padding-bottom: 9px;
  }

  /* Status do evento */
  .chip-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border-radius: 999px;
    padding: 2px 9px;
    font-size: 8.5px;
    font-weight: 600;
    white-space: nowrap;
  }

  .chip-status::before {
    content: '';
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: currentColor;
  }

  .chip-status.andamento {
    background: #e3f5e8;
    color: #1f8a4c;
  }

  .chip-status.agendado {
    background: #e6effc;
    color: #2563c9;
  }

  .chip-status.encerrado {
    background: #e6e9e7;
    color: var(--text-muted);
  }

  .btn-encerrar {
    color: #d33a3a;
  }

  .btn-encerrar:hover {
    border-color: #d33a3a;
    color: #d33a3a;
  }

  .btn-encerrar svg {
    fill: currentColor;
    stroke: none;
  }

  .nota-evento {
    font-weight: 700;
    font-size: 9px;
  }


  /* ============ FORMULÁRIO (cadastrar / alterar) ============ */
  .inst-voltar {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 14px;
    font-size: 11px;
    font-weight: 700;
    color: var(--green-2);
    text-decoration: none;
  }

  .inst-voltar svg {
    width: 12px;
    height: 12px;
  }

  .inst-voltar:hover {
    text-decoration: underline;
  }

  .inst-form-card {
    max-width: 560px;
    padding: 24px 26px 22px;
    background: var(--white);
    border-radius: 12px;
    box-shadow: var(--inst-card-shadow);
  }

  .inst-form-title {
    margin: 0 0 4px;
    font-size: 26px;
    font-weight: 500;
    color: var(--text-dark);
  }

  .inst-form-sub {
    margin: 0 0 20px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-light);
  }

  .inst-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px 28px;
  }

  .inst-form-grid .full {
    grid-column: 1 / -1;
  }

  .inst-form-sep {
    border: none;
    border-top: 1px solid var(--border);
    margin: 18px -26px;
  }

  .inst-label {
    display: block;
    margin-bottom: 6px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-dark);
  }

  .inst-input {
    width: 100%;
    padding: 7px 14px;
    background: var(--white);
    border: 1px solid #b9c3bd;
    border-radius: 999px;
    font-family: 'Raleway', sans-serif;
    font-size: 10.5px;
    font-weight: 500;
    color: var(--text-dark);
    outline: none;
    transition: border-color .15s, box-shadow .15s;
  }

  .inst-input::placeholder {
    color: var(--text-muted);
  }

  .inst-input:focus {
    border-color: var(--green-1);
    box-shadow: 0 0 0 2px rgba(31, 138, 76, .18);
  }

  .inst-input.invalido {
    border-color: #c73f3f;
  }

  .inst-input-data {
    max-width: 150px;
  }

  .inst-erro {
    display: block;
    margin-top: 5px;
    padding-left: 6px;
    font-size: 10.5px;
    font-weight: 600;
    color: #c73f3f;
  }

  .inst-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 14px;
    margin-top: 6px;
  }

  .btn-form {
    min-width: 90px;
    padding: 8px 18px;
    border: none;
    border-radius: 8px;
    font-family: 'Raleway', sans-serif;
    font-size: 10px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: filter .15s;
  }

  .btn-form:focus-visible {
    outline: 2px solid var(--green-2);
    outline-offset: 2px;
  }

  .btn-form.cancelar {
    background: var(--white);
    color: var(--text-dark);
    box-shadow: 0 2px 6px rgba(15, 40, 25, .22);
  }

  .btn-form.salvar {
    background: #3f9a41;
    color: #fff;
  }

  .btn-form:hover {
    filter: brightness(.95);
  }

  @media (max-width: 600px) {
    .inst-form-grid {
      grid-template-columns: 1fr;
    }
  }


  /* Variações de campo usadas em formulários maiores (evento) */
  .inst-input.box {
    border-radius: 6px;
    padding: 8px 10px;
  }

  .inst-input.select {
    appearance: none;
    -webkit-appearance: none;
    padding-right: 24px;
    cursor: pointer;
    background: var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%236b7871' stroke-width='2.6' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 8px center;
  }

  .inst-textarea {
    display: block;
    min-height: 92px;
    padding: 10px 14px;
    border-radius: 16px;
    line-height: 1.5;
    resize: vertical;
  }

  .inst-form-secao {
    margin: 0 0 10px;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-dark);
  }

  .inst-form-grid.endereco {
    grid-template-columns: 1fr 1.7fr .6fr;
    gap: 12px 16px;
  }

  .inst-form-grid.endereco .span-2 {
    grid-column: span 2;
  }

  .inst-form-grid.datas {
    grid-template-columns: repeat(2, minmax(0, 190px));
    gap: 14px 24px;
  }

  .inst-form-grid.datas .inst-input {
    max-width: none;
  }

  @media (max-width: 600px) {
    .inst-form-grid.endereco {
      grid-template-columns: 1fr 1fr;
    }

    .inst-form-grid.endereco .span-2 {
      grid-column: 1 / -1;
    }

    .inst-form-grid.datas {
      grid-template-columns: 1fr;
    }
  }


  /* Tela de alterar evento: formulário + aviso de encerramento lado a lado */
  .inst-form-layout {
    display: flex;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 24px;
  }

  .inst-form-layout .inst-form-card {
    flex: 1 1 460px;
  }

  .inst-aviso-perigo {
    flex: 0 1 300px;
    padding: 12px 16px 14px;
    background: var(--white);
    border: 1px solid #e58a8a;
    border-radius: 10px;
  }

  .inst-aviso-perigo h2 {
    margin: 0 0 6px;
    font-size: 11px;
    font-weight: 700;
    color: #c73f3f;
  }

  .inst-aviso-perigo-corpo {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
  }

  .inst-aviso-perigo p {
    margin: 0;
    font-size: 9px;
    font-weight: 500;
    line-height: 1.45;
    color: var(--text-dark);
  }

  .btn-perigo {
    flex-shrink: 0;
    min-width: 62px;
    padding: 5px 14px;
    background: #e03a2f;
    border: none;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(180, 30, 20, .3);
    font-family: 'Raleway', sans-serif;
    font-size: 9px;
    font-weight: 700;
    color: #fff;
    cursor: pointer;
    transition: filter .15s;
  }

  .btn-perigo:hover {
    filter: brightness(.93);
  }

  .btn-perigo:focus-visible {
    outline: 2px solid #c73f3f;
    outline-offset: 2px;
  }


  /* ============ AVALIAÇÕES DO EVENTO ============ */
  .inst-aval {
    max-width: 660px;
  }

  .inst-aval-titulo {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    color: var(--text-dark);
  }

  .inst-aval-sub {
    margin: 2px 0 18px;
    font-size: 11.5px;
    font-weight: 500;
    color: var(--text-light);
  }

  .inst-aval .inst-card {
    border-radius: 14px;
  }

  .inst-aval .inst-card+.inst-card {
    margin-top: 28px;
  }

  .aval-resumo {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: 28px;
    padding: 20px 28px 22px;
  }

  .aval-rotulo {
    margin: 0 0 8px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-dark);
  }

  .aval-nota {
    display: flex;
    align-items: baseline;
    gap: 2px;
    line-height: 1;
    color: var(--text-dark);
  }

  .aval-nota strong {
    font-size: 40px;
    font-weight: 700;
  }

  .aval-nota span {
    font-size: 15px;
    font-weight: 500;
  }

  .aval-estrelas {
    display: inline-flex;
    gap: 2px;
    margin: 10px 0 8px;
  }

  .aval-estrelas svg {
    width: 14px;
    height: 14px;
    fill: #d9dedb;
  }

  .aval-estrelas svg.on {
    fill: #f5a13a;
  }

  .aval-base {
    font-size: 10.5px;
    font-weight: 600;
    color: var(--text-dark);
  }

  .aval-dist {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .aval-linha {
    display: grid;
    grid-template-columns: 26px 1fr 26px;
    align-items: center;
    gap: 10px;
    font-size: 9.5px;
    font-weight: 700;
    color: var(--text-dark);
  }

  .aval-linha .estrela {
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .aval-linha .estrela svg {
    width: 9px;
    height: 9px;
    fill: var(--text-dark);
  }

  .aval-barra {
    height: 5px;
    background: var(--border);
    border-radius: 999px;
    overflow: hidden;
  }

  .aval-barra i {
    display: block;
    height: 100%;
    background: #f5a13a;
    border-radius: 999px;
  }

  .aval-qtd {
    text-align: right;
  }

  .aval-lista-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
  }

  .aval-lista-head h2 {
    margin: 0;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-dark);
  }

  .aval-filtro {
    appearance: none;
    -webkit-appearance: none;
    background: var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='9' height='9' viewBox='0 0 24 24' fill='none' stroke='%2316241d' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 8px center;
    border: 1px solid var(--border);
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(15, 40, 25, .18);
    padding: 4px 24px 4px 12px;
    font-family: 'Raleway', sans-serif;
    font-size: 9px;
    font-weight: 700;
    color: var(--text-dark);
    cursor: pointer;
  }

  .aval-filtro:focus-visible {
    outline: 2px solid var(--green-1);
    outline-offset: 2px;
  }

  .aval-lista {
    min-height: 240px;
  }

  .aval-item {
    display: flex;
    gap: 10px;
    padding: 14px 24px;
    border-bottom: 1px solid var(--border);
  }

  .aval-avatar {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #4a9a63;
    border-radius: 50%;
    font-size: 8.5px;
    font-weight: 700;
    color: #fff;
  }

  .aval-topo {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
  }

  .aval-topo strong {
    font-size: 9.5px;
    font-weight: 700;
    color: var(--text-dark);
  }

  .aval-topo .data {
    font-size: 7.5px;
    font-weight: 500;
    color: var(--text-muted);
  }

  .aval-item .aval-estrelas {
    margin: 3px 0 4px;
    gap: 1px;
    align-items: center;
  }

  .aval-item .aval-estrelas svg {
    width: 9px;
    height: 9px;
  }

  .aval-item .aval-estrelas .num {
    margin-left: 4px;
    font-size: 8px;
    font-weight: 700;
    color: var(--text-dark);
  }

  .aval-texto {
    margin: 0;
    font-size: 9.5px;
    font-weight: 500;
    line-height: 1.5;
    color: var(--text-dark);
  }

  .aval-vazio {
    padding: 56px 24px;
    text-align: center;
    font-size: 11px;
    font-weight: 500;
    color: var(--text-muted);
  }

  @media (max-width: 600px) {
    .aval-resumo {
      grid-template-columns: 1fr;
    }
  }


  /* ============ MENU DO USUÁRIO (header) ============ */
  .user-dropdown {
    position: relative;
  }

  body.inst button.user-menu {
    border: none;
    background: transparent;
    font-family: inherit;
  }

  body.inst button.user-menu:hover {
    background: var(--green-2);
  }

  body.inst button.user-menu:focus-visible,
  .user-dropdown-topo:focus-visible,
  .user-dropdown-item:focus-visible {
    outline: 2px solid #fff;
    outline-offset: -3px;
  }

  

  /* Ao abrir, o painel cobre o botão e forma um bloco só, como no design */
  .user-dropdown-panel {
    display: none;
    position: absolute;
    top: -4px;
    right: -4px;
    z-index: 50;
    min-width: calc(100% + 8px);
    min-width: max(100% + 8px, 190px);
    overflow: hidden;
    background: linear-gradient(90deg, #0e5c34 0%, #4cae4f 100%);
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, .35);
  }

  .user-dropdown.open .user-dropdown-panel {
    display: block;
  }

  .user-dropdown-topo,
  .user-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 10px 16px 10px 12px;
    background: transparent;
    border: none;
    font-family: 'Raleway', sans-serif;
    font-size: 14px;
    font-weight: 300;
    color: #fff;
    text-align: left;
    text-decoration: none;
    cursor: pointer;
  }

  .user-dropdown-topo .name {
    font-size: 14px;
    font-weight: 300;
    color: #fff;
  }

  .user-dropdown-item {
    border-top: 1px solid rgba(255, 255, 255, .9);
    padding-left: 16px;
  }

  .user-dropdown-item svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
  }

  .user-dropdown-topo:hover,
  .user-dropdown-item:hover {
    background: rgba(255, 255, 255, .12);
  }

  /* ============ EDITAR PERFIL ============ */
  .perfil-card {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 270px;
    max-width: 1000px;
    min-height: 440px;
    background: var(--white);
    border-radius: 14px;
    box-shadow: var(--inst-card-shadow);
    overflow: hidden;
  }

  .perfil-form {
    padding: 30px 36px 34px;
  }

  .perfil-titulo {
    margin: 0 0 30px;
    font-size: 28px;
    font-weight: 500;
    color: var(--text-dark);
  }

  .perfil-campos {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 520px;
  }

  .perfil-campo,
  .perfil-bloco,
  .perfil-foto {
    background: var(--white);
    border: 1px solid #b8c2bc;
    border-radius: 6px;
    color: #6b7871;
  }

  .perfil-campo,
  .perfil-foto,
  .perfil-bloco-cab {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .perfil-campo {
    padding: 9px 14px;
  }

  .perfil-campo:focus-within,
  .perfil-foto:focus-within,
  .perfil-bloco:focus-within {
    border-color: var(--green-1);
    box-shadow: 0 0 0 2px rgba(31, 138, 76, .15);
  }

  .perfil-campo.invalido {
    border-color: #c73f3f;
  }

  .perfil-campo svg,
  .perfil-bloco-cab svg {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
    stroke: currentColor;
    fill: none;
    stroke-width: 1.5;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .perfil-campo input {
    flex: 1;
    min-width: 0;
    border: none;
    outline: none;
    background: transparent;
    font-family: 'Raleway', sans-serif;
    font-size: 12px;
    font-weight: 500;
    color: #5d6b64;
  }

  .perfil-bloco {
    padding: 10px 14px 14px;
  }

  .perfil-bloco-cab {
    margin-bottom: 10px;
    font-size: 13px;
    font-weight: 500;
  }

  .perfil-end-grid {
    display: grid;
    grid-template-columns: 1fr 1.7fr .6fr;
    gap: 10px 14px;
    padding-left: 36px;
  }

  .perfil-end-grid .span-2 {
    grid-column: span 2;
  }

  .perfil-end-grid label {
    display: block;
    margin-bottom: 2px;
    font-size: 8px;
    font-weight: 500;
    color: var(--text-muted);
  }

  .perfil-end-grid input,
  .perfil-end-grid select {
    width: 100%;
    padding: 6px 8px;
    background: var(--white);
    border: 1px solid #b8c2bc;
    border-radius: 6px;
    font-family: 'Raleway', sans-serif;
    font-size: 10px;
    font-weight: 500;
    color: #5d6b64;
    outline: none;
  }

  .perfil-end-grid select {
    appearance: none;
    -webkit-appearance: none;
    padding-right: 22px;
    cursor: pointer;
    background: var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%236b7871' stroke-width='2.6' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 7px center;
  }

  .perfil-end-grid input:focus,
  .perfil-end-grid select:focus {
    border-color: var(--green-1);
    box-shadow: 0 0 0 2px rgba(31, 138, 76, .15);
  }

  .perfil-foto {
    padding: 8px 14px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
  }

  .perfil-foto input[type="file"] {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
  }

  .perfil-foto-mini {
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #4a8c63;
    border-radius: 50%;
    font-size: 9px;
    font-weight: 600;
    color: #fff;
  }

  .perfil-foto-nome {
    margin-left: auto;
    max-width: 40%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 10px;
    font-weight: 600;
    color: var(--green-2);
  }

  .perfil-acoes {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    max-width: 520px;
    margin-top: 34px;
    padding: 0 6px;
  }

  .perfil-btn {
    min-width: 108px;
    padding: 11px 18px;
    border: none;
    border-radius: 8px;
    font-family: 'Raleway', sans-serif;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: filter .15s;
  }

  .perfil-btn:hover {
    filter: brightness(1.12);
  }

  .perfil-btn:focus-visible {
    outline: 2px solid var(--green-1);
    outline-offset: 2px;
  }

  .perfil-btn.salvar {
    background: #2b5a40;
  }

  .perfil-btn.cancelar {
    background: #990000;
  }

  .perfil-lateral {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 20px;
    border-left: 1px solid var(--border);
    text-align: center;
  }

  .perfil-avatar {
    width: 104px;
    height: 104px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
    background: #4a8c63;
    border-radius: 50%;
    font-size: 40px;
    font-weight: 400;
    color: #fff;
  }

  .perfil-lateral h2 {
    margin: 0 0 8px;
    font-size: 20px;
    font-weight: 500;
    color: var(--text-dark);
  }

  .perfil-lateral p {
    margin: 0 0 14px;
    font-size: 9px;
    font-weight: 500;
    color: var(--text-dark);
    word-break: break-all;
  }

  @media (max-width: 860px) {
    .perfil-card {
      grid-template-columns: 1fr;
    }

    .perfil-lateral {
      order: -1;
      border-left: none;
      border-bottom: 1px solid var(--border);
      padding: 28px 20px;
    }
  }

  @media (max-width: 600px) {
    .perfil-form {
      padding: 24px 18px 28px;
    }

    .perfil-end-grid {
      grid-template-columns: 1fr 1fr;
      padding-left: 0;
    }

    .perfil-end-grid .span-2 {
      grid-column: 1 / -1;
    }
  }

  /* ============ RESPONSIVO ============ */
  @media (max-width: 1000px) {
    .inst-split {
      grid-template-columns: 1fr;
    }

    .inst-stats {
      max-width: none;
    }
  }

  @media (max-width: 760px) {
    .inst-stats {
      grid-template-columns: 1fr;
    }
  }
</style>