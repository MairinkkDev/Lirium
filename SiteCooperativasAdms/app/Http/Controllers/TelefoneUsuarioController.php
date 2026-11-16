<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TelefoneUsuario;
use Illuminate\Http\Request;

class TelefoneUsuarioController extends Controller
{
    public function index($idUsuario)
    {
        $telefoneUsuario = TelefoneUsuario::where('idUsuario', $idUsuario)->get();
        return $telefoneUsuario;
    }

    public function store(Request $request, $idUsuario)
    {
        $data = $request->validate([
            'numTelefoneUsuario' => ['required', 'string', 'max:20']
        ]);

        return response()->json(
            TelefoneUsuario::create($data + ['idUsuario' => $idUsuario]),
            201
        );
    }

    public function destroy(string $idTelefoneUsuario)
    {
        TelefoneUsuario::where('idTelefoneUsuario', $idTelefoneUsuario)->delete();
        return response()->json(['message' => 'Telefone removido com sucesso.']);
    }
}
