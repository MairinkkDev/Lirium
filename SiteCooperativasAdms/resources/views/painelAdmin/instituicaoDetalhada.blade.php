
@extends('layouts.admin')

@section('titulo', 'Instituição #' . $instituicao->idInstituicao)

@section('content')

@php
    $endereco = $instituicao->endereco;
    $modoEdicao = $errors->any();
@endphp

<div class="inst-detalhe-page">

    <a class="inst-voltar" href="{{ route('admin.instituicoes') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Voltar para instituições
    </a>

    <form id="formInstituicao"
        action="{{ route('instituicao.atualizar', $instituicao->idInstituicao) }}"
        method="POST"
        class="inst-form-card">

        @csrf
        @method('PUT')

        <h1 class="inst-form-title">{{ $instituicao->nomeInstituicao }}</h1>

        <p class="inst-form-sub">
            Visualize e gerencie as informações da instituição.
        </p>

        <div class="inst-status">
            <span class="badge-status {{ $instituicao->statusInstituicao === 'ativo' ? 'active' : 'suspended' }}">
                {{ $instituicao->statusInstituicao === 'ativo' ? 'Ativa' : 'Inativa' }}
            </span>
        </div>

        @if(session('erro'))
            <div class="aviso-erro">{{ session('erro') }}</div>
        @endif

        @if($errors->any())
            <div class="aviso-erro">
                <strong>Verifique os seguintes erros:</strong>
                <ul>
                    @foreach($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h3 class="inst-section-title">Informações da instituição</h3>

        <div class="inst-form-grid">

            <div>
                <label class="inst-label">Código</label>
                <input type="text" class="inst-input"
                    value="#{{ $instituicao->idInstituicao }}" disabled>
            </div>

            <div>
                <label class="inst-label" for="nomeInstituicao">Nome da instituição</label>
                <input type="text" id="nomeInstituicao" name="nomeInstituicao"
                    class="inst-input @error('nomeInstituicao') invalido @enderror"
                    value="{{ old('nomeInstituicao', $instituicao->nomeInstituicao) }}"
                    maxlength="150"
                    required
                    @disabled(!$modoEdicao)>
                @error('nomeInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="inst-label" for="idTipoInstituicao">Tipo de instituição</label>
                <select id="idTipoInstituicao" name="idTipoInstituicao"
                    class="inst-input @error('idTipoInstituicao') invalido @enderror"
                    required
                    @disabled(!$modoEdicao)>

                    <option value="">Selecione o tipo</option>

                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->idTipoInstituicao }}"
                            @selected(old('idTipoInstituicao', $instituicao->idTipoInstituicao) == $tipo->idTipoInstituicao)>
                            {{ $tipo->nomeTipoInstituicao }}
                        </option>
                    @endforeach

                </select>
                @error('idTipoInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="inst-label" for="cnpjInstituicao">CNPJ</label>
                <input type="text" id="cnpjInstituicao"
                    class="inst-input"
                    value="{{ $instituicao->cnpjInstituicao ?? 'Não informado' }}"
                    disabled>
                <small class="inst-ajuda">Dado protegido: não pode ser alterado.</small>
            </div>

            <div class="full">
                <label class="inst-label" for="emailInstituicao">E-mail</label>
                <input type="email" id="emailInstituicao" name="emailInstituicao"
                    class="inst-input @error('emailInstituicao') invalido @enderror"
                    value="{{ old('emailInstituicao', $instituicao->emailInstituicao) }}"
                    maxlength="100"
                    @disabled(!$modoEdicao)>
                @error('emailInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div class="full">
                <label class="inst-label" for="descricaoInstituicao">Descrição</label>
                <textarea id="descricaoInstituicao" name="descricaoInstituicao"
                    class="inst-input inst-textarea @error('descricaoInstituicao') invalido @enderror"
                    rows="4"
                    @disabled(!$modoEdicao)>{{ old('descricaoInstituicao', $instituicao->descricaoInstituicao) }}</textarea>
                @error('descricaoInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div class="full">
                <label class="inst-label">Data de cadastro</label>
                <input type="text" class="inst-input"
                    value="{{ $instituicao->created_at ? $instituicao->created_at->format('d/m/Y H:i') : 'Não informada' }}"
                    disabled>
            </div>

        </div>

        <hr class="inst-form-sep">

        <h3 class="inst-section-title">Telefones</h3>

        <div class="inst-form-grid">
            <div class="full">
                <label class="inst-label" for="telefones">
                    Telefones da instituição
                </label>

                <textarea id="telefones" name="telefones"
                    class="inst-input inst-textarea @error('telefones') invalido @enderror"
                    rows="4"
                    placeholder="Digite um telefone por linha"
                    @disabled(!$modoEdicao)>{{ old('telefones', $instituicao->telefones->pluck('numTelefoneInstituicao')->implode("\n")) }}</textarea>

                <small class="inst-ajuda">
                    Informe um telefone por linha.
                </small>

                @error('telefones')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <hr class="inst-form-sep">

        <h3 class="inst-section-title">Endereço</h3>

        <div class="inst-form-grid">

            <div>
                <label class="inst-label" for="cepEnderecoInstituicao">CEP</label>
                <input type="text" id="cepEnderecoInstituicao"
                    name="cepEnderecoInstituicao"
                    class="inst-input @error('cepEnderecoInstituicao') invalido @enderror"
                    value="{{ old('cepEnderecoInstituicao', optional($endereco)->cepEnderecoInstituicao) }}"
                    maxlength="9"
                    placeholder="00000-000"
                    @disabled(!$modoEdicao)>
                @error('cepEnderecoInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="inst-label" for="logradouroEnderecoInstituicao">Logradouro</label>
                <input type="text" id="logradouroEnderecoInstituicao"
                    name="logradouroEnderecoInstituicao"
                    class="inst-input @error('logradouroEnderecoInstituicao') invalido @enderror"
                    value="{{ old('logradouroEnderecoInstituicao', optional($endereco)->logradouroEnderecoInstituicao) }}"
                    @disabled(!$modoEdicao)>
                @error('logradouroEnderecoInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="inst-label" for="numeroEnderecoInstituicao">Número</label>
                <input type="text" id="numeroEnderecoInstituicao"
                    name="numeroEnderecoInstituicao"
                    class="inst-input @error('numeroEnderecoInstituicao') invalido @enderror"
                    value="{{ old('numeroEnderecoInstituicao', optional($endereco)->numeroEnderecoInstituicao) }}"
                    @disabled(!$modoEdicao)>
                @error('numeroEnderecoInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="inst-label" for="complementoEnderecoInstituicao">Complemento</label>
                <input type="text" id="complementoEnderecoInstituicao"
                    name="complementoEnderecoInstituicao"
                    class="inst-input @error('complementoEnderecoInstituicao') invalido @enderror"
                    value="{{ old('complementoEnderecoInstituicao', optional($endereco)->complementoEnderecoInstituicao) }}"
                    @disabled(!$modoEdicao)>
                @error('complementoEnderecoInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="inst-label" for="bairroEnderecoInstituicao">Bairro</label>
                <input type="text" id="bairroEnderecoInstituicao"
                    name="bairroEnderecoInstituicao"
                    class="inst-input @error('bairroEnderecoInstituicao') invalido @enderror"
                    value="{{ old('bairroEnderecoInstituicao', optional($endereco)->bairroEnderecoInstituicao) }}"
                    @disabled(!$modoEdicao)>
                @error('bairroEnderecoInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="inst-label" for="cidadeEnderecoInstituicao">Cidade</label>
                <input type="text" id="cidadeEnderecoInstituicao"
                    name="cidadeEnderecoInstituicao"
                    class="inst-input @error('cidadeEnderecoInstituicao') invalido @enderror"
                    value="{{ old('cidadeEnderecoInstituicao', optional($endereco)->cidadeEnderecoInstituicao) }}"
                    @disabled(!$modoEdicao)>
                @error('cidadeEnderecoInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="inst-label" for="ufEnderecoInstituicao">Estado (UF)</label>
                <input type="text" id="ufEnderecoInstituicao"
                    name="ufEnderecoInstituicao"
                    class="inst-input @error('ufEnderecoInstituicao') invalido @enderror"
                    value="{{ old('ufEnderecoInstituicao', optional($endereco)->ufEnderecoInstituicao) }}"
                    maxlength="2"
                    placeholder="SP"
                    @disabled(!$modoEdicao)>
                @error('ufEnderecoInstituicao')
                    <span class="inst-erro">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="inst-form-actions">

            <button type="button" id="btnAlterar"
                class="btn-form salvar"
                @if($modoEdicao) hidden @endif>
                Alterar informações
            </button>

            <button type="button" id="btnCancelar"
                class="btn-form cancelar"
                @unless($modoEdicao) hidden @endunless>
                Cancelar
            </button>

            <button type="submit" id="btnSalvar"
                class="btn-form salvar"
                @unless($modoEdicao) hidden @endunless>
                Salvar alterações
            </button>

        </div>

    </form>

    <div class="inst-admin-actions">

        @if($instituicao->statusInstituicao === 'ativo')

            <form action="{{ route('instituicao.suspender', $instituicao->idInstituicao) }}"
                method="POST"
                data-confirmar="Suspender a instituição {{ $instituicao->nomeInstituicao }}?">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn-form suspender">
                    Suspender instituição
                </button>
            </form>

        @else

            <form action="{{ route('instituicao.reativar', $instituicao->idInstituicao) }}"
                method="POST">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn-form salvar">
                    Reativar instituição
                </button>
            </form>

        @endif

        <form action="{{ route('instituicao.excluir', $instituicao->idInstituicao) }}"
            method="POST"
            data-confirmar="Excluir a instituição {{ $instituicao->nomeInstituicao }}? Esta ação não pode ser desfeita.">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn-form excluir">
                Excluir instituição
            </button>
        </form>

    </div>

</div>

<style>
    .inst-detalhe-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .inst-voltar {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 20px;
        color: #287a43;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .inst-voltar svg {
        width: 18px;
        height: 18px;
    }

    .inst-form-card {
        width: 100%;
        box-sizing: border-box;
        padding: 30px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
    }

    .inst-form-title {
        margin: 0 0 7px;
        color: #183d29;
        font-size: 25px;
        font-weight: 700;
    }

    .inst-form-sub {
        margin: 0 0 22px;
        color: #777;
        font-size: 13px;
    }

    .inst-status {
        margin-bottom: 24px;
    }

    .inst-section-title {
        margin: 8px 0 18px;
        color: #244c34;
        font-size: 16px;
        font-weight: 700;
    }

    .inst-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .inst-form-grid .full {
        grid-column: 1 / -1;
    }

    .inst-label {
        display: block;
        margin-bottom: 7px;
        color: #34483b;
        font-size: 13px;
        font-weight: 600;
    }

    .inst-input {
        display: block;
        width: 100%;
        min-height: 43px;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid #d5dfd7;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: #34483b;
        font-family: inherit;
        font-size: 14px;
    }

    .inst-input:focus {
        border-color: #278447;
        box-shadow: 0 0 0 3px rgba(39, 132, 71, .10);
    }

    .inst-input:disabled {
        background: #f8faf8;
        color: #39483e;
        opacity: 1;
        cursor: default;
    }

    .inst-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .inst-ajuda {
        display: block;
        margin-top: 6px;
        color: #777;
        font-size: 11px;
    }

    .inst-erro {
        display: block;
        margin-top: 5px;
        color: #c03939;
        font-size: 12px;
    }

    .inst-input.invalido {
        border-color: #c03939;
    }

    .inst-form-sep {
        margin: 25px 0;
        border: 0;
        border-top: 1px solid #e5ebe6;
    }

    .inst-form-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    .btn-form {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 20px;
        border: 0;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-form[hidden] {
        display: none !important;
    }

    .btn-form.cancelar {
        background: #edf1ed;
        color: #405247;
    }

    .btn-form.salvar {
        background: #278447;
        color: #fff;
    }

    .btn-form.suspender,
    .btn-form.excluir {
        background: #fff;
        border: 1px solid #edcaca;
        color: #bd3737;
    }

    .inst-admin-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 18px;
    }

    .aviso-erro {
        margin-bottom: 18px;
        padding: 10px 14px;
        border-radius: 8px;
        background: #fde2e2;
        color: #9b1c1c;
        font-size: 12px;
    }

    .aviso-erro ul {
        margin: 6px 0 0;
        padding-left: 20px;
    }

    @media (max-width: 650px) {
        .inst-form-card {
            padding: 20px;
        }

        .inst-form-grid {
            grid-template-columns: 1fr;
        }

        .inst-form-grid .full {
            grid-column: auto;
        }

        .inst-form-actions,
        .inst-admin-actions {
            flex-direction: column;
        }

        .btn-form {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('formInstituicao');
        const btnAlterar = document.getElementById('btnAlterar');
        const btnCancelar = document.getElementById('btnCancelar');
        const btnSalvar = document.getElementById('btnSalvar');

        const camposEditaveis = form.querySelectorAll(
            'input[name], select[name], textarea[name]'
        );

        const camposProtegidos = ['_token', '_method'];

        function liberarEdicao() {
            camposEditaveis.forEach(function (campo) {
                if (!camposProtegidos.includes(campo.name)) {
                    campo.disabled = false;
                }
            });

            btnAlterar.hidden = true;
            btnCancelar.hidden = false;
            btnSalvar.hidden = false;
        }

        function cancelarEdicao() {
            window.location.reload();
        }

        btnAlterar.addEventListener('click', liberarEdicao);
        btnCancelar.addEventListener('click', cancelarEdicao);

    });
</script>

@endsection
