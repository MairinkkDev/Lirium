<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Denuncia;
use Illuminate\Http\Request;

class DenunciaController extends Controller
{
public function painel(Request $request)
{
    $status = $request->query('status', 'todas');

    $denuncia = Denuncia::when($status !== 'todas', function ($query) use ($status) {
        $query->where('statusDenuncia', $status);
    })
    ->orderBy('dataCriacaoDenuncia')
    ->paginate(10)
    ->withQueryString();

    return view('painelAdmin.denuncia')->with('denuncia', $denuncia)->with('status', $status);
}

     public function show( $idDenuncia)
    {
      $denuncia = Denuncia::where('idDenuncia',$idDenuncia)-> get();
      return $denuncia;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descricaoDenuncia' => ['required', 'string'],
            'idCategoriaDenuncia' => ['required', 'integer', 'exists:tb_Categoria_Denuncia,idCategoriaDenuncia'],
        ]);

        $data['idUsuario'] = $request->user()?->idUsuario ?? $request->input('idUsuario');

        abort_unless($data['idUsuario'], 422, 'idUsuario é obrigatório.');

        $data['statusDenuncia'] = 'pendente';

        return response()->json(
            Denuncia::create($data)->load(['usuario', 'categoria']),
            201
        );
    }


    public function destroy(string $idDenuncia)
    {
        Denuncia::where('idDenuncia', $idDenuncia)->delete();
        return response()->json(['message' => 'Denuncia removido com sucesso.']);
    }


public function analisar(string $idDenuncia)
{
    abort_unless(Auth::user()->isAdmin(), 403);

    $denuncia = Denuncia::where('idDenuncia', $idDenuncia)->firstOrFail();

    if ($denuncia->statusDenuncia !== 'pendente') {
        return back()->with('mensagem', "A denúncia #{$idDenuncia} não está mais pendente.");
    }

    Denuncia::where('idDenuncia', $idDenuncia)->update(['statusDenuncia' => 'em_analise']);

    return back()->with('mensagem', "Denúncia #{$idDenuncia} está em análise.");
}

public function concluir(string $idDenuncia)
{
    abort_unless(Auth::user()->isAdmin(), 403);

    $denuncia = Denuncia::where('idDenuncia', $idDenuncia)->firstOrFail();

    if ($denuncia->statusDenuncia === 'resolvida') {
        return back()->with('mensagem', "A denúncia #{$idDenuncia} já estava concluída.");
    }

    Denuncia::where('idDenuncia', $idDenuncia)->update(['statusDenuncia' => 'resolvida']);

    return back()->with('mensagem', "Denúncia #{$idDenuncia} concluída com sucesso.");
}

    public function detalhe(string $idDenuncia)
{
    $denuncia = Denuncia::where('idDenuncia', $idDenuncia)->firstOrFail();

    return view('painelAdmin.denunciaDetalhada')->with('denuncia', $denuncia);
}

    public function reabrir(string $idDenuncia)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $denuncia = Denuncia::where('idDenuncia', $idDenuncia)->firstOrFail();

        if ($denuncia->statusDenuncia !== 'resolvida') {
            return back()->with('mensagem', "A denúncia #{$idDenuncia} já não está concluída.");
        }

        Denuncia::where('idDenuncia', $idDenuncia)->update(['statusDenuncia' => 'pendente']);

        return back()->with('mensagem', "Denúncia de {$idDenuncia} marcada como pendente novamente.");
    }

    public function excluir(string $idDenuncia)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        Denuncia::where('idDenuncia', $idDenuncia)->firstOrFail();

        Denuncia::where('idDenuncia', $idDenuncia)->delete();

        return back()->with('mensagem', "Denúncia #{$idDenuncia} excluída com sucesso.");
    }
}
