<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use App\Models\MaterialReciclavel;
use App\Models\EstoqueInstituicao;
use App\Models\PrecoMaterialInstituicao;
use App\Models\ContribuicaoUsuario;
use App\Models\TelefoneUsuario;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContribuidorController extends Controller
{
    /* ===================== AUXILIARES ===================== */

    private function instituicaoLogada(): Instituicao
    {
        $usuario = Auth::user();

        if (!$usuario || !$usuario->isInstituicao() || !$usuario->instituicao) {
            abort(403, 'Esta página é só para usuários de instituição.');
        }

        return $usuario->instituicao;
    }

    private function cpfMascarado(?string $c): string
    {
        return $c ? '•••.' . substr($c, 3, 3) . '.' . substr($c, 6, 3) . '-••' : '-';
    }

    private function telefoneFormatado(?string $t): string
    {
        if (!$t) return '-';

        if (strlen($t) === 11) {
            return '(' . substr($t, 0, 2) . ') ' . substr($t, 2, 5) . '-' . substr($t, 7);
        }

        if (strlen($t) === 10) {
            return '(' . substr($t, 0, 2) . ') ' . substr($t, 2, 4) . '-' . substr($t, 6);
        }

        return $t;
    }

    private function materialPorNome(string $nome): MaterialReciclavel
    {
        $nome = trim(preg_replace('/\s+/', ' ', $nome));

        $material = MaterialReciclavel::whereRaw(
            'LOWER(nomeMaterialReciclavel) = ?',
            [mb_strtolower($nome)]
        )->first();

        return $material ?? MaterialReciclavel::create(['nomeMaterialReciclavel' => $nome]);
    }

    // Soma (delta > 0) ou subtrai (delta < 0) do estoque da instituição
    private function ajustarEstoque(int $idInst, int $idMat, float $delta): void
    {
        $estoque = EstoqueInstituicao::where('idInstituicao', $idInst)
            ->where('idMaterialReciclavel', $idMat)
            ->lockForUpdate()
            ->first();

        if (!$estoque) {
            if ($delta <= 0) {
                return;
            }

            $estoque = EstoqueInstituicao::create([
                'idInstituicao'        => $idInst,
                'idMaterialReciclavel' => $idMat,
                'quantidadeKg'         => 0,
            ]);
        }

        $estoque->update([
            'quantidadeKg' => max(0, (float) $estoque->quantidadeKg + $delta),
        ]);
    }

    private function normalizar(Request $request): void
    {
        $qtd = trim((string) $request->quantidadeContribuidor);

        if (str_contains($qtd, ',')) {
            $qtd = str_replace(',', '.', str_replace('.', '', $qtd));
        }

        $request->merge([
            'cpfContribuidor'        => preg_replace('/\D/', '', (string) $request->cpfContribuidor),
            'telefoneContribuidor'   => preg_replace('/\D/', '', (string) $request->telefoneContribuidor),
            'quantidadeContribuidor' => $qtd,
        ]);
    }

    private function mensagens(): array
    {
        return [
            'required'        => 'Preencha este campo.',
            'digits'          => 'O CPF deve ter 11 números.',
            'regex'           => 'Informe o telefone com DDD (10 ou 11 números).',
            'numeric'         => 'Informe um número válido.',
            'gt'              => 'A quantidade deve ser maior que zero.',
            'date'            => 'Data inválida.',
            'before_or_equal' => 'A data da entrega não pode ser futura.',
            'max'             => 'Valor muito grande.',
        ];
    }

    private function dataEntrega(string $data): Carbon
    {
        return Carbon::parse($data)->setTimeFrom(now());
    }

    private function listaMateriais(): array
    {
        return MaterialReciclavel::orderBy('nomeMaterialReciclavel')
            ->pluck('nomeMaterialReciclavel')
            ->all();
    }

    /* ===================== TELAS ===================== */

    public function painel(Request $request)
    {
        $inst     = $this->instituicaoLogada();
        $busca    = trim((string) $request->query('busca', ''));
        $material = trim((string) $request->query('material', ''));

        $query = ContribuicaoUsuario::with(['usuario.telefones', 'material'])
            ->where('idInstituicao', $inst->idInstituicao);

        if ($busca !== '') {
            $digitos = preg_replace('/\D/', '', $busca);

            $query->whereHas('usuario', function ($u) use ($busca, $digitos) {
                $u->where(function ($w) use ($busca, $digitos) {
                    $w->where('nomeUsuario', 'like', '%' . $busca . '%');

                    if (strlen($digitos) === 11) {
                        $w->orWhere('cpfUsuario', $digitos);
                    }
                });
            });
        }

        if ($material !== '') {
            $query->whereHas('material', fn ($m) => $m->where('nomeMaterialReciclavel', $material));
        }

        $contribuidores = $query
            ->orderByDesc('created_at')
            ->orderByDesc('idContribuicaoUsuario')
            ->get()
            ->map(fn ($c) => [
                'id'         => $c->idContribuicaoUsuario,
                'nome'       => $c->usuario->nomeUsuario ?? '-',
                'cpf'        => $this->cpfMascarado($c->usuario->cpfUsuario ?? null),
                'telefone'   => $this->telefoneFormatado($c->usuario->telefones->first()->numTelefoneUsuario ?? null),
                'material'   => $c->material->nomeMaterialReciclavel ?? '-',
                'quantidade' => (float) $c->quantidadeKg,
                'entrega'    => $c->created_at ? $c->created_at->format('d/m/Y') : '-',
            ])->all();

        return view('painelInstituicao.contribuidor', [
            'contribuidores' => $contribuidores,
            'materiais'      => $this->listaMateriais(),
            'busca'          => $busca,
            'material'       => $material,
        ]);
    }

    public function criar()
    {
        return view('painelInstituicao.contribuidorCriar', [
            'materiais' => $this->listaMateriais(),
        ]);
    }

    public function editar(string $id)
    {
        $inst = $this->instituicaoLogada();

        $c = ContribuicaoUsuario::with(['usuario.telefones', 'material'])
            ->where('idInstituicao', $inst->idInstituicao)
            ->where('idContribuicaoUsuario', $id)
            ->firstOrFail();

        $u = $c->usuario;

        $contribuidor = [
            'id'         => $c->idContribuicaoUsuario,
            'nome'       => $u->nomeUsuario,
            'cpf'        => $this->cpfMascarado($u->cpfUsuario),
            'telefone'   => $this->telefoneFormatado($u->telefones->first()->numTelefoneUsuario ?? null),
            'material'   => $c->material->nomeMaterialReciclavel ?? '',
            'quantidade' => str_replace('.', ',', (string) (float) $c->quantidadeKg),
            'entrega'    => $c->created_at->format('Y-m-d'),
        ];

        return view('painelInstituicao.contribuidorAlterar', [
            'contribuidor' => $contribuidor,
            'materiais'    => $this->listaMateriais(),
        ]);
    }

    /* ===================== AÇÕES ===================== */

    public function store(Request $request)
    {
        $this->normalizar($request);

        $inst = $this->instituicaoLogada();

        $d = $request->validate([
            'cpfContribuidor'         => ['required', 'digits:11'],
            'telefoneContribuidor'    => ['nullable', 'regex:/^\d{10,11}$/'],
            'materialContribuidor'    => ['required', 'string', 'max:100'],
            'quantidadeContribuidor'  => ['required', 'numeric', 'gt:0', 'max:99999999'],
            'dataEntregaContribuidor' => ['required', 'date', 'before_or_equal:today'],
        ], $this->mensagens());

        // O usuário precisa existir: não cria ninguém
        $usuario = Usuario::where('cpfUsuario', $d['cpfContribuidor'])->first();

        if (!$usuario) {
            return back()->withInput()->withErrors([
                'cpfContribuidor' => 'Nenhum usuário cadastrado com este CPF.',
            ]);
        }

        try {
            DB::transaction(function () use ($d, $inst, $usuario) {
                // Telefone opcional: adiciona ao usuário se ainda não tiver esse número
                $telefone = $d['telefoneContribuidor'] ?? '';

                if ($telefone !== '' && !TelefoneUsuario::where('idUsuario', $usuario->idUsuario)
                        ->where('numTelefoneUsuario', $telefone)->exists()) {
                    TelefoneUsuario::create([
                        'idUsuario'          => $usuario->idUsuario,
                        'numTelefoneUsuario' => $telefone,
                    ]);
                }

                $material = $this->materialPorNome($d['materialContribuidor']);

                $preco = PrecoMaterialInstituicao::where('idInstituicao', $inst->idInstituicao)
                    ->where('idMaterialReciclavel', $material->idMaterialReciclavel)
                    ->value('precoKg');

                $contribuicao = new ContribuicaoUsuario([
                    'idUsuario'            => $usuario->idUsuario,
                    'idInstituicao'        => $inst->idInstituicao,
                    'idMaterialReciclavel' => $material->idMaterialReciclavel,
                    'quantidadeKg'         => $d['quantidadeContribuidor'],
                    'precoKgNoMomento'     => $preco,
                ]);
                $contribuicao->created_at = $this->dataEntrega($d['dataEntregaContribuidor']);
                $contribuicao->save();

                $this->ajustarEstoque(
                    $inst->idInstituicao,
                    (int) $material->idMaterialReciclavel,
                    (float) $d['quantidadeContribuidor']
                );
            });

            return redirect('/contribuidor')->with('sucesso', 'Entrega registrada e estoque atualizado.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('erro', 'Não foi possível registrar a entrega. Tente novamente.');
        }
    }

    // Soma uma quantidade à entrega já existente (e ao estoque)
    public function adicionarQuantidade(Request $request, string $id)
    {
        $this->normalizar($request);

        $inst = $this->instituicaoLogada();

        $c = ContribuicaoUsuario::where('idInstituicao', $inst->idInstituicao)
            ->where('idContribuicaoUsuario', $id)
            ->firstOrFail();

        $d = $request->validate([
            'quantidadeContribuidor' => ['required', 'numeric', 'gt:0', 'max:99999999'],
        ], $this->mensagens());

        try {
            DB::transaction(function () use ($d, $inst, $c) {
                $c->quantidadeKg = (float) $c->quantidadeKg + (float) $d['quantidadeContribuidor'];
                $c->save();

                $this->ajustarEstoque(
                    $inst->idInstituicao,
                    (int) $c->idMaterialReciclavel,
                    (float) $d['quantidadeContribuidor']
                );
            });

            return redirect('/contribuidor')->with('sucesso', 'Quantidade adicionada e estoque atualizado.');
        } catch (\Throwable $e) {
            report($e);

            return redirect('/contribuidor')->with('erro', 'Não foi possível adicionar a quantidade. Tente novamente.');
        }
    }

    public function atualizar(Request $request, string $id)
    {
        $this->normalizar($request);

        $inst = $this->instituicaoLogada();

        $c = ContribuicaoUsuario::where('idInstituicao', $inst->idInstituicao)
            ->where('idContribuicaoUsuario', $id)
            ->firstOrFail();

        $d = $request->validate([
            'materialContribuidor'    => ['required', 'string', 'max:100'],
            'quantidadeContribuidor'  => ['required', 'numeric', 'gt:0', 'max:99999999'],
            'dataEntregaContribuidor' => ['required', 'date', 'before_or_equal:today'],
        ], $this->mensagens());

        try {
            DB::transaction(function () use ($d, $inst, $c) {
                $this->ajustarEstoque($inst->idInstituicao, (int) $c->idMaterialReciclavel, -(float) $c->quantidadeKg);

                $material = $this->materialPorNome($d['materialContribuidor']);

                if ((int) $material->idMaterialReciclavel !== (int) $c->idMaterialReciclavel) {
                    $c->precoKgNoMomento = PrecoMaterialInstituicao::where('idInstituicao', $inst->idInstituicao)
                        ->where('idMaterialReciclavel', $material->idMaterialReciclavel)
                        ->value('precoKg');
                }

                $c->idMaterialReciclavel = $material->idMaterialReciclavel;
                $c->quantidadeKg         = $d['quantidadeContribuidor'];

                if ($c->created_at->toDateString() !== $d['dataEntregaContribuidor']) {
                    $c->created_at = $this->dataEntrega($d['dataEntregaContribuidor']);
                }

                $c->save();

                $this->ajustarEstoque(
                    $inst->idInstituicao,
                    (int) $material->idMaterialReciclavel,
                    (float) $d['quantidadeContribuidor']
                );
            });

            return redirect('/contribuidor')->with('sucesso', 'Entrega alterada e estoque ajustado.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('erro', 'Não foi possível alterar a entrega. Tente novamente.');
        }
    }

    public function excluir(string $id)
    {
        $inst = $this->instituicaoLogada();

        $c = ContribuicaoUsuario::where('idInstituicao', $inst->idInstituicao)
            ->where('idContribuicaoUsuario', $id)
            ->firstOrFail();

        try {
            DB::transaction(function () use ($c, $inst) {
                $this->ajustarEstoque($inst->idInstituicao, (int) $c->idMaterialReciclavel, -(float) $c->quantidadeKg);
                $c->delete();
            });

            return redirect('/contribuidor')->with('sucesso', 'Entrega excluída e estoque ajustado.');
        } catch (\Throwable $e) {
            report($e);

            return redirect('/contribuidor')->with('erro', 'Não foi possível excluir a entrega. Tente novamente.');
        }
    }
}