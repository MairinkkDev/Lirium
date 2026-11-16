<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Publicacao;
use App\Models\ImagemPublicacao;
use App\Models\CategoriaPublicacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PublicacaoController extends Controller
{
    public function painel(Request $request)
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        $status = $request->query('status', 'todos');

        $publicacao = Auth::user()->instituicao->publicacoes()
            ->with(['imagens', 'categoria'])
            ->when($status !== 'todos', function ($query) use ($status) {
                $query->where('statusPublicacao', $status);
            })
            ->orderByDesc('dataCriacaoPublicacao')
            ->paginate(10)
            ->withQueryString();

        return view('publicacao')
    ->with('publicacao', $publicacao)
    ->with('status', $status);
    }

    public function criar()
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        $categorias = CategoriaPublicacao::orderBy('descricaoCategoriaPublicacao')->get();

        return view('publicacaoCriar')->with('categorias', $categorias);
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        $dados = $this->validarDadosBase($request);
        $dados['idInstituicao']   = Auth::user()->instituicao->idInstituicao;
        $dados['statusPublicacao'] = 'ativo';

        $publicacao = Publicacao::create($dados);

        $this->salvarImagens($request, $publicacao);

        return redirect()->route('publicacao.painel')
            ->with('mensagem', 'Publicação criada com sucesso.');
    }

    public function detalhe(string $idPublicacao)
    {
        $publicacao = $this->buscarPublicacaoDaInstituicao($idPublicacao);
        $publicacao->load('imagens');

        $categorias = CategoriaPublicacao::orderBy('descricaoCategoriaPublicacao')->get();

        return view('publicacaoDetalhada')
            ->with('publicacao', $publicacao)
            ->with('categorias', $categorias);
    }

    public function atualizar(Request $request, string $idPublicacao)
    {
        $publicacao = $this->buscarPublicacaoDaInstituicao($idPublicacao);

        $dados = $this->validarDadosBase($request);

        $dados['statusPublicacao'] = $request->validate([
            'statusPublicacao' => ['required', 'string', 'in:ativo,inativo'],
        ])['statusPublicacao'];

        $publicacao->update($dados);

        $this->salvarImagens($request, $publicacao);

        return back()->with('mensagem', "Publicação #{$idPublicacao} atualizada com sucesso.");
    }

    public function excluir(string $idPublicacao)
    {
        $publicacao = $this->buscarPublicacaoDaInstituicao($idPublicacao);

        foreach ($publicacao->imagens as $imagem) {
            Storage::disk('public')->delete($imagem->caminhoImagemPublicacao);
        }

        $publicacao->delete();

        return redirect()->route('publicacao.painel')
            ->with('mensagem', "Publicação #{$idPublicacao} excluída com sucesso.");
    }

    public function excluirImagem(string $idPublicacao, string $idImagemPublicacao)
    {
        $publicacao = $this->buscarPublicacaoDaInstituicao($idPublicacao);

        $imagem = $publicacao->imagens()
            ->where('idImagemPublicacao', $idImagemPublicacao)
            ->firstOrFail();

        Storage::disk('public')->delete($imagem->caminhoImagemPublicacao);
        $imagem->delete();

        return back()->with('mensagem', 'Imagem removida com sucesso.');
    }

    private function buscarPublicacaoDaInstituicao(string $idPublicacao): Publicacao
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        return Auth::user()->instituicao->publicacoes()
            ->where('tb_publicacao.idPublicacao', $idPublicacao)
            ->firstOrFail();
    }

  private function validarDadosBase(Request $request): array
{
    $request->validate([
        'imagens.*' => ['nullable', 'image', 'max:2048'],
    ]);

    return $request->validate([
        'tituloPublicacao'      => ['required', 'string', 'max:150'],
        'descricaoPublicacao'   => ['nullable', 'string'],
        'idCategoriaPublicacao' => ['required', 'integer', 'exists:tb_categoria_publicacao,idCategoriaPublicacao'],
    ]);
}   

    private function salvarImagens(Request $request, Publicacao $publicacao): void
    {
        if (! $request->hasFile('imagens')) {
            return;
        }

        foreach ($request->file('imagens') as $arquivo) {
            $request->validate([]); // placeholder, validação real abaixo por arquivo

            $caminho = $arquivo->store('publicacoes', 'public');

            ImagemPublicacao::create([
                'caminhoImagemPublicacao' => $caminho,
                'idPublicacao'            => $publicacao->idPublicacao,
            ]);
        }
    }
}