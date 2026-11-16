<?php

namespace App\Http\Controllers;

use App\Models\CategoriaPublicacao;
use Illuminate\Http\Request;

class CategoriaPublicacaoController extends Controller
{
    public function index()
    {
        return response()->json(CategoriaPublicacao::paginate(20));
    }

    public function show(CategoriaPublicacao $categoriaPublicacao)
    {
        return response()->json($categoriaPublicacao);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descricaoCategoriaPublicacao' => ['required', 'string']
        ]);

        return response()->json(CategoriaPublicacao::create($data), 201);
    }

    public function update(Request $request, CategoriaPublicacao $categoriaPublicacao)
    {
        $data = $request->validate([
            'descricaoCategoriaPublicacao' => ['sometimes', 'string']
        ]);

        $categoriaPublicacao->update($data);

        return response()->json($categoriaPublicacao->fresh());
    }

    public function destroy(CategoriaPublicacao $categoriaPublicacao)
    {
        $categoriaPublicacao->delete();

        return response()->json([
            'message' => 'Registro removido com sucesso.'
        ]);
    }
}
