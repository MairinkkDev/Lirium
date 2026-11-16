<?php

namespace App\Http\Controllers;

use App\Models\EnderecoInstituicao;
use App\Models\ImagemInstituicao;
use App\Models\ImagemUsuario;
use App\Models\TelefoneInstituicao;
use App\Models\Usuario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuario = Usuario::all();

        return $usuario;
    }

    public function show(string $idUsuario)
    {
        $usuario = Usuario::where('idUsuario', $idUsuario)->get();

        return $usuario;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomeUsuario' => [
                'required',
                'string',
                'max:100'
            ],

            'emailUsuario' => [
                'required',
                'email',
                'max:100',
                'unique:tb_usuario,emailUsuario'
            ],

            'senhaUsuario' => [
                'required',
                'string',
                'min:6'
            ],
        ]);

        $data['senhaUsuario'] = Hash::make($data['senhaUsuario']);

        Usuario::create($data);

        return redirect()->route('login')
            ->with('mensagem', 'Cadastro realizado! Faça login para continuar.');
    }

    /* ===================== PERFIL ===================== */

    public function perfil()
    {
        $usuario = Auth::user();

        $usuario->load([
            'imagem',
            'instituicao.imagem',
            'instituicao.endereco',
            'instituicao.telefones'
        ]);

        $view = $usuario->isInstituicao()
            ? 'painelInstituicao.meuPerfil'
            : 'meuPerfil';

        return view($view)->with('usuario', $usuario);
    }

    public function atualizarPerfil(Request $request)
    {
        $usuario = Auth::user();
        $instituicao = $usuario->instituicao;

        // Remove a máscara do telefone antes da validação.
        if ($request->has('numTelefoneInstituicao')) {
            $request->merge([
                'numTelefoneInstituicao' => preg_replace(
                    '/\D/',
                    '',
                    $request->input('numTelefoneInstituicao', '')
                ),
            ]);
        }

        $regras = [
            'nomeUsuario' => [
                'required',
                'string',
                'max:100'
            ],

            'emailUsuario' => [
                'required',
                'email',
                'max:100',
                'unique:tb_usuario,emailUsuario,' . $usuario->idUsuario . ',idUsuario'
            ],

            'fotoPerfil' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:2048'
            ],
        ];

        // Campos adicionais quando o usuário pertence a uma instituição.
        if ($instituicao) {
            $regras += [
                'numTelefoneInstituicao' => [
                    'nullable',
                    'digits_between:10,11'
                ],

                'cepEnderecoInstituicao' => [
                    'nullable',
                    'regex:/^\d{5}-?\d{3}$/'
                ],

                'cidadeEnderecoInstituicao' => [
                    'nullable',
                    'string',
                    'max:100'
                ],

                'ufEnderecoInstituicao' => [
                    'nullable',
                    'string',
                    'size:2'
                ],

                'logradouroEnderecoInstituicao' => [
                    'nullable',
                    'string',
                    'max:150'
                ],

                'numeroEnderecoInstituicao' => [
                    'nullable',
                    'string',
                    'max:10'
                ],

                'complementoEnderecoInstituicao' => [
                    'nullable',
                    'string',
                    'max:100'
                ],

                'bairroEnderecoInstituicao' => [
                    'nullable',
                    'string',
                    'max:100'
                ],
            ];
        }

        $dados = $request->validate($regras, [
            'fotoPerfil.image' => 'O arquivo precisa ser uma imagem.',
            'fotoPerfil.mimes' => 'Use uma imagem PNG, JPG ou WEBP.',
            'fotoPerfil.max' => 'A foto deve ter no máximo 2 MB.',
            'cepEnderecoInstituicao.regex' => 'CEP inválido (use 00000-000).',
            'numTelefoneInstituicao.digits_between' => 'O telefone deve possuir DDD e número válido.',
        ]);

        // Atualizar os dados da conta.
        $usuario->update([
            'nomeUsuario' => $dados['nomeUsuario'],
            'emailUsuario' => $dados['emailUsuario'],
        ]);

        // Atualizar a foto de perfil.
        if ($request->hasFile('fotoPerfil')) {
            $novo = $request->file('fotoPerfil')->store('perfis', 'public');

            $antiga = null;

            if ($instituicao) {
                $img = ImagemInstituicao::where(
                    'idInstituicao',
                    $instituicao->idInstituicao
                )->first();

                $antiga = $img
                    ? $img->caminhoImagemInstituicao
                    : null;

                ImagemInstituicao::updateOrCreate(
                    ['idInstituicao' => $instituicao->idInstituicao],
                    ['caminhoImagemInstituicao' => $novo]
                );
            } else {
                $img = ImagemUsuario::where(
                    'idUsuario',
                    $usuario->idUsuario
                )->first();

                $antiga = $img
                    ? $img->caminhoImagemUsuario
                    : null;

                ImagemUsuario::updateOrCreate(
                    ['idUsuario' => $usuario->idUsuario],
                    ['caminhoImagemUsuario' => $novo]
                );
            }

            if ($antiga) {
                Storage::disk('public')->delete($antiga);
            }
        }

        // Atualizar telefone da instituição.
        if (
            $instituicao &&
            $request->has('numTelefoneInstituicao') &&
            !empty($dados['numTelefoneInstituicao'])
        ) {
            $telefone = TelefoneInstituicao::where(
                'idInstituicao',
                $instituicao->idInstituicao
            )->first();

            if (!$telefone) {
                $telefone = new TelefoneInstituicao();
            }

            $telefone->forceFill([
                'idInstituicao' => $instituicao->idInstituicao,
                'numTelefoneInstituicao' => $dados['numTelefoneInstituicao'],
            ])->save();
        }

        // Atualizar ou cadastrar endereço da instituição.
        if ($instituicao) {
            $camposEndereco = [
                'cepEnderecoInstituicao',
                'logradouroEnderecoInstituicao',
                'numeroEnderecoInstituicao',
                'complementoEnderecoInstituicao',
                'bairroEnderecoInstituicao',
                'cidadeEnderecoInstituicao',
                'ufEnderecoInstituicao',
            ];

            $recebeuEndereco = false;

            foreach ($camposEndereco as $campo) {
                if ($request->has($campo)) {
                    $recebeuEndereco = true;
                    break;
                }
            }

            if ($recebeuEndereco) {
                $endereco = EnderecoInstituicao::where(
                    'idInstituicao',
                    $instituicao->idInstituicao
                )->first();

                if (!$endereco) {
                    $endereco = new EnderecoInstituicao();
                }

                $endereco->forceFill([
                    'idInstituicao' => $instituicao->idInstituicao,
                    'cepEnderecoInstituicao' => $dados['cepEnderecoInstituicao'] ?? null,
                    'logradouroEnderecoInstituicao' => $dados['logradouroEnderecoInstituicao'] ?? null,
                    'numeroEnderecoInstituicao' => $dados['numeroEnderecoInstituicao'] ?? null,
                    'complementoEnderecoInstituicao' => $dados['complementoEnderecoInstituicao'] ?? null,
                    'bairroEnderecoInstituicao' => $dados['bairroEnderecoInstituicao'] ?? null,
                    'cidadeEnderecoInstituicao' => $dados['cidadeEnderecoInstituicao'] ?? null,
                    'ufEnderecoInstituicao' => isset($dados['ufEnderecoInstituicao'])
                        ? strtoupper($dados['ufEnderecoInstituicao'])
                        : null,
                ])->save();
            }
        }

        return redirect()->route('perfil.show')
            ->with('mensagem', 'Perfil atualizado com sucesso.');
    }

    /* ===================== LOGIN / LOGOUT ===================== */

  public function fazerLogin(Request $request)
{
    $dados = $request->validate([
        'emailUsuario' => ['required', 'email'],
        'senhaUsuario' => ['required', 'string'],
    ]);

    if (!Auth::attempt([
        'emailUsuario' => $dados['emailUsuario'],
        'password'     => $dados['senhaUsuario'],
    ])) {
        return back()
            ->withErrors(['emailUsuario' => 'E-mail ou senha incorretos.'])
            ->onlyInput('emailUsuario');
    }

    $request->session()->regenerate();

    $usuario = Auth::user();

    // Conta inativa
    if ($usuario->statusUsuario !== 'ativo') {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'emailUsuario' => 'Esta conta está inativa.',
        ]);
    }

    // Instituição suspensa
    if (
        $usuario->isInstituicao() &&
        $usuario->instituicao &&
        $usuario->instituicao->statusInstituicao === 'inativo'
    ) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'emailUsuario' => 'Sua instituição está suspensa. Entre em contato com o suporte.',
        ]);
    }

    // Redirecionamento por tipo de conta
    if ($usuario->isAdmin()) {
        return redirect()->route('admin.home');
    }

    if ($usuario->isInstituicao()) {
        return redirect()->route('estoque.painel');
    }

    return redirect()->route('perfil.show');
}
    public function fazerLogOut(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /* ===================== CRUD ===================== */

    public function update(Request $request, string $id)
    {
        $validarDados = $request->validate([
            'nomeUsuario' => [
                'sometimes',
                'string',
                'min:3',
                'max:100'
            ],

            'emailUsuario' => [
                'sometimes',
                'email',
                'max:100'
            ],

            'senhaUsuario' => [
                'sometimes',
                'string',
                'max:40'
            ],

            'statusUsuario' => [
                'sometimes',
                'string',
                'max:40'
            ],
        ]);

        if (isset($validarDados['senhaUsuario'])) {
            $validarDados['senhaUsuario'] = Hash::make(
                $validarDados['senhaUsuario']
            );
        }

        $usuario = Usuario::findOrFail($id);

        $usuario->update($validarDados);

        return response()->json([
            'mensagem' => 'Dados alterados com sucesso',
            'usuario' => $usuario,
        ], 200);
    }

    public function destroy(string $idUsuario)
    {
        Usuario::where('idUsuario', $idUsuario)->delete();

        return response()->json([
            'message' => 'Usuário removido com sucesso.'
        ]);
    }
}