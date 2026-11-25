

  <style>
    :root {
      --sidebar-bg: #002202;
      --sidebar-bg-2: #00221E;
      --sidebar-active: #429B41;
      --green-1: #1f8a4c;
      --green-2: #0e5c34;
      --bg: #eef1f0;
      --white: #fff;
      --text-dark: #16241d;
      --text-muted: #6b7871;
      --text-light: #9aa79f;
      --border: #e6e9e7;
      --icon-bg: #eaf5ee;
      --icon-color: #1f8a4c;
      font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
      
    }

    * {
      box-sizing: border-box;
    }
html, body, input, select, textarea, button {
  font-variant-numeric: lining-nums;
  font-feature-settings: "lnum" 1;
}

/* Esconde a barra de rolagem, mantendo a rolagem funcionando */
html, body {
  -ms-overflow-style: none;   /* Edge/IE antigo */
  scrollbar-width: none;      /* Firefox e navegadores atuais */
}

html::-webkit-scrollbar,
body::-webkit-scrollbar {
  display: none;              /* Chrome, Edge, Safari */
  width: 0;
  height: 0;
}

    html,
    body {
      margin: 0;
      min-height: 100%;
      background: #fff;
      color: var(--text-dark);
      -webkit-font-smoothing: antialiased;
    }

    button,
    input {
      font-family: 'Raleway', sans-serif;
      font-weight: 600;
    }

    ::-webkit-scrollbar {
      width: 6px;
    }

    ::-webkit-scrollbar-thumb {
      background: #cfd6d1;
      border-radius: 4px;
    }

    /* ---- shared bits ---- */
    .hidden {
      display: none !important;
    }

    svg {
      display: block;
    }

    /* ---- AUTH SCREENS ---- */
    .auth-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    .auth-card {
      background: #fff;
      border-radius: 20px;
      padding: 40px 44px;
      width: 100%;
      max-width: 460px;
      border: 1px solid var(--border);
      box-shadow: 0 10px 30px rgba(15, 60, 40, 0.06);
    }

    .back-link {
      font-size: 13px;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 22px;
      cursor: pointer;
      background: none;
      border: none;
      padding: 0;
      font-weight: 600;
      text-decoration:none;
    }

    .back-link:hover {
      color: var(--text-dark);
    }

    .auth-logo {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 9px;
      margin-bottom: 22px;
    }

    .auth-logo .brand-logo {
      width: 50px;
      height: 50px;
    }

    .auth-logo .brand-name {
      font-size: 30px;
      font-family: 'Raleway', sans-serif;
      font-weight: 300;
      color: var(--text-dark);
      
    }

    .auth-title {
      font-size: 20px;
      font-family: 'Raleway', sans-serif;
      font-weight: 700;
      margin: 0 0 6px;
      text-align: left;
    }

    .auth-title.center {
      text-align: center;
    }

    .auth-sub {
      font-family: 'Raleway', sans-serif;
      font-weight: 300;
      font-size: 13px;
      color: var(--text-muted);
      margin: 20px 0 24px;
      line-height: 0.9;
    }

    .auth-sub.center {
      text-align: center;
    }

    .field {
      position: relative;
      margin-bottom: 12px;
    }

    .field .ic-left {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
      pointer-events: none;
    }

    .field input {
      width: 100%;
      padding: 12px 14px 12px 40px;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13.5px;
      outline: none;
      background: #fbfcfb;
    }

    .field input:focus {
      border-color: var(--green-1);
      background: #fff;
    }

    .field .eye {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--text-light);
    }

    .acc-label {
      font-size: 12.5px;
      font-family: 'Raleway', sans-serif;
      font-weight: 600;
      margin: 18px 0 10px;
    }

    .acc-types {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
    }

    .acc-type {
      flex: 1;
      border: 1.5px solid var(--border);
      border-radius: 12px;
      padding: 16px 10px;
      text-align: center;
      cursor: pointer;
      position: relative;
      background: #fff;
    }

    .acc-type.selected {
      border-color: var(--green-1);
      background: #f3faf5;
    }

    .acc-type .dot {
      position: absolute;
      top: 12px;
      right: 12px;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      border: 1.5px solid #cfd6d1;
      background: #fff;
    }

    .acc-type.selected .dot {
      background: var(--green-1);
      border-color: var(--green-1);
    }

    .acc-type .ic {
      color: var(--icon-color);
      margin-bottom: 6px;
    }

    .acc-type .lbl {
      font-size: 12.5px;
      font-weight: 700;
    }

    .terms-row {
      display: flex;
      align-items: flex-start;
      gap: 9px;
      font-size: 12px;
      color: var(--text-muted);
      margin-bottom: 22px;
      cursor: pointer;
    }

    .terms-row a {
      color: var(--green-2);
      font-weight: 700;
      text-decoration: none;
    }

    .checkbox {
      width: 17px;
      height: 17px;
      border-radius: 5px;
      border: 1.5px solid var(--green-1);
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      margin-top: 1px;
    }

    .checkbox.checked {
      background: var(--green-1);
    }

    .remember-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12.5px;
      margin: 2px 0 22px;
    }

    .remember-l {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      color: var(--text-dark);
      font-weight: 500;
    }

    .forgot-link {
      color: var(--green-2);
      font-weight: 700;
      text-decoration: none;
      cursor: pointer;
    }

    .btn-primary {
      width: 100%;
      background: var(--sidebar-bg);
      color: #fff;
      border: none;
      padding: 13px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background .15s;
    }

    .btn-primary:hover {
      background: var(--sidebar-bg-2);
    }

    .btn-outline {
      width: 100%;
      background: #fff;
      border: 1.5px solid var(--border);
      padding: 11.5px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      color: var(--text-dark);
      margin-top: 12px;
    }

    .btn-outline:hover {
      border-color: var(--green-1);
      color: var(--green-2);
    }

    /* ---- APP SHELL ---- */
    .app {
      width: 100%;
      min-height: 100vh;
      display: grid;
      grid-template-columns: 240px 1fr;
      grid-template-rows: auto 1fr;
    }

    .sidebar {
      grid-row: 1/3;
      background: linear-gradient(180deg, var(--sidebar-bg-2) 0%, var(--sidebar-bg) 100%);
      color: #dfeee6;
      display: flex;
      flex-direction: column;
      padding: 26px 18px;
      position: sticky;
      top: 0;
      height: 100vh;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 4px 8px 28px 8px;
    }

    .brand-logo {
      width: 44px;
      height: 45px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .brand-name {
      font-family: 'Raleway', sans-serif;
      font-weight: 100;
      font-size:25px;
      
      color: #f2fbf6;
    }

    .nav {
      display: flex;
      flex-direction: column;
      gap: 4px;
      margin-top: 4px;
      flex: 1;
    }

    .nav-item {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 9px 11px;               /* era 11px 13px */
  border-radius: 10px;
  font-size: 13.5px;
  color: #b9cabf;
  cursor: pointer;
  border: 2px solid transparent;   /* era border: none; */
  background: transparent;
  width: 100%;
  text-align: left;
  transition: background .15s, color .15s;
  text-decoration: none;
}

    .nav-item .ic {
      width: 17px;
      height: 17px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: .85;
    }

    .nav-item span.label {
      flex: 1;
      font-family: 'Raleway', sans-serif;
      font-weight: 300;
      line-height: 1.25;
    }

    .nav-item .badge {
      background: #e14b4bcc;
      color: #fff;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 80px;
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, .06);
      color: #eaf5ee;
    }

    .nav-item.active {
  color: #fff;
  border-color: var(--sidebar-active);
}

    .nav-item.active .ic {
      opacity: 1;
    }

    .nav-bottom {
      display: flex;
      flex-direction: column;
      gap: 4px;
      padding-top: 14px;
      border-top: 1px solid rgba(255, 255, 255, .08);
    }

    .header {
      background: var(--sidebar-bg-2);
      display: flex;
      align-items: center;
       justify-content: flex-end;
      padding: 18px 36px;
      border-bottom: 1px solid var(--border);
      gap: 16px;
    }

    .search-box {
      flex: 1;
      max-width: 420px;
      display: flex;
      align-items: center;
      gap: 9px;
      background: #0b3d22f2;
      border-radius: 30px;
      padding: 10px 15px;
      color: white;
    }

    .search-box input {
      border: none;
      background: transparent;
      outline: none;
      font-family: 'Raleway', sans-serif;
      font-weight: 400;
      font-size: 12.5px;
      color: white;
      width: 100%;
    }

    .search-box input::placeholder {
      color: #a9b3ad;
    }

    .user-menu {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      padding: 5px 8px;
      border-radius: 30px;
      transition: background .15s;
      user-select: none;
    }

    .user-menu:hover {
      background:var(--green-2);

    }

    .user-avatar-sm {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: linear-gradient(135deg, #2fa663, #0e5c34);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 12px;
      font-weight: 700;
    }

    .user-menu .name {
      font-size: 14px;
      font-family: 'Raleway', sans-serif;
      font-weight: 300;
      color: var(--white);
    }

    .chev {
      color: var(--text-light);
      font-size: 9px;
      margin-left: 2px;
    }

    .main {
      padding: 36px 40px 48px 40px;
      overflow-y: auto;
      max-width: 1100px;
    }

    .view {
      display: none;
    }

    .view.active {
      display: block;
    }

    /* ---- Inicio (home) view ---- */
    h1.welcome {
      font-size: 25px;
      font-family: 'Raleway', sans-serif;
      font-weight: 600;
      margin: 0 0 22px 0;
    }

    .profile-card {
      background: linear-gradient(120deg, var(--green-1) 0%, var(--green-2) 100%);
      border-radius: 10px;
      padding: 26px 32px;
      display: flex;
      align-items: center;
      gap: 20px;
      color: #fff;
      margin-bottom: 22px;
    }

    .profile-avatar {
      width: 66px;
      height: 66px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .22);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Raleway', sans-serif;
      font-weight: 500;
      font-size: 26px;
      flex-shrink: 0;
    }

    .profile-info .p-name {
      font-size: 23px;
      font-family: 'Raleway', sans-serif;
      font-weight: 400;
      margin-bottom: 4px;
    }


    .profile-info .p-since {
      font-size: 13px;
      font-family: 'Raleway', sans-serif;
      font-weight: 400;
      opacity: .9;
      font-weight: 600;
    }

    .menu-list {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .menu-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 12px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      cursor: pointer;
      text-align: left;
      width: 100%;
      transition: transform .12s, box-shadow .12s, border-color .12s;
      text-decoration: none;
      color: var(--icon-color);
    }

    .menu-card:hover {
      box-shadow: 0 6px 16px rgba(15, 92, 52, .12);
      border-color: #d7e6dc;
      transform: translateY(-1px);
      color: var(--green-2);
    }

    .menu-icon {
      width: 48px;
      height: 48px;
      border-radius: 13px;
      color: var(--icon-color);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .menu-text {
      flex: 1;
    }

    .menu-title {
      font-size: 15px;
      font-family: 'Raleway', sans-serif;
      font-weight: 600;
      margin-bottom: 3px;
      color: black;
    }

    .menu-sub {
      font-size: 12.5px;
      font-family: 'Raleway', sans-serif;
      font-weight: 300;
      color: var(--text-muted);
    }

    .menu-arrow {
      color: #c3ccc6;
      font-size: 31px;
      flex-shrink: 0;
      transition: transform .12s, color .12s;
    }

    .menu-card:hover .menu-arrow {
      color: var(--icon-color);
      transform: translateX(2px);
    }

    /* ---- page header ---- */
    .page-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      gap: 16px;
      flex-wrap: wrap;
    }

    .page-title {
      font-size: 22px;
      font-family: 'Raleway', sans-serif;
      font-weight: 600;
      margin: 0 0 4px;
    }

    .page-sub {
      font-size: 16px;
      font-family: 'Raleway', sans-serif;
      font-weight: 400;
      color: var(--text-muted);
      margin: 0;
    }

    .select-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 9px 14px;
      font-size: 12.5px;
      font-weight: 600;
      cursor: pointer;
      color: var(--text-dark);
      transition: background .15s;
    }
    .select-btn:hover{
      background: var(--text-light);
    }

    /* ---- stat cards ---- */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-bottom: 16px;
    }

    .stat-card {
      background: #fff;
      border-radius: 16px;
      padding: 18px 20px;
    }

    .stat-top {
      display: flex;
      align-items: center;
      gap: 9px;
      font-size: 15px;
      color: var(--text-muted);
      margin-bottom: 12px;
      font-weight: 600;
    }

    .stat-icon {
      width: 30px;
      height: 30px;
      border-radius: 9px;
      color: var(--icon-color);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .stat-value {
      font-size: 23px;
      font-weight: 700;
    }

    .stat-change {
      font-size: 18px;
      color: var(--green-1);
      margin-top: 6px;
      font-weight: 600;
    }

    .stat-change.down {
      color: #e14b4b;
    }

    .stat-value.orange {
      color: #c9700b;
    }

    .dash-row {
      display: grid;
      grid-template-columns: 1fr 1.4fr;
      gap: 16px;
      margin-bottom: 16px;
      align-items: stretch;
    }

    .panel {
      background: #fff;
      border-radius: 16px;
      padding: 20px 22px;
    }

    .panel-title {
      font-size: 18px;
      margin: 0 0 16px;
      font-family: 'Raleway', sans-serif;
      font-weight: 800;
    }

    .gauge-wrap {
      display: flex;
      align-items: center;
      gap: 24px;
    }

    .gauge {
      width: 118px;
      height: 118px;
      border-radius: 50%;
      background: conic-gradient(var(--green-1) 0% 74%, #e7eae8 74% 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .gauge-inner {
      width: 84px;
      height: 84px;
      background: #fff;
      border-radius: 50%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .gauge-inner b {
      font-size: 16px;
    }

    .gauge-inner span {
      font-size: 9.5px;
      color: var(--text-muted);
    }

    .legend {
      display: flex;
      flex-direction: column;
      gap: 10px;
      font-size: 12.5px;
    }

    .legend .dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 8px;
    }

    .legend b {
      display: block;
      font-size: 14px;
    }

    .legend .row {
      display: flex;
      align-items: center;
      font-family: 'Raleway', sans-serif;
      font-weight: 500;
    }

    .mini-stats {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    .mini-stat {
      border-radius: 12px;
      padding: 14px 16px;
    }

    .mini-stat .lbl {
      font-size: 20px;
      color: var(--text-muted);
      margin-bottom: 8px;
      font-weight: 600;
    }

    .mini-stat .val {
      font-size: 20px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 7px;
    }

    .mini-stat .dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
    }

    /* ---- activity list ---- */
    .activity-panel {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
    }

    .activity-panel .panel-title {
      padding: 18px 22px 0;
      font-family: 'Raleway', sans-serif;
      font-weight: 700;
    }

    .activity-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 22px;
      border-top: 1px solid var(--border);
      font-size: 12.5px;
    }

    .activity-row .txt b {
      font-weight: 700;
    }

    .activity-row .date {
      color: var(--text-muted);
      font-size: 11.5px;
      white-space: nowrap;
      margin-left: 16px;
    }

    /* ---- tabs ---- */
    .tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 18px;
      flex-wrap: wrap;
    }

    .tab {
      padding: 10px 20px;
      border-radius: 10px;
      font-size: 12.5px;
      font-weight: 700;
      border: 1px solid var(--border);
      background: #fff;
      cursor: pointer;
      color: var(--text-dark);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .tab.active {
      background: var(--sidebar-bg);
      color: #fff;
      border-color: var(--sidebar-bg);
    }

    .tab .count {
      background: rgba(0, 0, 0, .08);
      border-radius: 20px;
      font-size: 10.5px;
      padding: 1px 7px;
    }

    .tab.active .count {
      background: rgba(255, 255, 255, .2);
    }

    .tab .count.warn {
      background: #e14b4b;
      color: #fff;
    }

    /* ---- filters row ---- */
    .filters-row {
      display: flex;
      gap: 12px;
      margin-bottom: 18px;
      flex-wrap: wrap;
    }

    .filters-row .search-box {
      flex: 1;
      min-width: 220px;
      max-width: none;
      border-color: var(--border);
    }

    /* ---- table ---- */
    .table-panel {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12.5px;
    }

    .data-table th {
      text-align: left;
      padding: 14px 22px;
      color: black;
      font-family: 'Raleway', sans-serif;
      font-weight: 800;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: .03em;
      border-bottom: 1px solid var(--border);
    }

    .data-table td {
      padding: 15px 22px;
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }

    .data-table tr:last-child td {
      border-bottom: none;
    }

    .badge-status {
      padding: 9px 15px;
      border-radius: 20px;
      font-size: 11px;
      font-family: 'Raleway', sans-serif;
      font-weight: 700;
      display: inline-block;
    }

    .badge-status.pending {
      background: #fdecdc;
      color: #c9700b;
    }

    .badge-status.active {
      background: #e3f5e8;
      color: #1f8a4c;
    }

    .badge-status.suspended {
      background: #fde3e3;
      color: #c73f3f;
    }

    .icon-btn {
      width: 30px;
      height: 30px;
      border-radius: 8px;
      border: 1px solid var(--border);
      background: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--text-muted);
    }

    .icon-btn:hover {
      border-color: var(--green-1);
      color: var(--green-1);
    }

    .pager {
      text-align: center;
      padding: 16px;
      font-size: 12.5px;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    /* ---- toast ---- */
    .toast {
      position: fixed;
      left: 50%;
      bottom: 24px;
      transform: translateX(-50%) translateY(12px);
      background: #16241d;
      color: #fff;
      font-size: 12px;
      padding: 9px 16px;
      border-radius: 20px;
      opacity: 0;
      pointer-events: none;
      transition: opacity .2s, transform .2s;
      white-space: nowrap;
      z-index: 50;
    }

    .toast.show {
      opacity: 1;
      transform: translateX(-50%) translateY(0);
    }

    @media(max-width:900px) {
      .stats-row {
        grid-template-columns: 1fr;
      }

      .dash-row {
        grid-template-columns: 1fr;
      }

      .mini-stats {
        grid-template-columns: 1fr;
      }
    }

    /* ---- extras adicionados para integração com o Laravel ---- */

    .badge-status.analise {
      background: #e6effc;
      color: #2563c9;
    }

    .badge-status.resolved {
      background: #e3f5e8;
      color: #1f8a4c;
    }

    .badge-status.inativo {
      background: #fde3e3;
      color: #c73f3f;
    }

    .badge-status.ativo {
      background: #e3f5e8;
      color: #1f8a4c;
    }

    a.icon-btn {
      text-decoration: none;
    }

    .icon-btn.danger:hover {
      border-color: #c73f3f;
      color: #c73f3f;
    }

    form.inline-form {
      display: inline-block;
      margin: 0;
    }

    .btn-reset-link {
      display: flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 9px 14px;
      font-size: 12.5px;
      font-family: 'Raleway', sans-serif;
      font-weight: 800;
      cursor: pointer;
      color: #e14b4bcc;
      transition: background .15s;
      text-decoration: none;
    }
    .btn-reset-link:hover {
      background: var(--text-light);
    }

    .filters-row select,
    .filters-row .native-select {
      border: 1px solid var(--border);
      background: #fff;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 13px;
      font-family: 'Raleway', sans-serif;
      font-weight: 450;
      color: var(--text-dark);
      cursor: pointer;
    }

    .alert-msg {
      background: #e3f5e8;
      border: 1px solid #c7e8d2;
      color: #0e5c34;
      font-size: 13px;
      font-weight: 600;
      padding: 12px 16px;
      border-radius: 10px;
      margin-bottom: 16px;
    }

    .field-error {
      color: #c73f3f;
      font-size: 12px;
      margin: -8px 0 12px;
      display: block;
    }

    .info-panel {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px 26px;
      margin-bottom: 18px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      padding: 12px 0;
      border-bottom: 1px solid var(--border);
      font-size: 13.5px;
    }

    .info-row:last-child {
      border-bottom: none;
    }

    .info-row .info-label {
      color: var(--text-muted);
      font-weight: 600;
      min-width: 160px;
    }

    .info-row .info-value {
      color: var(--text-dark);
      text-align: right;
      flex: 1;
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px 18px;
    }

    .form-grid .field-full {
      grid-column: 1 / -1;
    }

    .form-label {
      font-size: 12.5px;
      font-weight: 700;
      color: var(--text-muted);
      margin-bottom: 6px;
      display: block;
    }

    .form-grid input[type="text"] {
      width: 100%;
      padding: 11px 13px;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 13px;
      background: #fff;
      color: var(--text-dark);
    }

    .form-grid input[readonly] {
      background: #f4f6f5;
      color: var(--text-muted);
    }

    .back-link-page {
      font-size: 13px;
      color: var(--text-muted);
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 16px;
      text-decoration: none;
      font-weight: 600;
    }

    .back-link-page:hover {
      color: var(--text-dark);
    }

    .actions-row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 4px;
    }

    .actions-row .btn-primary,
    .actions-row .btn-outline {
      width: auto;
      padding: 11px 20px;
      display:flex;
    }
    .btn-primary{
      margin-top: 13px;
    }
    dialog#confirmaMensagem {
      border: none;
      border-radius: 16px;
      padding: 26px 28px;
      max-width: 380px;
      width: 90%;
      box-shadow: 0 20px 50px rgba(15, 60, 40, 0.18);
    }

    dialog#confirmaMensagem::backdrop {
      background: rgba(11, 46, 34, 0.45);
    }

    dialog#confirmaMensagem p {
      font-size: 14px;
      color: var(--text-dark);
      margin: 0 0 20px;
      line-height: 1.5;
    }

    dialog#confirmaMensagem .dialog-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
    }

    dialog#confirmaMensagem button {
      border-radius: 10px;
      padding: 9px 18px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--text-dark);
    }

    dialog#confirmaMensagem #botaoConfirmar {
      background: var(--green-1);
      border-color: var(--green-1);
      color: #fff;
    }

    nav.pager-nav {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 14px;
      padding: 16px;
      font-size: 12.5px;
      color: var(--text-muted);
    }

    nav.pager-nav a {
      color: var(--text-muted);
      text-decoration: none;
      font-weight: 600;
    }

    nav.pager-nav a:hover {
      color: var(--green-1);
    }

    nav.pager-nav strong {
      color: #fff;
      background: var(--green-1);
      padding: 4px 10px;
      border-radius: 8px;
    }

    .icon-btn.text-btn {
    width: auto;
    min-width: 85px;
    height: 32px;
    padding: 0 10px;
    font-size: 11px;
    font-family: 'Raleway', sans-serif;
    font-weight: 700;
    white-space: nowrap;
}
</style>
