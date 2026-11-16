<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoriaDenuncia;
use Illuminate\Http\Request;

class CategoriaDenunciaController extends Controller
{
    public function index()
    {
        $categoriaDenuncia = CategoriaDenuncia::all();
     return $categoriaDenuncia;
    }
public function show( $idCategoriaDenuncia)
    {
      $categoriaDenuncia = CategoriaDenuncia::where('idCategoriaDenuncia',$idCategoriaDenuncia)-> get();
      return $categoriaDenuncia;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descricaoCategoriaDenuncia' => ['required','string']
        ]);

        return response()->json(CategoriaDenuncia::create($data), 201);
    }

    public function update(Request $request, CategoriaDenuncia $categoriaDenuncia)
    {
        $data = $request->validate([
            'descricaoCategoriaDenuncia' => ['sometimes']
        ]);

        $categoriaDenuncia->update($data);

        return response()->json($categoriaDenuncia->fresh());
    }

    public function destroy(string $idCategoriaDenuncia)
    {
        CategoriaDenuncia::where('idCategoriaDenuncia', $idCategoriaDenuncia)->delete();
        return response()->json(['message' => 'Categoria removido com sucesso.']);
    }
}
