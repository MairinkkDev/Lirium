<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Instituicao;
use App\Models\Denuncia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsuarios       = Usuario::count();
        $usuariosAtivos      = Usuario::where('statusUsuario', 'ativo')->count();
        $usuariosInativos    = Usuario::where('statusUsuario', 'inativo')->count();

        $totalInstituicoes       = Instituicao::count();
        $instituicoesAtivas      = Instituicao::where('statusInstituicao', 'ativo')->count();
        $instituicoesInativas    = Instituicao::where('statusInstituicao', 'inativo')->count();

        $totalDenuncias      = Denuncia::count();
        $denunciasPendentes  = Denuncia::where('statusDenuncia', 'pendente')->count();
        $denunciasEmAnalise  = Denuncia::where('statusDenuncia', 'em_analise')->count();
        $denunciasResolvidas = Denuncia::where('statusDenuncia', 'resolvida')->count();

        // Variação comparando os últimos 30 dias com os 30 dias anteriores a esses
        $inicioPeriodoAtual    = now()->subDays(30);
        $inicioPeriodoAnterior = now()->subDays(60);

        $variacaoUsuarios = $this->calcularVariacao(
            Usuario::where('created_at', '>=', $inicioPeriodoAtual)->count(),
            Usuario::whereBetween('created_at', [$inicioPeriodoAnterior, $inicioPeriodoAtual])->count()
        );

        $variacaoInstituicoes = $this->calcularVariacao(
            Instituicao::where('created_at', '>=', $inicioPeriodoAtual)->count(),
            Instituicao::whereBetween('created_at', [$inicioPeriodoAnterior, $inicioPeriodoAtual])->count()
        );

        $variacaoDenuncias = $this->calcularVariacao(
            Denuncia::where('created_at', '>=', $inicioPeriodoAtual)->count(),
            Denuncia::whereBetween('created_at', [$inicioPeriodoAnterior, $inicioPeriodoAtual])->count()
        );

        // Usuários online = tiveram alguma requisição autenticada nos últimos 5 minutos
        $usuariosOnline  = Usuario::where('ultimoAcessoUsuario', '>=', now()->subMinutes(5))->count();
        $usuariosOffline = max($totalUsuarios - $usuariosOnline, 0);
        // Uma casa decimal (ex.: 62,6%) para o texto "X% dos N usuários cadastrados"
        $percentualOnline = $totalUsuarios > 0 ? round(($usuariosOnline / $totalUsuarios) * 100, 1) : 0;

        // Novos cadastros por dia (usuários) nos últimos 30 dias, incluindo hoje
        $inicioSerie = now()->subDays(29)->startOfDay();
        $cadastrosPorDiaBruto = Usuario::where('created_at', '>=', $inicioSerie)
            ->get(['created_at'])
            ->countBy(fn ($u) => $u->created_at->format('Y-m-d'));

        $cadastrosPorDia = collect(range(0, 29))->map(function ($i) use ($inicioSerie, $cadastrosPorDiaBruto) {
            $dia = $inicioSerie->copy()->addDays($i);

            return [
                'data'  => $dia,
                'total' => (int) $cadastrosPorDiaBruto->get($dia->format('Y-m-d'), 0),
            ];
        });

        // Atividades recentes = últimos registros reais de usuários, instituições e denúncias.
        // "Ver todas" (?atividades=todas) amplia a lista.
        $verTodasAtividades = request('atividades') === 'todas';

        $rotulosDenuncia = [
            'pendente'  => ['Pendente',  'pending'],
            'em_analise' => ['Em análise', 'analise'],
            'resolvida' => ['Encerrada', 'resolved'],
        ];

        $atividadesRecentes = collect()
            ->concat(
                Usuario::latest()->take(10)->get()->map(fn ($u) => [
                    'tipo'    => 'usuario',
                    'titulo'  => 'Novo usuário cadastrado',
                    'detalhe' => $u->nomeUsuario,
                    'badge'   => null,
                    'data'    => $u->created_at,
                ])
            )
            ->concat(
                Instituicao::latest()->take(10)->get()->map(fn ($i) => [
                    'tipo'    => 'instituicao',
                    'titulo'  => $i->tipoInstituicao === 'Ong' ? 'Nova ONG cadastrada' : 'Nova cooperativa cadastrada',
                    'detalhe' => $i->nomeInstituicao,
                    'badge'   => null,
                    'data'    => $i->created_at,
                ])
            )
            ->concat(
                Denuncia::with('usuario')->latest()->take(10)->get()->map(fn ($d) => [
                    'tipo'    => 'denuncia',
                    'titulo'  => 'Nova denúncia registrada',
                    'detalhe' => 'Protocolo #' . str_pad($d->idDenuncia, 4, '0', STR_PAD_LEFT)
                                 . ' · ' . ($d->usuario->nomeUsuario ?? '-'),
                    'badge'   => $rotulosDenuncia[$d->statusDenuncia]
                                 ?? [ucfirst(str_replace('_', ' ', $d->statusDenuncia)), 'analise'],
                    'data'    => $d->created_at,
                ])
            )
            ->filter(fn ($item) => !is_null($item['data']))
            ->sortByDesc('data')
            ->take($verTodasAtividades ? 30 : 6)
            ->values();

        return view('painelAdmin.dash', compact(
            'totalUsuarios', 'usuariosAtivos', 'usuariosInativos',
            'totalInstituicoes', 'instituicoesAtivas', 'instituicoesInativas',
            'totalDenuncias', 'denunciasPendentes', 'denunciasEmAnalise', 'denunciasResolvidas',
            'variacaoUsuarios', 'variacaoInstituicoes', 'variacaoDenuncias',
            'usuariosOnline', 'usuariosOffline', 'percentualOnline',
            'cadastrosPorDia', 'atividadesRecentes', 'verTodasAtividades'
        ));
    }

    private function calcularVariacao(int $atual, int $anterior): array
    {
        if ($anterior === 0) {
            return [
                'direcao'    => $atual > 0 ? 'up' : 'neutro',
                'percentual' => $atual > 0 ? 100 : 0,
            ];
        }

        $percentual = round((($atual - $anterior) / $anterior) * 100);

        return [
            'direcao'    => $percentual >= 0 ? 'up' : 'down',
            'percentual' => abs($percentual),
        ];
    }
}