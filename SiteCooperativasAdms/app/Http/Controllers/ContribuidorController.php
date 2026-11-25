<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use App\Models\MaterialReciclavel;
use App\Models\EstoqueInstituicao;
use App\Models\PrecoMaterialInstituicao;
use App\Models\ContribuicaoUsuario;
use App\Models\Contribuidor;
use App\Models\TelefoneContribuidor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            'nomeContribuidor'       => trim((string) $request->nomeContribuidor),
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
            'integer'         => 'Escolha um material da lista.',
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

    // Lista de nomes (usada nas telas de criar e alterar, onde ainda dá para digitar)
    private function listaMateriais(): array
    {
        return MaterialReciclavel::orderBy('nomeMaterialReciclavel')
            ->pluck('nomeMaterialReciclavel')
            ->all();
    }

    // Materiais que a instituição tem no estoque (usada no select do "Registrar entrega")
    private function materiaisDaInstituicao(int $idInst)
    {
        return MaterialReciclavel::whereIn(
                'idMaterialReciclavel',
                EstoqueInstituicao::where('idInstituicao', $idInst)->select('idMaterialReciclavel')
            )
            ->orderBy('nomeMaterialReciclavel')
            ->get(['idMaterialReciclavel', 'nomeMaterialReciclavel']);
    }

    /* ===================== TELAS ===================== */

    public function painel(Request $request)
    {
        $inst   = $this->instituicaoLogada();
        $idInst = $inst->idInstituicao;

        /* ---------- Cards de resumo ---------- */

        $totalContribuidores = Contribuidor::count();

        $totalMaterial = ContribuicaoUsuario::where('idInstituicao', $idInst)
            ->sum('quantidadeKg');

        $totalHoje = ContribuicaoUsuario::where('idInstituicao', $idInst)
            ->whereDate('created_at', today())
            ->count();

        /* ---------- Filtros ---------- */

        $busca    = trim((string) $request->query('busca', ''));
        $material = trim((string) $request->query('material', ''));

        /* ---------- Entregas desta instituição, somadas por contribuidor ---------- */

        $entregasQuery = ContribuicaoUsuario::where('idInstituicao', $idInst);

        if ($material !== '') {
            $entregasQuery->whereHas('material', fn ($m) =>
                $m->where('nomeMaterialReciclavel', $material)
            );
        }

        $resumo = $entregasQuery
            ->select(
                'idContribuidor',
                DB::raw('SUM(quantidadeKg) as total_kg'),
                DB::raw('COUNT(*) as total_entregas'),
                DB::raw('MAX(idContribuicaoUsuario) as ultima_id')
            )
            ->groupBy('idContribuidor')
            ->get()
            ->keyBy('idContribuidor');

        // Última entrega registrada de cada pessoa
        $ultimas = ContribuicaoUsuario::with('material')
            ->whereIn('idContribuicaoUsuario', $resumo->pluck('ultima_id'))
            ->get()
            ->keyBy('idContribuicaoUsuario');

        /* ---------- Contribuidores (tabela tb_contribuidor) ---------- */

        $pessoasQuery = Contribuidor::with('telefones');

        if ($busca !== '') {
            $digitos = preg_replace('/\D/', '', $busca);

            $pessoasQuery->where(function ($w) use ($busca, $digitos) {
                $w->where('nomeContribuidor', 'like', '%' . $busca . '%');

                if (strlen($digitos) === 11) {
                    $w->orWhere('cpfContribuidor', $digitos);
                }
            });
        }

        // Filtrando por material, só aparece quem entregou aquele material
        if ($material !== '') {
            $pessoasQuery->whereIn('idContribuidor', $resumo->keys()->all());
        }

        $contribuidores = $pessoasQuery->get()
            ->map(function ($p) use ($resumo, $ultimas) {
                $r = $resumo->get($p->idContribuidor);
                $u = $r ? $ultimas->get($r->ultima_id) : null;

                return [
                    'id'              => $p->idContribuidor,
                    'nome'            => $p->nomeContribuidor ?? '-',
                    'cpf'             => $this->cpfMascarado($p->cpfContribuidor ?? null),
                    'telefone'        => $this->telefoneFormatado(
                        $p->telefones->first()->numTelefoneContribuidor ?? null
                    ),
                    'entregas'        => (int) ($r->total_entregas ?? 0),
                    'total'           => (float) ($r->total_kg ?? 0),
                    'ultima_id'       => $r->ultima_id ?? null,   // null = ainda sem entrega
                    'ultima'          => $u?->created_at?->format('d/m/Y') ?? '-',
                    'ultima_kg'       => (float) ($u->quantidadeKg ?? 0),
                    'ultima_material' => $u?->material->nomeMaterialReciclavel ?? '-',
                    // recém-cadastrados e quem entregou há pouco aparecem primeiro
                    'ordem'           => max(
                        $u?->created_at?->timestamp ?? 0,
                        $p->created_at?->timestamp ?? 0
                    ),
                ];
            })
            ->sortByDesc('ordem')
            ->values()
            ->all();

        return view('painelInstituicao.contribuidor', [
            'contribuidores'      => $contribuidores,
            'materiais'           => $this->listaMateriais(),
            'busca'               => $busca,
            'material'            => $material,
            'totalContribuidores' => $totalContribuidores,
            'totalMaterial'       => $totalMaterial,
            'totalHoje'           => $totalHoje,
        ]);
    }

    public function criar()
    {
        $this->instituicaoLogada();

        return view('painelInstituicao.contribuidorCriar');
    }

    public function editar(string $id)
    {
        $inst = $this->instituicaoLogada();

        $c = ContribuicaoUsuario::with(['contribuidor.telefones', 'material'])
            ->where('idInstituicao', $inst->idInstituicao)
            ->where('idContribuicaoUsuario', $id)
            ->firstOrFail();

        $p = $c->contribuidor;

        $contribuidor = [
            'id'         => $c->idContribuicaoUsuario,
            'nome'       => $p->nomeContribuidor ?? '-',
            'cpf'        => $this->cpfMascarado($p->cpfContribuidor ?? null),
            'telefone'   => $this->telefoneFormatado(
                $p?->telefones->first()->numTelefoneContribuidor ?? null
            ),
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

    // Cadastra apenas o contribuidor (tb_contribuidor + telefone), sem entrega
    public function store(Request $request)
    {
        $this->normalizar($request);

        $this->instituicaoLogada();

        $d = $request->validate([
            'nomeContribuidor'     => ['required', 'string', 'max:100'],
            'cpfContribuidor'      => ['required', 'digits:11'],
            'telefoneContribuidor' => ['required', 'regex:/^\d{10,11}$/'],
        ], $this->mensagens());

        // Avisa se CPF ou telefone já estão cadastrados
        $erros = [];

        if (Contribuidor::where('cpfContribuidor', $d['cpfContribuidor'])->exists()) {
            $erros['cpfContribuidor'] = 'Este CPF já está cadastrado.';
        }

        if (TelefoneContribuidor::where('numTelefoneContribuidor', $d['telefoneContribuidor'])->exists()) {
            $erros['telefoneContribuidor'] = 'Este telefone já está cadastrado.';
        }

        if ($erros) {
            return back()->withInput()->withErrors($erros);
        }

        try {
            DB::transaction(function () use ($d) {
                $contribuidor = Contribuidor::create([
                    'nomeContribuidor' => $d['nomeContribuidor'],
                    'cpfContribuidor'  => $d['cpfContribuidor'],
                ]);

                TelefoneContribuidor::create([
                    'idContribuidor'          => $contribuidor->idContribuidor,
                    'numTelefoneContribuidor' => $d['telefoneContribuidor'],
                ]);
            });

            return redirect('/contribuidor')->with('sucesso', 'Contribuidor cadastrado com sucesso.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('erro', 'Não foi possível cadastrar o contribuidor. Tente novamente.');
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

    // Altera só a quantidade de uma entrega (modal do histórico); material e data não mudam
    public function alterarEntrega(Request $request, string $id)
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
                $antiga = (float) $c->quantidadeKg;
                $nova   = (float) $d['quantidadeContribuidor'];

                $c->quantidadeKg = $nova;
                $c->save();

                // ajusta o estoque do material com a diferença (positiva ou negativa)
                $this->ajustarEstoque($inst->idInstituicao, (int) $c->idMaterialReciclavel, $nova - $antiga);
            });

            return back()->with('sucesso', 'Entrega alterada e estoque ajustado.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('erro', 'Não foi possível alterar a entrega. Tente novamente.');
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

    public function historico(string $id)
    {
        $inst = $this->instituicaoLogada();

        $base = ContribuicaoUsuario::where('idInstituicao', $inst->idInstituicao)
            ->where('idContribuidor', $id);

        $p = Contribuidor::with('telefones')
            ->where('idContribuidor', $id)
            ->firstOrFail();

        $pessoa = [
            'id'       => $p->idContribuidor,
            'nome'     => $p->nomeContribuidor ?? '-',
            'cpf'      => $this->cpfMascarado($p->cpfContribuidor ?? null),
            'telefone' => $this->telefoneFormatado(
                $p->telefones->first()->numTelefoneContribuidor ?? null
            ),
        ];

        $totalKg       = (float) (clone $base)->sum('quantidadeKg');
        $totalEntregas = (clone $base)->count();

        // Total por material (chips de resumo)
        $porMaterial = (clone $base)
            ->select('idMaterialReciclavel', DB::raw('SUM(quantidadeKg) as total_kg'))
            ->with('material')
            ->groupBy('idMaterialReciclavel')
            ->orderByDesc('total_kg')
            ->get();

        // Últimas entregas, da mais recente para a mais antiga
        $entregas = (clone $base)
            ->with('material')
            ->orderByDesc('created_at')
            ->orderByDesc('idContribuicaoUsuario')
            ->paginate(10);

        return view('painelInstituicao.contribuidorHistorico', [
            'pessoa'        => $pessoa,
            'totalKg'       => $totalKg,
            'totalEntregas' => $totalEntregas,
            'porMaterial'   => $porMaterial,
            'entregas'      => $entregas,
            'materiais'     => $this->materiaisDaInstituicao($inst->idInstituicao),
        ]);
    }

    public function registrarEntrega(Request $request, string $id)
    {
        $this->normalizar($request);

        $inst = $this->instituicaoLogada();

        Contribuidor::findOrFail($id); // 404 se o contribuidor não existir

        $d = $request->validate([
            'idMaterialReciclavel'   => ['required', 'integer'],
            'quantidadeContribuidor' => ['required', 'numeric', 'gt:0', 'max:99999999'],
        ], $this->mensagens());

        // O material precisa ser um dos que a instituição tem no estoque
        $material = $this->materiaisDaInstituicao($inst->idInstituicao)
            ->firstWhere('idMaterialReciclavel', (int) $d['idMaterialReciclavel']);

        if (!$material) {
            return back()->withInput()->withErrors([
                'idMaterialReciclavel' => 'Escolha um material da lista.',
            ]);
        }

        try {
            DB::transaction(function () use ($d, $inst, $id, $material) {
                $preco = PrecoMaterialInstituicao::where('idInstituicao', $inst->idInstituicao)
                    ->where('idMaterialReciclavel', $material->idMaterialReciclavel)
                    ->value('precoKg');

                $contribuicao = new ContribuicaoUsuario([
                    'idUsuario'            => Auth::user()->idUsuario, // quem registrou
                    'idContribuidor'       => $id,
                    'idInstituicao'        => $inst->idInstituicao,
                    'idMaterialReciclavel' => $material->idMaterialReciclavel,
                    'quantidadeKg'         => $d['quantidadeContribuidor'],
                    'precoKgNoMomento'     => $preco,
                ]);
                $contribuicao->save();

                $this->ajustarEstoque(
                    $inst->idInstituicao,
                    (int) $material->idMaterialReciclavel,
                    (float) $d['quantidadeContribuidor']
                );
            });

            return redirect('/contribuidor/' . $id . '/historico')
                ->with('sucesso', 'Entrega registrada e estoque atualizado.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('erro', 'Não foi possível registrar a entrega. Tente novamente.');
        }
    }
}