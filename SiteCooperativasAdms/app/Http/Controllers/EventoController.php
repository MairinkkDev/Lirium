<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EventoController extends Controller
{
public function painel(Request $request)
{
    abort_unless(Auth::user()->isInstituicao(), 403);

    $status = $request->query('status', 'todos');
    $busca  = trim((string) $request->query('busca', ''));

    $eventos = Auth::user()->instituicao->eventos()
    ->withAvg('avaliacoes as nota_media', 'notaAvaliacao')
    ->when($status !== 'todos', function ($query) use ($status) {
        $query->where('statusEvento', $status);
    })
    ->when($busca !== '', function ($query) use ($busca) {
        $query->where('nomeEvento', 'like', '%' . $busca . '%');
    })
    ->orderBy('dataInicioEvento')
    ->paginate(10)
    ->withQueryString();

    return view('painelInstituicao.eventos', compact('eventos', 'status', 'busca'));
}

public function criar()
{
    abort_unless(Auth::user()->isInstituicao(), 403);

    return view('painelInstituicao.eventoCriar');
}
public function avaliacoes(string $idEvento)
{
    $evento = $this->buscarEventoDaInstituicao($idEvento);

    // Distribuição das notas: [5 => qtd, 4 => qtd, ...]
    $contagem = $evento->avaliacoes()
        ->whereNotNull('notaAvaliacao')
        ->selectRaw('notaAvaliacao as nota, count(*) as total')
        ->groupBy('notaAvaliacao')
        ->pluck('total', 'nota');

    $distribuicao = [];
    foreach ([5, 4, 3, 2, 1] as $n) {
        $distribuicao[$n] = (int) ($contagem[$n] ?? 0);
    }

    // Lista de avaliações com o nome do usuário
    $avaliacoes = $evento->avaliacoes()
        ->with('usuario')
        ->whereNotNull('notaAvaliacao')
        ->latest('created_at')
        ->get()
        ->map(fn ($a) => [
            'nome'       => $a->usuario->nomeUsuario ?? 'Usuário removido',
            'data'       => $a->created_at?->format('d/m/Y') ?? '-',
            'nota'       => (int) $a->notaAvaliacao,
            'comentario' => $a->descricaoAvaliacao ?? '',
        ]);

    $evento = [
        'nome'   => $evento->nomeEvento,
        'inicio' => $evento->dataInicioEvento?->format('d/m/Y') ?? '-',
        'fim'    => $evento->dataFimEvento?->format('d/m/Y') ?? '-',
    ];

    return view('painelInstituicao.eventoAvaliacoes', compact('evento', 'distribuicao', 'avaliacoes'));
}
    public function store(Request $request)
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        $dados = $this->validarDadosBase($request);
        $dados['statusEvento'] = Evento::STATUS_AGENDADO;

        $evento = Evento::create($dados);

        $evento->instituicoes()->attach(Auth::user()->instituicao->idInstituicao);

        return redirect()->route('evento.painel')
            ->with('mensagem', 'Evento criado com sucesso.');
    }

    public function detalhe(string $idEvento)
    {
        abort_unless(Auth::user()->isInstituicao(), 403);
        $evento = $this->buscarEventoDaInstituicao($idEvento);
        return view('painelInstituicao.eventoAlterar')->with('evento', $evento);
    }

    public function atualizar(Request $request, string $idEvento)
    {
        $evento = $this->buscarEventoDaInstituicao($idEvento);

        $dados = $this->validarDadosBase($request);

        $dados['statusEvento'] = $request->validate([
            'statusEvento' => ['required', 'string', 'in:agendado,em_andamento,concluido,cancelado'],
        ])['statusEvento'];

        $evento->update($dados);

        return back()->with('mensagem', "Evento #{$idEvento} atualizado com sucesso.");
    }
        public function encerrar(string $idEvento)
    {
        $evento = $this->buscarEventoDaInstituicao($idEvento);

        $evento->update(['statusEvento' => Evento::STATUS_CONCLUIDO]);

        return redirect()->route('evento.painel')
            ->with('mensagem', "Evento #{$idEvento} encerrado com sucesso.");
    }

    public function excluir(string $idEvento)
    {
        $evento = $this->buscarEventoDaInstituicao($idEvento);

        $evento->instituicoes()->detach();
        $evento->delete();

        return redirect()->route('evento.painel')
            ->with('mensagem', "Evento #{$idEvento} excluído com sucesso.");
    }

    private function buscarEventoDaInstituicao(string $idEvento): Evento
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        return Auth::user()->instituicao->eventos()
            ->where('tb_evento.idEvento', $idEvento)
            ->firstOrFail();
    }

    private function validarDadosBase(Request $request): array
    {
        return $request->validate([
            'nomeEvento'                => ['required', 'string', 'max:150'],
            'descricaoEvento'           => ['nullable', 'string'],
            'dataInicioEvento'          => ['required', 'date'],
            'dataFimEvento'             => ['nullable', 'date', 'after_or_equal:dataInicioEvento'],
            'cepEnderecoEvento'         => ['required', 'string', 'max:9'],
            'logradouroEnderecoEvento'  => ['required', 'string', 'max:150'],
            'numeroEnderecoEvento'      => ['required', 'string', 'max:10'],
            'complementoEnderecoEvento' => ['nullable', 'string', 'max:100'],
            'bairroEnderecoEvento'      => ['required', 'string', 'max:100'],
            'cidadeEnderecoEvento'      => ['required', 'string', 'max:100'],
            'ufEnderecoEvento'          => ['required', 'string', 'max:2'],
        ]);
    }
}   