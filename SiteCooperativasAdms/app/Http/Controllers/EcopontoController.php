<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Ecoponto;
use Illuminate\Http\Request;

class EcopontoController extends Controller
{
     public function painel(Request $request)
    {
        $descricao = $request->query('descricao');

        $ecoponto = Ecoponto::when($descricao, function ($query) use ($descricao) {
                $query->where('descricaoEcoponto', 'like', '%' . $descricao . '%');
            })
            ->orderBy('descricaoEcoponto')
            ->paginate(10)
            ->withQueryString();

        return view('painelAdmin.ecoponto')
            ->with('ecoponto', $ecoponto)
            ->with('descricao', $descricao);
    }

    public function detalhe(string $idEcoponto)
    {
        $ecoponto = Ecoponto::where('idEcoponto', $idEcoponto)->firstOrFail();

        return view('painelAdmin.ecopontoDetalhado')->with('ecoponto', $ecoponto);
    }

    public function atualizar(Request $request, string $idEcoponto)
    {   
    abort_unless(Auth::user()->isAdmin(), 403);

    $ecoponto = Ecoponto::where('idEcoponto', $idEcoponto)->firstOrFail();

    if ($ecoponto->statusEcoponto === 'inativo') {
        return back()->with('mensagem', "Não é possível alterar o endereço do ecoponto #{$idEcoponto} enquanto ele estiver inativo.");
    }

    $dados = $request->validate([
        'cepEcoponto'          => ['required', 'string', 'max:9'],
        'logradouroEcoponto'   => ['required', 'string', 'max:150'],
        'numeroEcoponto'       => ['required', 'string', 'max:10'],
        'complementoEcoponto'  => ['nullable', 'string', 'max:100'],
        'bairroEcoponto'       => ['required', 'string', 'max:100'],
        'cidadeEcoponto'       => ['required', 'string', 'max:100'],
        'ufEcoponto'           => ['required', 'string', 'max:2'],
    ]);

    $ecoponto->update($dados);

    return back()->with('mensagem', "Endereço do ecoponto #{$idEcoponto} atualizado com sucesso.");
    }

    public function inativar(string $idEcoponto)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $ecoponto = Ecoponto::where('idEcoponto', $idEcoponto)->firstOrFail();

        if ($ecoponto->statusEcoponto === 'inativo') {
            return back()->with('mensagem', "O ecoponto #{$idEcoponto} já estava inativo.");
        }

        Ecoponto::where('idEcoponto', $idEcoponto)->update(['statusEcoponto' => 'inativo']);

        return back()->with('mensagem', "Ecoponto #{$idEcoponto} marcado como inativo.");
    }

    public function ativar(string $idEcoponto)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $ecoponto = Ecoponto::where('idEcoponto', $idEcoponto)->firstOrFail();

        if ($ecoponto->statusEcoponto === 'ativo') {
            return back()->with('mensagem', "O ecoponto #{$idEcoponto} já estava ativo.");
        }

        Ecoponto::where('idEcoponto', $idEcoponto)->update(['statusEcoponto' => 'ativo']);

        return back()->with('mensagem', "Ecoponto #{$idEcoponto} marcado como ativo.");
    }

    public function excluir(string $idEcoponto)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        Ecoponto::where('idEcoponto', $idEcoponto)->firstOrFail();
        Ecoponto::where('idEcoponto', $idEcoponto)->delete();

        return back()->with('mensagem', "Ecoponto #{$idEcoponto} excluído com sucesso.");
    }

    public function index()
    {
        $ecoponto = Ecoponto::all();
     return $ecoponto;
    }

    public function show( $idEcoponto)
    {
      $ecoponto = Ecoponto::where('idEcoponto',$idEcoponto)-> get();
      return $ecoponto;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descricaoEcoponto' => ['required', 'string', 'max:150'],
            'cepEcoponto' => ['nullable', 'string', 'max:9'],
            'logradouroEcoponto' => ['nullable', 'string', 'max:150'],
            'numeroEcoponto' => ['nullable', 'string', 'max:10'],
            'complementoEcoponto' => ['nullable', 'string', 'max:100'],
            'bairroEcoponto' => ['nullable', 'string', 'max:100'],
            'cidadeEcoponto' => ['nullable', 'string', 'max:100'],
            'ufEcoponto' => ['nullable', 'string', 'size:2'],
            'paisEcoponto' => ['nullable', 'string', 'max:50'],
            'materiais' => ['nullable', 'array'],
            'materiais.*' => ['integer', 'exists:tb_Material_Reciclavel,idMaterialReciclavel'],
        ]);

        $materiais = $data['materiais'] ?? [];
        unset($data['materiais']);

        $ecoponto = Ecoponto::create($data);
        $ecoponto->materiais()->sync($materiais);

        return response()->json($ecoponto->load('materiais'), 201);
    }

    public function destroy(string $idEcoponto)
    {
        Ecoponto::where('idEcoponto', $idEcoponto)->delete();
        return response()->json(['message' => 'Ecoponto removido com sucesso.']);
    }
}
