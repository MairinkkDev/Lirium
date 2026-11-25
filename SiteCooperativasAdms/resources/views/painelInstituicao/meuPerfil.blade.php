@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.instituicao')

@section('titulo', 'Editar perfil')

@section('content')
  @php
    $ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];

    if (isset($usuario)) {
      $instituicao = $usuario->instituicao;
      $endereco    = $instituicao?->endereco;
      $p = [
        'nome'        => $usuario->nomeUsuario,
        'email'       => $usuario->emailUsuario,
        'nomeCurto'   => $instituicao?->nomeInstituicao ?: $usuario->nomeUsuario,
        'cnpj'        => $instituicao?->getAttribute('cnpjInstituicao'),
        'temEndereco' => (bool) $instituicao,
        'cep'         => $endereco?->cepEnderecoInstituicao,
        'cidade'      => $endereco?->cidadeEnderecoInstituicao,
        'uf'          => $endereco?->ufEnderecoInstituicao,
        'logradouro'  => $endereco?->logradouroEnderecoInstituicao,
        'numero'      => $endereco?->numeroEnderecoInstituicao,
        // foto da instituição, ou do usuário se não for instituição
        'foto'        => $instituicao ? $instituicao->foto_url : $usuario->foto_url,
      ];
    } else {
      $p = [
        'nome'        => 'Cooperativa Sonhos Bons',
        'email'       => 'contatoSonhosBons@gmail.com',
        'nomeCurto'   => 'Sonhos Bons',
        'cnpj'        => '33.000.167/0001-01',
        'temEndereco' => true,
        'cep'         => '08400-147',
        'cidade'      => 'São Paulo',
        'uf'          => 'SP',
        'logradouro'  => 'Rua do retorno das laranjeiras',
        'numero'      => '54',
        'foto'        => null,
      ];
    }

    $partes   = preg_split('/\s+/', trim($p['nomeCurto']));
    $iniciais = mb_strtoupper(mb_substr($partes[0], 0, 1) . (count($partes) > 1 ? mb_substr(end($partes), 0, 1) : mb_substr($partes[0], 1, 1)));
  @endphp

  <div class="perfil-card">

    <form action="{{ route('perfil.atualizar') }}" method="post" enctype="multipart/form-data" class="perfil-form"
      novalidate>
      @csrf
      @method('PATCH')

      <h1 class="perfil-titulo">Editar Perfil</h1>

      <div class="perfil-campos">

        <div>
          <label class="perfil-campo {{ $errors->has('nomeUsuario') ? 'invalido' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <circle cx="12" cy="12" r="10" />
              <circle cx="12" cy="10" r="3.2" />
              <path d="M5.8 19c1.2-2.6 3.5-4 6.2-4s5 1.4 6.2 4" />
            </svg>
            <input type="text" name="nomeUsuario" maxlength="100" aria-label="Nome" placeholder="Nome"
              value="{{ old('nomeUsuario', $p['nome']) }}" required>
          </label>
          @error('nomeUsuario') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="perfil-campo {{ $errors->has('emailUsuario') ? 'invalido' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <circle cx="12" cy="12" r="10" />
              <circle cx="12" cy="10" r="3.2" />
              <path d="M5.8 19c1.2-2.6 3.5-4 6.2-4s5 1.4 6.2 4" />
            </svg>
            <input type="email" name="emailUsuario" maxlength="100" aria-label="E-mail" placeholder="E-mail"
              value="{{ old('emailUsuario', $p['email']) }}" required>
          </label>
          @error('emailUsuario') <span class="inst-erro">{{ $message }}</span> @enderror
        </div>

        @if($p['temEndereco'])
          <div class="perfil-bloco" role="group" aria-labelledby="tituloEndereco">
            <div class="perfil-bloco-cab" id="tituloEndereco">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="10" />
                <circle cx="12" cy="10" r="3.2" />
                <path d="M5.8 19c1.2-2.6 3.5-4 6.2-4s5 1.4 6.2 4" />
              </svg>
              Endereço
            </div>

            <div class="perfil-end-grid">
              <div>
                <label for="cepEnderecoInstituicao">CEP</label>
                <input type="text" id="cepEnderecoInstituicao" name="cepEnderecoInstituicao" inputmode="numeric"
                  maxlength="9" value="{{ old('cepEnderecoInstituicao', $p['cep']) }}">
                @error('cepEnderecoInstituicao') <span class="inst-erro">{{ $message }}</span> @enderror
              </div>

              <div>
                <label for="cidadeEnderecoInstituicao">Cidade</label>
                <input type="text" id="cidadeEnderecoInstituicao" name="cidadeEnderecoInstituicao" maxlength="100"
                  value="{{ old('cidadeEnderecoInstituicao', $p['cidade']) }}">
                @error('cidadeEnderecoInstituicao') <span class="inst-erro">{{ $message }}</span> @enderror
              </div>

              <div>
                <label for="ufEnderecoInstituicao">UF</label>
                <select id="ufEnderecoInstituicao" name="ufEnderecoInstituicao">
                  <option value="" hidden></option>
                  @foreach($ufs as $uf)
                    <option value="{{ $uf }}" {{ old('ufEnderecoInstituicao', $p['uf']) === $uf ? 'selected' : '' }}>{{ $uf }}</option>
                  @endforeach
                </select>
                @error('ufEnderecoInstituicao') <span class="inst-erro">{{ $message }}</span> @enderror
              </div>

              <div class="span-2">
                <label for="logradouroEnderecoInstituicao">Endereço</label>
                <input type="text" id="logradouroEnderecoInstituicao" name="logradouroEnderecoInstituicao"
                  maxlength="150" value="{{ old('logradouroEnderecoInstituicao', $p['logradouro']) }}">
                @error('logradouroEnderecoInstituicao') <span class="inst-erro">{{ $message }}</span> @enderror
              </div>

              <div>
                <label for="numeroEnderecoInstituicao">Número</label>
                <input type="text" id="numeroEnderecoInstituicao" name="numeroEnderecoInstituicao" maxlength="10"
                  value="{{ old('numeroEnderecoInstituicao', $p['numero']) }}">
                @error('numeroEnderecoInstituicao') <span class="inst-erro">{{ $message }}</span> @enderror
              </div>
            </div>
          </div>
        @endif

        <label class="perfil-foto" for="fotoPerfil">
          <span class="perfil-foto-mini" id="miniFoto">
            @if($p['foto'])
              <img src="{{ $p['foto'] }}" alt="Foto de perfil">
            @else
              {{ $iniciais }}
            @endif
          </span>
          Alterar Foto
          <span class="perfil-foto-nome" id="nomeFoto"></span>
          <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/png,image/jpeg,image/webp">
        </label>
        @error('fotoPerfil') <span class="inst-erro">{{ $message }}</span> @enderror

      </div>

      <div class="perfil-acoes">
        <button type="submit" class="perfil-btn salvar">Salvar</button>
       <a href="{{ auth()->user()->isAdmin() ? route('admin.home') : url('/estoque') }}"
   class="perfil-btn cancelar">
    Cancelar
</a>
      </div>
    </form>

    <aside class="perfil-lateral" aria-label="Resumo do perfil">
      <div class="perfil-avatar" id="avatarFoto" aria-hidden="true">
        @if($p['foto'])
          <img src="{{ $p['foto'] }}" alt="">
        @else
          {{ $iniciais }}
        @endif
      </div>
      <h2>{{ $p['nomeCurto'] }}</h2>
      <p>{{ $p['email'] }}</p>
      @if($p['cnpj'])
        <p>{{ $p['cnpj'] }}</p>
      @endif
    </aside>

  </div>

  <style>
    .alert-ok { padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 13px; background: #dff5e3; color: #1b5e2b; }
    .perfil-foto-mini, .perfil-avatar { overflow: hidden; }
    .perfil-foto-mini img, .perfil-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block; }

    /* ===== Aumento de tamanho da tela de perfil ===== */

    .perfil-card { width: 100%; max-width: none; }

    .perfil-titulo {
      font-size: 30px !important;
      font-weight: 750;
      margin-bottom: 26px;
    }

    /* campos de nome e e-mail */
    .perfil-campos { gap: 20px; }

    .perfil-campo {
      min-height: 60px;
      padding: 0 18px;
      gap: 14px;
      border-radius: 12px;
    }

    .perfil-campo svg {
      width: 28px !important;
      height: 28px !important;
      flex-shrink: 0;
    }

    .perfil-campo input {
      font-size: 17px !important;
      font-weight: 500;
    }

    /* bloco de endereço */
    .perfil-bloco { padding: 22px 24px; border-radius: 12px; }

    .perfil-bloco-cab {
      gap: 12px;
      margin-bottom: 18px;
      font-size: 18px !important;
      font-weight: 700;
    }

    .perfil-bloco-cab svg { width: 26px !important; height: 26px !important; }

    .perfil-end-grid { gap: 16px; }

    .perfil-end-grid label {
      margin-bottom: 6px;
      font-size: 15px !important;
      font-weight: 700;
    }

    .perfil-end-grid input,
    .perfil-end-grid select {
      min-height: 48px;
      padding: 10px 14px;
      font-size: 16px !important;
      border-radius: 9px;
    }

    /* foto */
    .perfil-foto {
      gap: 14px;
      font-size: 17px !important;
      font-weight: 600;
    }

    .perfil-foto-mini {
      width: 52px !important;
      height: 52px !important;
      font-size: 18px !important;
      flex-shrink: 0;
    }

    .perfil-foto-nome { font-size: 14px !important; }

    /* botões */
    .perfil-acoes { gap: 16px; margin-top: 8px; }

    .perfil-btn {
      min-height: 52px;
      padding: 0 34px !important;
      font-size: 17px !important;
      font-weight: 700;
      border-radius: 12px;
    }

    /* lateral com resumo */
    .perfil-avatar {
      width: 150px !important;
      height: 150px !important;
      font-size: 46px !important;
    }

    .perfil-lateral h2 {
      margin-top: 18px;
      font-size: 24px !important;
      font-weight: 750;
    }

    .perfil-lateral p {
      font-size: 17px !important;
      line-height: 1.5;
    }

    /* mensagens de erro */
    .perfil-card .inst-erro { font-size: 14px !important; }

    @media (max-width: 700px) {
      .perfil-titulo { font-size: 25px !important; }
      .perfil-avatar { width: 110px !important; height: 110px !important; font-size: 36px !important; }
      .perfil-btn { padding: 0 22px !important; }
    }
  </style>

  <script>
    const iniciais = @json($iniciais);

    // CEP: 00000-000
    const cep = document.getElementById('cepEnderecoInstituicao');
    if (cep) {
      const maskCep = v => v.replace(/\D/g, '').slice(0, 8).replace(/^(\d{5})(\d)/, '$1-$2');
      cep.value = maskCep(cep.value);
      cep.addEventListener('input', () => cep.value = maskCep(cep.value));
    }

    // Mostra a foto escolhida no lugar das iniciais antes de salvar
    document.getElementById('fotoPerfil')?.addEventListener('change', function () {
      const arquivo = this.files[0];
      document.getElementById('nomeFoto').textContent = arquivo ? arquivo.name : '';

      [document.getElementById('miniFoto'), document.getElementById('avatarFoto')].forEach(el => {
        if (!el) return;
        if (arquivo) {
          el.innerHTML = '<img alt="">';
          el.firstChild.src = URL.createObjectURL(arquivo);
        } else {
          el.textContent = iniciais;
        }
      });
    });
  </script>
@endsection