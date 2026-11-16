<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EnderecoInstituicao;
use Illuminate\Http\Request;

class EnderecoInstituicaoController extends Controller
{
    public function show($idInstituicao)
    {
       
       $enderecoInstituicao = EnderecoInstituicao::where('idInstituicao', $idInstituicao)->get();
       return $enderecoInstituicao;
    }

    public function store(Request $request, $idInstituicao)
    {
        $data = $request->validate([
            'cepEnderecoInstituicao' => ['nullable','string','max:9'],
            'logradouroEnderecoInstituicao' => ['nullable','string','max:150'],
            'numeroEnderecoInstituicao' => ['nullable','string','max:10'],
            'complementoEnderecoInstituicao' => ['nullable','string','max:100'],
            'bairroEnderecoInstituicao' => ['nullable','string','max:100'],
            'cidadeEnderecoInstituicao' => ['nullable','string','max:100'],
            'ufEnderecoInstituicao' => ['nullable','string','size:2'],
        ]);

        $endereco = EnderecoInstituicao::updateOrCreate(
            ['idInstituicao' => $idInstituicao],
            $data
        );

        return response()->json($endereco, 201);
    }

    public function destroy(String $idInstituicao)
    {
        EnderecoInstituicao::where('idInstituicao', $idInstituicao)->delete();
        return response()->json(['message' => 'Endereço removido com sucesso.']);
    }
}
