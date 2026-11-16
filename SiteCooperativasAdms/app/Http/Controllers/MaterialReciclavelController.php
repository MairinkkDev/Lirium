<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MaterialReciclavel;
use Illuminate\Http\Request;

class MaterialReciclavelController extends Controller
{
    public function index()
    {
        $material = MaterialReciclavel::all();
     return $material;
    }
    
  public function show( $idMaterial)
    {
      $material = MaterialReciclavel::where('idEcoponto',$idMaterial)-> get();
      return $material;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomeMaterialReciclavel' => ['required','string'],
        'descricaoMaterialReciclavel' => ['required','string'],
        'tipoMaterialReciclavel' => ['required','string'],
        'instrucoesDescarteMaterialReciclavel' => ['required','string']
        ]);

        return response()->json(MaterialReciclavel::create($data), 201);
    }

    public function destroy(string $idMaterial)
    {
        MaterialReciclavel::where('idMaterial', $idMaterial)->delete();
        return response()->json(['message' => 'Material removido com sucesso.']);
    }
}
