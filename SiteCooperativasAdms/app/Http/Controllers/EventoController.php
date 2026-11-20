<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EVENTOS DA INSTITUIÇÃO
    |--------------------------------------------------------------------------
    */

    public function painel(Request $request)
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        $instituicao = Auth::user()->instituicao;

        abort_if(!$instituicao, 403);

        $status = $request->query('status', 'todos');
        $busca = trim((string) $request->query('busca', ''));

        $statusPermitidos = [
            'pendente',
            'agendado',
            'em_andamento',
            'concluido',
            'cancelado',
            'recusado',
        ];

        $eventos = $instituicao->eventos()
            ->withAvg('avaliacoes as nota_media', 'notaAvaliacao')
            ->when(
                $status !== 'todos' && in_array($status, $statusPermitidos),
                fn($query) => $query->where('statusEvento', $status)
            )
            ->when(
                $busca !== '',
                fn($query) => $query->where('nomeEvento', 'like', "%{$busca}%")
            )
            ->orderBy('dataInicioEvento')
            ->paginate(10)
            ->withQueryString();

        return view(
            'painelInstituicao.eventos',
            compact('eventos', 'status', 'busca')
        );
    }

    public function criar()
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        return view('painelInstituicao.eventoCriar');
    }

    public function avaliacoes(string $idEvento)
    {
        $evento = $this->buscarEventoDaInstituicao($idEvento);

        $contagem = $evento->avaliacoes()
            ->whereNotNull('notaAvaliacao')
            ->selectRaw('notaAvaliacao as nota, count(*) as total')
            ->groupBy('notaAvaliacao')
            ->pluck('total', 'nota');

        $distribuicao = [];

        foreach ([5, 4, 3, 2, 1] as $nota) {
            $distribuicao[$nota] = (int) ($contagem[$nota] ?? 0);
        }

        $avaliacoes = $evento->avaliacoes()
            ->with('usuario')
            ->whereNotNull('notaAvaliacao')
            ->latest('created_at')
            ->get()
            ->map(fn($avaliacao) => [
                'nome' => $avaliacao->usuario->nomeUsuario ?? 'Usuário removido',
                'data' => $avaliacao->created_at?->format('d/m/Y') ?? '-',
                'nota' => (int) $avaliacao->notaAvaliacao,
                'comentario' => $avaliacao->descricaoAvaliacao ?? '',
            ]);

        $dadosEvento = [
            'nome' => $evento->nomeEvento,
            'inicio' => $evento->dataInicioEvento?->format('d/m/Y') ?? '-',
            'fim' => $evento->dataFimEvento?->format('d/m/Y') ?? '-',
        ];

        return view(
            'painelInstituicao.eventoAvaliacoes',
            compact('dadosEvento', 'distribuicao', 'avaliacoes')
        );
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        $instituicao = Auth::user()->instituicao;

        abort_if(!$instituicao, 403);

        $dados = $this->validarDadosBase($request);
        $dados['statusEvento'] = Evento::STATUS_PENDENTE;

        DB::transaction(function () use ($dados, $instituicao) {
            $evento = Evento::create($dados);

            $evento->instituicoes()->attach(
                $instituicao->idInstituicao
            );
        });

        return redirect()
            ->route('evento.painel')
            ->with(
                'mensagem',
                'Solicitação de evento enviada! Aguarde a aprovação do administrador.'
            );
    }

    public function detalhe(string $idEvento)
    {
        $evento = $this->buscarEventoDaInstituicao($idEvento);

        return view('painelInstituicao.eventoAlterar', compact('evento'));
    }

    public function atualizar(Request $request, string $idEvento)
    {
        $evento = $this->buscarEventoDaInstituicao($idEvento);

        $dados = $this->validarDadosBase($request);

        $evento->update($dados);

        return back()->with(
            'mensagem',
            "Evento #{$idEvento} atualizado com sucesso."
        );
    }

    public function encerrar(string $idEvento)
    {
        $evento = $this->buscarEventoDaInstituicao($idEvento);

        abort_unless(
            in_array($evento->statusEvento, [
                Evento::STATUS_AGENDADO,
                Evento::STATUS_EM_ANDAMENTO,
            ]),
            403,
            'Este evento não pode ser encerrado.'
        );

        $evento->update([
            'statusEvento' => Evento::STATUS_CONCLUIDO,
        ]);

        return redirect()
            ->route('evento.painel')
            ->with('mensagem', "Evento #{$idEvento} encerrado com sucesso.");
    }

    public function excluir(string $idEvento)
    {
        $evento = $this->buscarEventoDaInstituicao($idEvento);

        DB::transaction(function () use ($evento) {
            $evento->instituicoes()->detach();
            $evento->delete();
        });

        return redirect()
            ->route('evento.painel')
            ->with('mensagem', "Evento #{$idEvento} excluído com sucesso.");
    }

    /*
    |--------------------------------------------------------------------------
    | GERENCIAMENTO ADMINISTRATIVO DE EVENTOS
    |--------------------------------------------------------------------------
    */

    /**
     * Lista os eventos e as solicitações na mesma página administrativa.
     */
    public function painelAdmin(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $status = $request->query('status', 'todos');
        $busca = trim((string) $request->query('busca', ''));
        $dataInicio = $request->query('dataInicio', '');
        $dataFim = $request->query('dataFim', '');

        // Status disponíveis no filtro administrativo
        $statusPermitidos = [
            'todos',
            'agendado',
            'em_andamento',
            'concluido',
            'cancelado',
            'pendente',
        ];

        // Verifica se o status recebido é válido
        if (!in_array($status, $statusPermitidos)) {
            $status = 'todos';
        }

        // Consulta inicial dos eventos
        $query = Evento::with('instituicoes');

        // Filtro por status
        if ($status === 'todos') {
            $query->whereIn('statusEvento', [
                'agendado',
                'em_andamento',
                'concluido',
                'cancelado',
                'pendente',
            ]);
        } else {
            $query->where('statusEvento', $status);
        }

        // Busca pelo nome do evento ou da instituição
        if ($busca !== '') {
            $query->where(function ($subquery) use ($busca) {
                $subquery->where(
                    'nomeEvento',
                    'like',
                    "%{$busca}%"
                )->orWhereHas('instituicoes', function ($instituicao) use ($busca) {
                    $instituicao->where(
                        'nomeInstituicao',
                        'like',
                        "%{$busca}%"
                    );
                });
            });
        }

        // Filtro pela data inicial
        if ($dataInicio !== '') {
            $query->whereDate('dataInicioEvento', '>=', $dataInicio);
        }

        // Filtro pela data final
        if ($dataFim !== '') {
            $query->whereDate('dataInicioEvento', '<=', $dataFim);
        }

        // Ordenação e paginação
        $eventos = $query
            ->orderBy('dataInicioEvento')
            ->paginate(10)
            ->withQueryString();

        return view(
            'painelAdmin.EventosAdmin',
            compact(
                'eventos',
                'status',
                'busca',
                'dataInicio',
                'dataFim'
            )
        );
    }

    /**
     * Exibe os detalhes de qualquer evento para o administrador.
     */
    public function detalheAdmin(string $idEvento)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $evento = Evento::with('instituicoes')
            ->findOrFail($idEvento);

        return view(
            'painelAdmin.EventosDetalhes',
            compact('evento')
        );
    }

    /**
     * Aprova uma solicitação de evento.
     */
    public function aprovar(string $idEvento)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $evento = Evento::findOrFail($idEvento);

        abort_unless(
            $evento->statusEvento === Evento::STATUS_PENDENTE,
            403,
            'Este evento não está aguardando aprovação.'
        );

        $evento->update([
            'statusEvento' => Evento::STATUS_AGENDADO,
        ]);

        return redirect()
            ->route('admin.eventos.painel', ['status' => 'pendente'])
            ->with('mensagem', 'Evento aprovado com sucesso!');
    }

    /**
     * Recusa uma solicitação de evento.
     */
    public function recusar(string $idEvento)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $evento = Evento::findOrFail($idEvento);

        abort_unless(
            $evento->statusEvento === Evento::STATUS_PENDENTE,
            403,
            'Este evento não está aguardando aprovação.'
        );

        $evento->update([
            'statusEvento' => Evento::STATUS_RECUSADO,
        ]);

        return redirect()
            ->route('admin.eventos.painel', ['status' => 'pendente'])
            ->with('mensagem', 'Solicitação de evento recusada.');
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS AUXILIARES
    |--------------------------------------------------------------------------
    */

    private function buscarEventoDaInstituicao(string $idEvento): Evento
    {
        abort_unless(Auth::user()->isInstituicao(), 403);

        $instituicao = Auth::user()->instituicao;

        abort_if(!$instituicao, 403);

        return $instituicao->eventos()
            ->where('tb_evento.idEvento', $idEvento)
            ->firstOrFail();
    }

    private function validarDadosBase(Request $request): array
    {
        return $request->validate([
            'nomeEvento' => ['required', 'string', 'max:150'],
            'descricaoEvento' => ['nullable', 'string'],
            'dataInicioEvento' => ['required', 'date'],
            'dataFimEvento' => [
                'nullable',
                'date',
                'after_or_equal:dataInicioEvento',
            ],
            'cepEnderecoEvento' => ['required', 'string', 'max:9'],
            'logradouroEnderecoEvento' => ['required', 'string', 'max:150'],
            'numeroEnderecoEvento' => ['required', 'string', 'max:10'],
            'complementoEnderecoEvento' => ['nullable', 'string', 'max:100'],
            'bairroEnderecoEvento' => ['required', 'string', 'max:100'],
            'cidadeEnderecoEvento' => ['required', 'string', 'max:100'],
            'ufEnderecoEvento' => ['required', 'string', 'size:2'],
        ]);
    }
}