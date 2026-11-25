@extends('layouts.auth')

@section('titulo', 'Crie sua conta')
@section('auth-page-class', 'auth-cadastro-page')
@section('auth-card-class', 'auth-cadastro-wrapper')

@section('content')

@php
    $icones = [
        'inst'   => '<path d="M3 21h18M5 21V7l8-4 6 4v14M9 21v-6h6v6" />',
        'cnpj'   => '<rect x="3" y="4" width="18" height="16" rx="2" /><path d="M7 8h10M7 12h10M7 16h5" />',
        'user'   => '<circle cx="12" cy="8" r="4" /><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7" />',
        'email'  => '<rect x="3" y="5" width="18" height="14" rx="2" /><path d="M3 7l9 6 9-6" />',
        'tel'    => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.84.58 2.8.7A2 2 0 0 1 22 16.92z" />',
        'cep'    => '<rect x="3" y="5" width="18" height="14" rx="2" /><path d="M7 9h10M7 13h6" />',
        'rua'    => '<path d="M3 10l9-7 9 7" /><path d="M5 9v12h14V9M9 21v-7h6v7" />',
        'num'    => '<rect x="4" y="4" width="16" height="16" rx="2" /><path d="M9 9h2v6M9 12h2M14 9h2v6" />',
        'comp'   => '<rect x="4" y="4" width="16" height="16" rx="2" /><path d="M8 9h8M8 13h8" />',
        'bairro' => '<path d="M3 21V8l9-5 9 5v13" /><path d="M9 21v-6h6v6" />',
        'cidade' => '<path d="M3 21h18M5 21V7l7-4 7 4v14" /><path d="M9 21v-5h6v5M9 9h.01M15 9h.01" />',
        'uf'     => '<path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z" />',
        'senha'  => '<rect x="4" y="11" width="16" height="9" rx="2" /><path d="M8 11V8a4 4 0 0 1 8 0v3" />',
    ];

    // Gera um campo completo: ícone + input + mensagem de erro
    $campo = function ($classe, $nome, $placeholder, $icone, $tipo = 'text', $max = null, $obrigatorio = true) use ($icones, $errors) {
        $valor = $tipo === 'password' ? '' : e(old($nome));
        $erro  = $errors->first($nome);

        $html  = '<div class="col ' . $classe . '"><div class="field">';
        $html .= '<span class="ic-left"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $icones[$icone] . '</svg></span>';
        $html .= '<input type="' . $tipo . '" name="' . $nome . '" placeholder="' . e($placeholder) . '" value="' . $valor . '"';
        $html .= $max ? ' maxlength="' . $max . '"' : '';
        $html .= $obrigatorio ? ' required' : '';
        $html .= '></div>';
        $html .= $erro ? '<span class="field-error">' . e($erro) . '</span>' : '';
        $html .= '</div>';

        return new \Illuminate\Support\HtmlString($html);
    };
@endphp

<div class="cadastro-page">
    <div class="cadastro-card">

        <a class="back-link" href="{{ route('login') }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.2"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12" />
                <polyline points="12 19 5 12 12 5" />
            </svg>
            Voltar para o login
        </a>

        <h1 class="auth-title">Crie sua conta</h1>

        <p class="auth-sub">
            Cadastre sua instituição e faça parte da nossa rede de sustentabilidade!
        </p>

        @if(session('mensagem'))
            <div class="alert-msg">{{ session('mensagem') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrija os campos abaixo para continuar.</strong>
            </div>
        @endif

        <form action="{{ route('instituicao.criar') }}" method="POST" id="formCadastro">
            @csrf

            <div class="colunas">

                {{-- ========== COLUNA ESQUERDA ========== --}}
                <div class="coluna">

                    {{-- TIPO --}}
                    <section class="secao">
                        <h2 class="secao-titulo">Tipo de instituição</h2>

                        <div class="account-type-options">
                            <label class="acc-type {{ old('tipoInstituicao') == 'cooperativa' ? 'selected' : '' }}">
                                <input type="radio" class="acc-radio" name="tipoInstituicao" value="cooperativa"
                                    {{ old('tipoInstituicao') == 'cooperativa' ? 'checked' : '' }} required>
                                <span>Cooperativa</span>
                            </label>

                            <label class="acc-type {{ old('tipoInstituicao') == 'orgao_publico' ? 'selected' : '' }}">
                                <input type="radio" class="acc-radio" name="tipoInstituicao" value="orgao_publico"
                                    {{ old('tipoInstituicao') == 'orgao_publico' ? 'checked' : '' }} required>
                                <span>Órgão público</span>
                            </label>
                        </div>

                        @error('tipoInstituicao')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </section>

                    {{-- DADOS DA INSTITUIÇÃO --}}
                    <section class="secao">
                        <h2 class="secao-titulo">Dados da instituição</h2>

                        <div class="grid">
                            {{ $campo('c6', 'nomeInstituicao', 'Nome da instituição', 'inst', 'text', 150) }}
                            {{ $campo('c3', 'cnpjInstituicao', 'CNPJ', 'cnpj', 'text', 18) }}
                            {{ $campo('c3', 'numTelefoneInstituicao', 'Telefone de contato', 'tel', 'tel', 15) }}
                        </div>
                    </section>

                    {{-- RESPONSÁVEL E ACESSO --}}
                    <section class="secao">
                        <h2 class="secao-titulo">Responsável e acesso</h2>

                        <div class="grid">
                            {{ $campo('c3', 'nomeUsuario', 'Nome do responsável', 'user', 'text', 100) }}
                            {{ $campo('c3', 'emailUsuario', 'E-mail (será o seu login)', 'email', 'email', 100) }}

                            <div class="col c6">
                                <div class="field campo-senha">
                                    <span class="ic-left"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icones['senha'] !!}</svg></span>
                                    <input type="password" id="signupPass" name="senhaUsuario"
                                        placeholder="Senha (mínimo 8 caracteres)" minlength="8" required>
                                    <span class="eye" onclick="togglePass('signupPass')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </span>
                                </div>
                                @error('senhaUsuario')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </section>
                </div>

                {{-- ========== COLUNA DIREITA ========== --}}
                <div class="coluna">

                    {{-- ENDEREÇO --}}
                    <section class="secao">
                        <h2 class="secao-titulo">Endereço</h2>

                        <div class="grid">
                            {{ $campo('c2', 'cepEnderecoInstituicao', 'CEP', 'cep', 'text', 9) }}
                            {{ $campo('c4', 'logradouroEnderecoInstituicao', 'Rua, avenida ou logradouro', 'rua', 'text', 150) }}

                            {{ $campo('c2', 'numeroEnderecoInstituicao', 'Número', 'num', 'text', 10) }}
                            {{ $campo('c4', 'complementoEnderecoInstituicao', 'Complemento (opcional)', 'comp', 'text', 100, false) }}

                            {{ $campo('c4', 'bairroEnderecoInstituicao', 'Bairro', 'bairro', 'text', 100) }}
                            {{ $campo('c2', 'ufEnderecoInstituicao', 'UF', 'uf', 'text', 2) }}

                            {{ $campo('c6', 'cidadeEnderecoInstituicao', 'Cidade', 'cidade', 'text', 100) }}
                        </div>
                    </section>
                </div>
            </div>

            {{-- ========== TERMOS E ENVIO ========== --}}
            <div class="rodape">
                <div class="termos-box">
                    <label class="terms-row">
                        <input type="checkbox" name="aceitaTermos" value="1"
                            {{ old('aceitaTermos') ? 'checked' : '' }} required>
                        <span>
                            Li e aceito os <a href="#">Termos de Uso</a> e a <a href="#">Política de Privacidade</a>
                        </span>
                    </label>
                    @error('aceitaTermos')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    Cadastrar
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </button>
            </div>

        </form>
    </div>
</div>

<style>
/* ===== AJUSTE DA PÁGINA (sem scroll) ===== */

.auth-page.auth-cadastro-page {
    padding: 14px 24px !important;
    align-items: center !important;
}

/* ===== ESTRUTURA ===== */

.cadastro-page {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 !important;
    box-sizing: border-box;
}

.cadastro-card {
    width: 100%;
    max-width: 1020px;
    margin: 0 auto;
    padding: 20px 30px 22px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, .16);
    box-sizing: border-box;
}

.cadastro-card .auth-title { font-size: 22px; margin: 6px 0 2px; }
.cadastro-card .auth-sub   { font-size: 13px; line-height: 1.3; margin: 2px 0 10px; }

.cadastro-card .alert,
.cadastro-card .alert-msg { margin-bottom: 8px; padding: 6px 10px; font-size: 12.5px; }

/* ===== COLUNAS ===== */

.cadastro-card .colunas {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 28px;
}

.cadastro-card .coluna {
    display: flex;
    flex-direction: column;
    gap: 12px;
    min-width: 0;
}

/* linha vertical entre as colunas */
.cadastro-card .coluna + .coluna {
    padding-left: 28px;
    border-left: 2px solid #c3d3c8;
}

/* ===== SEÇÕES ===== */

.cadastro-card .secao-titulo {
    margin: 0 0 8px;
    font-size: 14px;
    font-weight: 700;
    color: #1d3a27;
}

/* ===== GRADE (6 colunas) ===== */

.cadastro-card .grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 8px 10px;
}

.cadastro-card .col { min-width: 0; }
.cadastro-card .c2 { grid-column: span 2; }
.cadastro-card .c3 { grid-column: span 3; }
.cadastro-card .c4 { grid-column: span 4; }
.cadastro-card .c6 { grid-column: span 6; }

/* ===== TIPO ===== */

.cadastro-card .account-type-options { display: flex; gap: 10px; }

.cadastro-card .acc-type {
    flex: 1;
    min-width: 0;
    min-height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 6px;
    border: 1px solid #e0e7e1;
    border-radius: 10px;
    font-size: 13px;
    cursor: pointer;
    box-sizing: border-box;
}

.cadastro-card .acc-radio { accent-color: #1f8a4c; }

.cadastro-card .acc-type.selected {
    border-color: #1f8a4c;
    background: #f3faf5;
}

/* ===== CAMPOS ===== */

.cadastro-card .field {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    width: 100% !important;
    height: 40px;
    margin: 0 !important;
    border: 1px solid #e0e7e1 !important;
    border-radius: 10px;
    background: #fbfcfb !important;
    box-sizing: border-box;
    overflow: hidden;
}

.cadastro-card .field:focus-within {
    border-color: #1f8a4c !important;
    background: #fff !important;
    box-shadow: 0 0 0 3px rgba(31, 138, 76, .08);
}

.cadastro-card .field .ic-left {
    position: static !important;
    left: auto !important;
    top: auto !important;
    transform: none !important;
    width: 36px;
    min-width: 36px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #659272;
}

.cadastro-card .field input {
    position: static !important;
    flex: 1;
    width: 0 !important;
    min-width: 0;
    height: 100%;
    padding: 0 10px 0 0 !important;
    margin: 0 !important;
    border: none !important;
    border-radius: 0 !important;
    outline: none !important;
    background: transparent !important;
    box-shadow: none !important;
    font-family: 'Raleway', sans-serif;
    font-size: 13px;
    font-weight: 400;
    box-sizing: border-box;
}

.cadastro-card .campo-senha input { padding-right: 38px !important; }

.cadastro-card .field .eye {
    position: absolute !important;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    align-items: center;
    color: #789182;
    cursor: pointer;
    z-index: 2;
}

/* ===== ERROS ===== */

.cadastro-card .field-error {
    display: block;
    margin: 3px 0 0 !important;
    color: #dc2626;
    font-size: 11.5px;
}

/* ===== RODAPÉ ===== */

.cadastro-card .rodape {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 2px solid #c3d3c8;
}

.cadastro-card .terms-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    font-size: 12.5px;
}

.cadastro-card .terms-row input { accent-color: #1f8a4c; flex-shrink: 0; }

.cadastro-card .terms-row a {
    color: #006b35;
    font-weight: 700;
    text-decoration: none;
}

.cadastro-card .btn-primary {
    width: 260px;
    flex-shrink: 0;
    min-height: 42px;
    margin: 0 !important;
    border-radius: 10px;
    font-size: 14px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

/* ===== RESPONSIVO (tablet e celular: uma coluna) ===== */

@media (max-width: 900px) {
    .auth-page.auth-cadastro-page { padding: 16px 12px !important; align-items: flex-start !important; }

    .cadastro-card { max-width: 620px; padding: 22px 18px; }

    .cadastro-card .colunas { grid-template-columns: 1fr; gap: 12px; }

    .cadastro-card .coluna + .coluna {
        padding-left: 0;
        border-left: none;
    }

    .cadastro-card .rodape { flex-direction: column; align-items: stretch; }
    .cadastro-card .btn-primary { width: 100%; }
}

@media (max-width: 560px) {
    .cadastro-card .c2,
    .cadastro-card .c3,
    .cadastro-card .c4 { grid-column: span 6; }

    .cadastro-card .account-type-options { flex-direction: column; }
}
</style>

<script>
document.querySelectorAll('.acc-type').forEach(function (label) {
    label.addEventListener('click', function () {
        document.querySelectorAll('.acc-type').forEach(function (item) {
            item.classList.remove('selected');
        });
        label.classList.add('selected');
        const radio = label.querySelector('.acc-radio');
        if (radio) radio.checked = true;
    });
});

function togglePass(id) {
    const campo = document.getElementById(id);
    campo.type = campo.type === 'password' ? 'text' : 'password';
}

/* ===== Máscaras: CNPJ, telefone, CEP e UF ===== */
const soNumeros = v => v.replace(/\D/g, '');

const mascaras = {
    cnpjInstituicao: v => {
        v = soNumeros(v).slice(0, 14);
        return v.replace(/^(\d{2})(\d)/, '$1.$2')
                .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                .replace(/\.(\d{3})(\d)/, '.$1/$2')
                .replace(/(\d{4})(\d)/, '$1-$2');
    },
    numTelefoneInstituicao: v => {
        v = soNumeros(v).slice(0, 11);
        if (v.length > 10) return v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
        if (v.length > 6)  return v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
        if (v.length > 2)  return v.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
        return v ? '(' + v : '';
    },
    cepEnderecoInstituicao: v => {
        v = soNumeros(v).slice(0, 8);
        return v.replace(/^(\d{5})(\d)/, '$1-$2');
    },
    ufEnderecoInstituicao: v => v.replace(/[^a-zA-Z]/g, '').toUpperCase().slice(0, 2),
};

Object.entries(mascaras).forEach(([nome, fn]) => {
    const campo = document.querySelector('[name="' + nome + '"]');
    if (!campo) return;
    campo.value = fn(campo.value);                       // aplica ao recarregar com old()
    campo.addEventListener('input', () => campo.value = fn(campo.value));
});
</script>

@endsection