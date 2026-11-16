<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    public function index()
    {
         $administrador = Administrador::all();
     return $administrador;
    }

    public function show( $idAdministrador)
    {
      $administrador = Administrador::where('idAdministrador',$idAdministrador)-> get();
      return $administrador;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idUsuario' => ['required','integer','exists:tbUsuario,idUsuario']
        ]);

        return response()->json(Administrador::create($data), 201);
    }

   

     public function destroy(string $idAdministrador)
    {
        Administrador::where('idAdministrador', $idAdministrador)->delete();
        return response()->json(['message' => 'Administrador removido com sucesso.']);
    }


    
    
}
