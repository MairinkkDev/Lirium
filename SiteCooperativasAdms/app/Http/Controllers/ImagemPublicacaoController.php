<?php

namespace App\Http\Controllers;

use App\Models\ImagemPublicacao;
use Illuminate\Http\Request;

class ImagemPublicacaoController extends Controller
{
    public function index($idPublicacao)
    {
        return response()->json(
            ImagemPublicacao::where('idPublicacao', $idPublicacao)->get()
        );
    }

    public function store(Request $request, $idPublicacao)
    {
        $data = $request->validate([
            'caminhoImagemPublicacao' => ['required', 'string', 'max:255']
        ]);

        return response()->json(
            ImagemPublicacao::create($data + [
                'idPublicacao' => $idPublicacao
            ]),
            201
        );
    }

    public function destroy(ImagemPublicacao $imagemPublicacao)
    {
        $imagemPublicacao->delete();

        return response()->json([
            'message' => 'Imagem removida com sucesso.'
        ]);
    }
}
