<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TelefoneInstituicao;
use Illuminate\Http\Request;

class TelefoneInstituicaoController extends Controller
{
    public function index($idInstituicao)
    {
        
        $telefoneInstituicao = TelefoneInstituicao::where('idInstituicao', $idInstituicao)->get();
        return $telefoneInstituicao;
    }

    public function store(Request $request, $idInstituicao)
    {
        $data = $request->validate([
            'numTelefoneInstituicao' => ['required', 'string', 'max:20']
        ]);

        return response()->json(
            TelefoneInstituicao::create($data + ['idInstituicao' => $idInstituicao]),
            201
        );
    }

    public function destroy(string $idTelefoneInstituicao)
    {
        TelefoneInstituicao::where('idTelefoneInstituicao', $idTelefoneInstituicao)->delete();
        return response()->json(['message' => 'Telefone removido com sucesso.']);
    }
}
