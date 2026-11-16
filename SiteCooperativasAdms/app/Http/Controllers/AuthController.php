<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Usuario;
use App\Models\Instituicao;
use App\Models\TipoInstituicao;
use App\Models\NivelAcesso;
use App\Models\TelefoneInstituicao;
use App\Models\EnderecoInstituicao;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('login');
    }

    public function mostrarCadastro()
    {
        return view('cadastro');
    }

    public function cadastrar(Request $request)
    {
        // Remove a máscara do telefone antes de validar e salvar.
        $request->merge([
            'numTelefoneInstituicao' => preg_replace(
                '/\D/',
                '',
                $request->input('numTelefoneInstituicao', '')
            ),
        ]);

        // Validar os dados recebidos do formulário.
        $dados = $request->validate([
            'tipoInstituicao' => [
                'required',
                'in:cooperativa,orgao_publico'
            ],

            'nomeInstituicao' => [
                'required',
                'string',
                'max:150'
            ],

            'cnpjInstituicao' => [
                'required',
                'string',
                'max:18',
                'unique:tb_instituicao,cnpjInstituicao'
            ],

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

            'numTelefoneInstituicao' => [
                'required',
                'digits_between:10,11'
            ],

            'senhaUsuario' => [
                'required',
                'string',
                'min:8'
            ],

            'aceitaTermos' => [
                'accepted'
            ],

            // Endereço opcional.
            'cepEnderecoInstituicao' => [
                'nullable',
                'regex:/^\d{5}-?\d{3}$/'
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
        ], [
            'numTelefoneInstituicao.required' => 'Informe o telefone da organização.',
            'numTelefoneInstituicao.digits_between' => 'O telefone deve possuir DDD e número válido.',
            'cepEnderecoInstituicao.regex' => 'Informe um CEP válido.',
            'ufEnderecoInstituicao.size' => 'Informe a sigla do estado com 2 letras.',
        ]);

        // Buscar o tipo de instituição.
        $tipoInstituicao = TipoInstituicao::where(
            'nomeTipoInstituicao',
            $dados['tipoInstituicao']
        )->firstOrFail();

        // Buscar o nível de acesso da instituição.
        $nivelAcesso = NivelAcesso::where(
            'nomeNivelAcesso',
            'instituicao'
        )->firstOrFail();

        // Cadastrar instituição, usuário, telefone e endereço.
        DB::transaction(function () use (
            $dados,
            $tipoInstituicao,
            $nivelAcesso
        ) {
            // Cadastrar instituição.
            $instituicao = Instituicao::create([
                'idTipoInstituicao' => $tipoInstituicao->idTipoInstituicao,
                'nomeInstituicao' => $dados['nomeInstituicao'],
                'cnpjInstituicao' => $dados['cnpjInstituicao'],
                'emailInstituicao' => $dados['emailUsuario'],
                'statusInstituicao' => 'ativo',
            ]);

            // Cadastrar usuário responsável.
            Usuario::create([
                'nomeUsuario' => $dados['nomeUsuario'],
                'emailUsuario' => $dados['emailUsuario'],
                'senhaUsuario' => Hash::make($dados['senhaUsuario']),
                'statusUsuario' => 'ativo',
                'idNivelAcesso' => $nivelAcesso->idNivelAcesso,
                'idInstituicao' => $instituicao->idInstituicao,
            ]);

            // Cadastrar telefone da instituição.
            $telefone = new TelefoneInstituicao();

            $telefone->forceFill([
                'numTelefoneInstituicao' => $dados['numTelefoneInstituicao'],
                'idInstituicao' => $instituicao->idInstituicao,
            ])->save();

            // Verificar se algum campo do endereço foi preenchido.
            $camposEndereco = [
                'cepEnderecoInstituicao',
                'logradouroEnderecoInstituicao',
                'numeroEnderecoInstituicao',
                'complementoEnderecoInstituicao',
                'bairroEnderecoInstituicao',
                'cidadeEnderecoInstituicao',
                'ufEnderecoInstituicao',
            ];

            $possuiEndereco = false;

            foreach ($camposEndereco as $campo) {
                if (!empty($dados[$campo])) {
                    $possuiEndereco = true;
                    break;
                }
            }

            // Cadastrar endereço somente se algum campo foi informado.
            if ($possuiEndereco) {
                $endereco = new EnderecoInstituicao();

                $endereco->forceFill([
                    'cepEnderecoInstituicao' => $dados['cepEnderecoInstituicao'] ?? null,
                    'logradouroEnderecoInstituicao' => $dados['logradouroEnderecoInstituicao'] ?? null,
                    'numeroEnderecoInstituicao' => $dados['numeroEnderecoInstituicao'] ?? null,
                    'complementoEnderecoInstituicao' => $dados['complementoEnderecoInstituicao'] ?? null,
                    'bairroEnderecoInstituicao' => $dados['bairroEnderecoInstituicao'] ?? null,
                    'cidadeEnderecoInstituicao' => $dados['cidadeEnderecoInstituicao'] ?? null,
                    'ufEnderecoInstituicao' => isset($dados['ufEnderecoInstituicao'])
                        ? strtoupper($dados['ufEnderecoInstituicao'])
                        : null,
                    'idInstituicao' => $instituicao->idInstituicao,
                ])->save();
            }
        });

        return redirect()->route('login')->with(
            'mensagem',
            'Instituição cadastrada com sucesso! Faça login para continuar.'
        );
    }
}