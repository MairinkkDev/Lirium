<?php
namespace App\Http\Controllers;

use App\Models\Instituicao;
use App\Models\MaterialReciclavel;
use App\Models\EstoqueInstituicao;
use App\Models\PrecoMaterialInstituicao;
use App\Models\ContribuicaoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstoqueController extends Controller
{
    private function instituicaoLogada(): Instituicao
    {
        $usuario = Auth::user();

        if (!$usuario || !$usuario->isInstituicao() || !$usuario->instituicao) {
            abort(403, 'Esta página é só para usuários de instituição.');
        }

        return $usuario->instituicao;
    }

    
    private function materialPorNome(string $nome): MaterialReciclavel
    {
        $nome = trim(preg_replace('/\s+/', ' ', $nome));

        $material = MaterialReciclavel::whereRaw('LOWER(nomeMaterialReciclavel) = ?', [mb_strtolower($nome)])->first();

        return $material ?? MaterialReciclavel::create(['nomeMaterialReciclavel' => $nome]);
    }

    public function painel()
{
    $inst = $this->instituicaoLogada();

    $estoques = EstoqueInstituicao::with('material')
        ->where('idInstituicao', $inst->idInstituicao)
        ->get();

    $precos = PrecoMaterialInstituicao::with('material')
        ->where('idInstituicao', $inst->idInstituicao)
        ->get();

    $materiais = $estoques->map(fn ($e) => [
        'id'          => $e->idMaterialReciclavel,
        'nome'        => $e->material->nomeMaterialReciclavel,
        'quantidade'  => (float) $e->quantidadeKg,
        'atualizacao' => $e->updated_at?->format('d/m/Y') ?? '-',
    ])->sortBy('nome')->values()->all();

    $listaPrecos = $precos->map(fn ($p) => [
        'id'    => $p->idMaterialReciclavel,
        'nome'  => $p->material->nomeMaterialReciclavel,
        'preco' => (float) $p->precoKg,
    ])->sortBy('nome')->values()->all();

    $totalEstoque  = $estoques->sum('quantidadeKg');
    $tiposMaterial = $estoques->count();
    $entregasHoje  = ContribuicaoUsuario::where('idInstituicao', $inst->idInstituicao)
        ->whereDate('created_at', today())
        ->count();

    $catalogo = MaterialReciclavel::orderBy('nomeMaterialReciclavel')->get();

    $primeiraEntrega = ContribuicaoUsuario::where('idInstituicao', $inst->idInstituicao)->min('created_at');

    $inicio = Carbon::parse($inst->created_at ?? now());
    if ($primeiraEntrega && Carbon::parse($primeiraEntrega)->lt($inicio)) {
        $inicio = Carbon::parse($primeiraEntrega); 
    }
    $inicio = $inicio->startOfMonth();
    $fim    = now()->startOfMonth();

    $porMes = ContribuicaoUsuario::where('idInstituicao', $inst->idInstituicao)
        ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, SUM(quantidadeKg) as total")
        ->groupBy('mes')
        ->pluck('total', 'mes');

    $mesesLabel   = [];
    $mesesValores = [];

    for ($m = $inicio->copy(); $m->lte($fim); $m->addMonth()) {
        $mesesLabel[]   = $m->format('m/Y');
        $mesesValores[] = round((float) ($porMes[$m->format('Y-m')] ?? 0), 2);
    }

    return view('painelInstituicao.estoque', compact(
        'materiais', 'listaPrecos', 'totalEstoque',
        'tiposMaterial', 'entregasHoje', 'catalogo',
        'mesesLabel', 'mesesValores'
    ));
}

   
    public function adicionar(Request $request)
    {
        $dados = $request->validate([
            'nomeMaterial' => 'required|string|max:100',
            'quantidadeKg' => 'required|numeric|min:0',
        ]);

        $inst = $this->instituicaoLogada();

        $erro = DB::transaction(function () use ($dados, $inst) {
            $material = $this->materialPorNome($dados['nomeMaterial']);

            $jaExiste = EstoqueInstituicao::where('idInstituicao', $inst->idInstituicao)
                ->where('idMaterialReciclavel', $material->idMaterialReciclavel)
                ->exists();

            if ($jaExiste) {
                return 'O material "' . $material->nomeMaterialReciclavel
                    . '" já está no seu estoque. Use "Alterar" para mudar a quantidade.';
            }

            EstoqueInstituicao::create([
                'idInstituicao'        => $inst->idInstituicao,
                'idMaterialReciclavel' => $material->idMaterialReciclavel,
                'quantidadeKg'         => $dados['quantidadeKg'],
            ]);

            return null;
        });

        if ($erro) {
            return back()->withErrors(['nomeMaterial' => $erro])->withInput();
        }

        return back()->with('sucesso', 'Material adicionado ao estoque.');
    }

   
    public function alterar(Request $request)
    {
        $dados = $request->validate([
            'idMaterialReciclavel' => 'required|integer',
            'quantidadeKg'         => 'required|numeric|min:0',
        ]);

        $inst = $this->instituicaoLogada();

        $estoque = EstoqueInstituicao::where('idInstituicao', $inst->idInstituicao)
            ->where('idMaterialReciclavel', $dados['idMaterialReciclavel'])
            ->first();

        if (!$estoque) {
            return back()->withErrors(['idMaterialReciclavel' => 'Material não encontrado no seu estoque.']);
        }

        $estoque->update(['quantidadeKg' => $dados['quantidadeKg']]);

        return back()->with('sucesso', 'Quantidade alterada com sucesso.');
    }

   
    public function excluirMaterial(Request $request)
{
    $dados = $request->validate(['idMaterialReciclavel' => 'required|integer']);

    $inst = $this->instituicaoLogada();

    EstoqueInstituicao::where('idInstituicao', $inst->idInstituicao)
        ->where('idMaterialReciclavel', $dados['idMaterialReciclavel'])
        ->delete();

    return back()->with('sucesso', 'Material excluído do estoque.');
}

    public function adicionarPreco(Request $request)
    {
        $dados = $request->validate([
            'nomeMaterial' => 'required|string|max:100',
            'precoKg'      => 'required|numeric|min:0',
        ]);

        $inst = $this->instituicaoLogada();

        $erro = DB::transaction(function () use ($dados, $inst) {
            $material = $this->materialPorNome($dados['nomeMaterial']);

            $jaExiste = PrecoMaterialInstituicao::where('idInstituicao', $inst->idInstituicao)
                ->where('idMaterialReciclavel', $material->idMaterialReciclavel)
                ->exists();

            if ($jaExiste) {
                return 'O material "' . $material->nomeMaterialReciclavel
                    . '" já tem preço cadastrado. Use "Alterar" para mudar o valor.';
            }

            PrecoMaterialInstituicao::create([
                'idInstituicao'        => $inst->idInstituicao,
                'idMaterialReciclavel' => $material->idMaterialReciclavel,
                'precoKg'              => $dados['precoKg'],
            ]);

            return null;
        });

        if ($erro) {
            return back()->withErrors(['nomeMaterial' => $erro])->withInput();
        }

        return back()->with('sucesso', 'Preço adicionado com sucesso.');
    }


    public function alterarPreco(Request $request)
    {
        $dados = $request->validate([
            'idMaterialReciclavel' => 'required|integer',
            'precoKg'              => 'required|numeric|min:0',
        ]);

        $inst = $this->instituicaoLogada();

        $preco = PrecoMaterialInstituicao::where('idInstituicao', $inst->idInstituicao)
            ->where('idMaterialReciclavel', $dados['idMaterialReciclavel'])
            ->first();

        if (!$preco) {
            return back()->withErrors(['idMaterialReciclavel' => 'Preço não encontrado.']);
        }

        $preco->update(['precoKg' => $dados['precoKg']]);

        return back()->with('sucesso', 'Preço alterado com sucesso.');
    }


    public function excluirPreco(Request $request)
    {
        $dados = $request->validate(['idMaterialReciclavel' => 'required|integer']);

        $inst = $this->instituicaoLogada();

        PrecoMaterialInstituicao::where('idInstituicao', $inst->idInstituicao)
            ->where('idMaterialReciclavel', $dados['idMaterialReciclavel'])
            ->delete();

        return back()->with('sucesso', 'Preço excluído.');
    }
}