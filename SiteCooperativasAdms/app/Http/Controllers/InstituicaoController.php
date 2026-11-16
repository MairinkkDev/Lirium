<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Instituicao;
use App\Models\TipoInstituicao;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InstituicaoController extends Controller
{
    public function painel(Request $request)
    {
        $nome = $request->query('nome');
        $tipo = $request->query('tipo', 'todos');
        $status = $request->query('status', 'todos');

        $instituicao = Instituicao::with([
                'tipoInstituicao',
                'telefones'
            ])
            ->when($nome, function ($query) use ($nome) {
                $query->where(
                    'nomeInstituicao',
                    'like',
                    '%' . $nome . '%'
                );
            })
            ->when($tipo !== 'todos', function ($query) use ($tipo) {
                $query->where('idTipoInstituicao', $tipo);
            })
            ->when($status !== 'todos', function ($query) use ($status) {
                $query->where('statusInstituicao', $status);
            })
            ->orderBy('nomeInstituicao')
            ->paginate(10)
            ->withQueryString();

        $totalInstituicoes = Instituicao::count();

        $instituicoesAtivas = Instituicao::where(
            'statusInstituicao',
            'ativo'
        )->count();

        $instituicoesInativas = Instituicao::where(
            'statusInstituicao',
            'inativo'
        )->count();

        $tipos = TipoInstituicao::orderBy(
            'nomeTipoInstituicao'
        )->get();

        return view('painelAdmin.instituicao')
            ->with('instituicao', $instituicao)
            ->with('nome', $nome)
            ->with('tipo', $tipo)
            ->with('status', $status)
            ->with('tipos', $tipos)
            ->with('totalInstituicoes', $totalInstituicoes)
            ->with('instituicoesAtivas', $instituicoesAtivas)
            ->with('instituicoesInativas', $instituicoesInativas);
    }

    // Exibe os dados completos da instituição.
    public function detalhe(string $idInstituicao)
{
    $instituicao = Instituicao::with([
        'tipoInstituicao',
        'telefones',
        'endereco'
    ])->findOrFail($idInstituicao);

    $tipos = TipoInstituicao::all();

    return view('painelAdmin.instituicaoDetalhada', compact(
        'instituicao',
        'tipos'
    ));
}

    // Suspende uma instituição.
    public function suspender(string $idInstituicao)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $instituicao = Instituicao::where(
            'idInstituicao',
            $idInstituicao
        )->firstOrFail();

        if ($instituicao->statusInstituicao === 'inativo') {
            return back()->with(
                'mensagem',
                "A instituição #{$idInstituicao} já estava suspensa."
            );
        }

        $instituicao->update([
            'statusInstituicao' => 'inativo'
        ]);

        return back()->with(
            'mensagem',
            "Instituição #{$idInstituicao} suspensa com sucesso."
        );
    }

    // Reativa uma instituição.
    public function reativar(string $idInstituicao)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $instituicao = Instituicao::where(
            'idInstituicao',
            $idInstituicao
        )->firstOrFail();

        if ($instituicao->statusInstituicao === 'ativo') {
            return back()->with(
                'mensagem',
                "A instituição #{$idInstituicao} já estava ativa."
            );
        }

        $instituicao->update([
            'statusInstituicao' => 'ativo'
        ]);

        return back()->with(
            'mensagem',
            "Instituição #{$idInstituicao} reativada com sucesso."
        );
    }

    // Exclui uma instituição pelo painel.
    public function excluir(string $idInstituicao)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $instituicao = Instituicao::where(
            'idInstituicao',
            $idInstituicao
        )->firstOrFail();

        $instituicao->delete();

        return back()->with(
            'mensagem',
            "Instituição #{$idInstituicao} excluída com sucesso."
        );
    }

    // Abre a tela de edição.
    public function editar(string $idInstituicao)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $instituicao = Instituicao::with([
            'tipoInstituicao',
            'telefones',
            'endereco'
        ])
        ->where('idInstituicao', $idInstituicao)
        ->firstOrFail();

        $tipos = TipoInstituicao::orderBy(
            'nomeTipoInstituicao'
        )->get();

        return view('painelAdmin.editarInstituicao')
            ->with('instituicao', $instituicao)
            ->with('tipos', $tipos);
    }

    // Salva as informações editáveis.
    public function atualizar(Request $request, string $idInstituicao)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $instituicao = Instituicao::where(
            'idInstituicao',
            $idInstituicao
        )->firstOrFail();

        $dados = $request->validate([
            'nomeInstituicao' => [
                'required',
                'string',
                'max:100'
            ],

            'idTipoInstituicao' => [
                'required',
                'integer',
                'exists:tb_tipo_instituicao,idTipoInstituicao'
            ],

            'descricaoInstituicao' => [
                'nullable',
                'string'
            ],

            'emailInstituicao' => [
                'nullable',
                'email',
                'max:150'
            ],

            'telefones' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'cepEnderecoInstituicao' => [
                'nullable',
                'string',
                'max:9'
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
        ]);

        DB::transaction(function () use ($instituicao, $dados) {

            // Atualiza os dados permitidos da instituição.
            $instituicao->update([
                'nomeInstituicao' => $dados['nomeInstituicao'],
                'idTipoInstituicao' => $dados['idTipoInstituicao'],
                'descricaoInstituicao' => $dados['descricaoInstituicao'] ?? null,
                'emailInstituicao' => $dados['emailInstituicao'] ?? null,
            ]);

            // Atualiza ou cadastra o endereço.
            $instituicao->endereco()->updateOrCreate(
                [
                    'idInstituicao' => $instituicao->idInstituicao
                ],
                [
                    'cepEnderecoInstituicao' => $dados['cepEnderecoInstituicao'] ?? null,
                    'logradouroEnderecoInstituicao' => $dados['logradouroEnderecoInstituicao'] ?? null,
                    'numeroEnderecoInstituicao' => $dados['numeroEnderecoInstituicao'] ?? null,
                    'complementoEnderecoInstituicao' => $dados['complementoEnderecoInstituicao'] ?? null,
                    'bairroEnderecoInstituicao' => $dados['bairroEnderecoInstituicao'] ?? null,
                    'cidadeEnderecoInstituicao' => $dados['cidadeEnderecoInstituicao'] ?? null,
                    'ufEnderecoInstituicao' => $dados['ufEnderecoInstituicao'] ?? null,
                ]
            );

            // Atualiza os telefones.
            $instituicao->telefones()->delete();

            $telefones = preg_split(
                '/\r\n|\r|\n/',
                $dados['telefones'] ?? ''
            );

            foreach ($telefones as $numero) {
                $numero = trim($numero);

                if ($numero !== '') {
                    $instituicao->telefones()->create([
                        'numTelefoneInstituicao' => $numero
                    ]);
                }
            }
        });

        return redirect()
            ->route('instituicao.detalhe', $idInstituicao)
            ->with(
                'mensagem',
                'Instituição atualizada com sucesso.'
            );
    }

    // Cadastro de instituição pelo formulário.
    public function cadastrar(Request $request)
    {
        $dados = $request->validate([
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

            'idTipoInstituicao' => [
                'required',
                'integer',
                'exists:tb_tipo_instituicao,idTipoInstituicao'
            ]
        ]);

        DB::transaction(function () use ($dados) {

            $instituicao = Instituicao::create([
                'idTipoInstituicao' => $dados['idTipoInstituicao'],
                'nomeInstituicao' => $dados['nomeUsuario'],
                'statusInstituicao' => 'ativo'
            ]);

            Usuario::create([
                'nomeUsuario' => $dados['nomeUsuario'],
                'emailUsuario' => $dados['emailUsuario'],
                'senhaUsuario' => Hash::make($dados['senhaUsuario']),
                'statusUsuario' => 'ativo',
                'idInstituicao' => $instituicao->idInstituicao
            ]);
        });

        return redirect()->route('login')
            ->with(
                'mensagem',
                'Cadastro da instituição realizado! Faça login para continuar.'
            );
    }

    // Listagem da API.
    public function index()
    {
        return response()->json(
            Instituicao::with([
                'tipoInstituicao',
                'telefones'
            ])->get()
        );
    }

    // Consulta de uma instituição pela chave correta.
    public function show($idInstituicao)
    {
        $instituicao = Instituicao::with([
            'tipoInstituicao',
            'telefones',
            'endereco'
        ])
        ->where('idInstituicao', $idInstituicao)
        ->firstOrFail();

        return response()->json($instituicao);
    }

    // Cadastro pela API.
    public function store(Request $request)
    {
        $data = $request->validate([
            'idTipoInstituicao' => [
                'required',
                'integer',
                'exists:tb_tipo_instituicao,idTipoInstituicao'
            ],

            'nomeInstituicao' => [
                'required',
                'string',
                'max:100'
            ],

            'descricaoInstituicao' => [
                'nullable',
                'string'
            ],

            'statusInstituicao' => [
                'sometimes',
                'in:ativo,inativo'
            ],

            'cnpjInstituicao' => [
                'nullable',
                'string',
                'max:18'
            ],

            'emailInstituicao' => [
                'nullable',
                'email',
                'max:150'
            ]
        ]);

        $data['statusInstituicao'] = $data['statusInstituicao'] ?? 'ativo';

        $instituicao = Instituicao::create($data);

        return response()->json($instituicao, 201);
    }

    // Exclusão pela API.
    public function destroy(string $idInstituicao)
    {
        $instituicao = Instituicao::where(
            'idInstituicao',
            $idInstituicao
        )->firstOrFail();

        $instituicao->delete();

        return response()->json([
            'message' => 'Instituição removida com sucesso.'
        ]);
    }
}
