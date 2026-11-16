<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\VerificaNivelAcesso;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EcopontoController;
use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialReciclavelController;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\PublicacaoController;
use App\Http\Controllers\CategoriaPublicacaoController;
use App\Http\Controllers\CategoriaDenunciaController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\TelefoneUsuarioController;
use App\Http\Controllers\TelefoneInstituicaoController;
use App\Http\Controllers\EnderecoInstituicaoController;
use App\Http\Controllers\ImagemPublicacaoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\ContribuidorController;

// ==================================================
// LOGIN E CADASTRO (públicas)
// ==================================================

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])
    ->name('login');

Route::post('/login', [UsuarioController::class, 'fazerLogin'])
    ->name('login.autenticar');

Route::post('/logout', [UsuarioController::class, 'fazerLogOut'])
    ->middleware(Authenticate::class)
    ->name('logout');

Route::get('/cadastro', [AuthController::class, 'mostrarCadastro'])
    ->name('instituicao.cadastro');

Route::post('/cadastro/instituicao', [AuthController::class, 'cadastrar'])
    ->name('instituicao.criar');

Route::post('/cadastro/usuario', [UsuarioController::class, 'store'])
    ->name('usuario.cadastrar');


// ==================================================
// DASHBOARD (ADMIN)
// ==================================================

Route::middleware([Authenticate::class, VerificaNivelAcesso::class . ':3'])->group(function () {

    Route::get('/home', function () {
        return view('painelAdmin.homeAdmin');
    })->name('admin.home');

    Route::get('/dash', [DashboardController::class, 'index']);
});


// ==================================================
// USUÁRIOS
// ==================================================

// Gerenciamento (somente admin)
Route::middleware([Authenticate::class, VerificaNivelAcesso::class . ':3'])->group(function () {

    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuarios/{idUsuario}', [UsuarioController::class, 'show']);
    Route::put('/usuarios/{idUsuario}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{idUsuario}', [UsuarioController::class, 'destroy']);
});

// Perfil (qualquer usuário logado)
Route::middleware(Authenticate::class)->group(function () {

    Route::get('/meuPerfil', [UsuarioController::class, 'perfil'])
        ->name('perfil.show');

    Route::patch('/meuPerfil', [UsuarioController::class, 'atualizarPerfil'])
        ->name('perfil.atualizar');

    Route::get('/usuario', function () {
        return view('usuario');
    });
});


// ==================================================
// ECOPONTOS
// ==================================================

Route::get('/ecoponto', [EcopontoController::class, 'painel'])
    ->middleware(Authenticate::class)
    ->name('ecoponto.painel');

Route::get('/ecoponto/{idEcoponto}', [EcopontoController::class, 'detalhe'])
    ->middleware(Authenticate::class)
    ->name('ecoponto.detalhe');

Route::patch('/ecoponto/{idEcoponto}', [EcopontoController::class, 'atualizar'])
    ->middleware(Authenticate::class)
    ->name('ecoponto.atualizar');

Route::patch('/ecoponto/{idEcoponto}/inativar', [EcopontoController::class, 'inativar'])
    ->middleware(Authenticate::class)
    ->name('ecoponto.inativar');

Route::patch('/ecoponto/{idEcoponto}/ativar', [EcopontoController::class, 'ativar'])
    ->middleware(Authenticate::class)
    ->name('ecoponto.ativar');

Route::delete('/ecoponto/{idEcoponto}', [EcopontoController::class, 'excluir'])
    ->middleware(Authenticate::class)
    ->name('ecoponto.excluir');


// ==================================================
// DENÚNCIAS
// ==================================================

Route::get('/denuncia', [DenunciaController::class, 'painel'])
    ->middleware(Authenticate::class);

Route::get('/denuncia/{idDenuncia}', [DenunciaController::class, 'detalhe'])
    ->middleware(Authenticate::class)
    ->name('denuncia.detalhe');

Route::patch('/denuncia/{idDenuncia}/concluir', [DenunciaController::class, 'concluir'])
    ->middleware(Authenticate::class)
    ->name('denuncia.concluir');

Route::patch('/denuncia/{idDenuncia}/reabrir', [DenunciaController::class, 'reabrir'])
    ->middleware(Authenticate::class)
    ->name('denuncia.reabrir');

Route::patch('/denuncia/{idDenuncia}/analisar', [DenunciaController::class, 'analisar'])
    ->middleware(Authenticate::class)
    ->name('denuncia.analisar');

Route::delete('/denuncia/{idDenuncia}', [DenunciaController::class, 'excluir'])
    ->middleware(Authenticate::class)
    ->name('denuncia.excluir');


// ==================================================
// INSTITUIÇÕES
// ==================================================

// Início da instituição (somente instituição)
Route::get('/instituicao/inicio', function () {
    return view('welcome');
})
    ->middleware([Authenticate::class, VerificaNivelAcesso::class . ':2'])
    ->name('instituicao.inicio');

// Gerenciamento de instituições (somente admin)
Route::middleware([Authenticate::class, VerificaNivelAcesso::class . ':3'])->group(function () {

    Route::get('/admin/instituicoes', [InstituicaoController::class, 'painel'])
        ->name('admin.instituicoes');

    Route::get('/instituicao/{idInstituicao}', [InstituicaoController::class, 'detalhe'])
        ->name('instituicao.detalhe');

    Route::patch('/instituicao/{idInstituicao}/suspender', [InstituicaoController::class, 'suspender'])
        ->name('instituicao.suspender');

    Route::patch('/instituicao/{idInstituicao}/reativar', [InstituicaoController::class, 'reativar'])
        ->name('instituicao.reativar');

    Route::delete('/instituicao/{idInstituicao}', [InstituicaoController::class, 'excluir'])
        ->name('instituicao.excluir');

    Route::get('/instituicao/{idInstituicao}/editar', [InstituicaoController::class, 'editar'])
        ->name('instituicao.editar');

    Route::put('/instituicao/{idInstituicao}/atualizar', [InstituicaoController::class, 'atualizar'])
        ->name('instituicao.atualizar');
});


// ==================================================
// EVENTOS
// ==================================================

Route::get('/eventos', [EventoController::class, 'painel'])
    ->middleware(Authenticate::class)
    ->name('evento.painel');

Route::get('/eventos/criar', [EventoController::class, 'criar'])
    ->middleware(Authenticate::class)
    ->name('evento.criar.form');

Route::post('/eventos', [EventoController::class, 'store'])
    ->middleware(Authenticate::class)
    ->name('evento.store');

Route::get('/eventos/avaliacoes/{idEvento}', [EventoController::class, 'avaliacoes'])
    ->middleware(Authenticate::class)
    ->name('evento.avaliacoes');

Route::get('/eventos/{idEvento}', [EventoController::class, 'detalhe'])
    ->middleware(Authenticate::class)
    ->name('evento.detalhe');

Route::patch('/eventos/{idEvento}', [EventoController::class, 'atualizar'])
    ->middleware(Authenticate::class)
    ->name('evento.atualizar');

Route::patch('/eventos/{idEvento}/encerrar', [EventoController::class, 'encerrar'])
    ->middleware(Authenticate::class)
    ->name('evento.encerrar');

Route::delete('/eventos/{idEvento}', [EventoController::class, 'excluir'])
    ->middleware(Authenticate::class)
    ->name('evento.excluir');


// ==================================================
// MATERIAIS, CATEGORIAS E ADMINISTRADORES (ADMIN)
// ==================================================

Route::middleware([Authenticate::class, VerificaNivelAcesso::class . ':3'])->group(function () {

    // Materiais recicláveis
    Route::get('/materiais', [MaterialReciclavelController::class, 'index']);
    Route::post('/materiais', [MaterialReciclavelController::class, 'store']);
    Route::get('/materiais/{idMaterialReciclavel}', [MaterialReciclavelController::class, 'show']);
    Route::delete('/materiais/{idMaterialReciclavel}', [MaterialReciclavelController::class, 'destroy']);

    // Categorias de denúncia
    Route::get('/categorias-denuncia', [CategoriaDenunciaController::class, 'index']);
    Route::post('/categorias-denuncia', [CategoriaDenunciaController::class, 'store']);
    Route::get('/categorias-denuncia/{idCategoriaDenuncia}', [CategoriaDenunciaController::class, 'show']);
    Route::delete('/categorias-denuncia/{idCategoriaDenuncia}', [CategoriaDenunciaController::class, 'destroy']);

    // Categorias de publicação
    Route::get('/categorias-publicacao', [CategoriaPublicacaoController::class, 'index']);
    Route::post('/categorias-publicacao', [CategoriaPublicacaoController::class, 'store']);
    Route::get('/categorias-publicacao/{idCategoriaPublicacao}', [CategoriaPublicacaoController::class, 'show']);
    Route::delete('/categorias-publicacao/{idCategoriaPublicacao}', [CategoriaPublicacaoController::class, 'destroy']);

    // Administradores
    Route::get('/administradores', [AdministradorController::class, 'index']);
    Route::get('/administradores/{idAdministrador}', [AdministradorController::class, 'show']);
    Route::post('/administradores', [AdministradorController::class, 'store']);
    Route::delete('/administradores/{idAdministrador}', [AdministradorController::class, 'destroy']);
});


// ==================================================
// PUBLICAÇÕES
// ==================================================

Route::get('/publicacao', [PublicacaoController::class, 'painel'])
    ->middleware(Authenticate::class)
    ->name('publicacao.painel');

Route::get('/publicacao/criar', [PublicacaoController::class, 'criar'])
    ->middleware(Authenticate::class)
    ->name('publicacao.criar.form');

Route::post('/publicacao', [PublicacaoController::class, 'store'])
    ->middleware(Authenticate::class)
    ->name('publicacao.store');

Route::get('/publicacao/{idPublicacao}', [PublicacaoController::class, 'detalhe'])
    ->middleware(Authenticate::class)
    ->name('publicacao.detalhe');

Route::patch('/publicacao/{idPublicacao}', [PublicacaoController::class, 'atualizar'])
    ->middleware(Authenticate::class)
    ->name('publicacao.atualizar');

Route::delete('/publicacao/{idPublicacao}', [PublicacaoController::class, 'excluir'])
    ->middleware(Authenticate::class)
    ->name('publicacao.excluir');

Route::delete('/publicacao/{idPublicacao}/imagens/{idImagemPublicacao}', [PublicacaoController::class, 'excluirImagem'])
    ->middleware(Authenticate::class)
    ->name('publicacao.imagem.excluir');


// ==================================================
// TELEFONES E ENDEREÇO DA INSTITUIÇÃO (exigem login)
// ==================================================

Route::middleware(Authenticate::class)->group(function () {

    // Telefones
    Route::get('/instituicoes/{idInstituicao}/telefones', [TelefoneInstituicaoController::class, 'index']);
    Route::post('/instituicoes/{idInstituicao}/telefones', [TelefoneInstituicaoController::class, 'store']);
    Route::delete('/telefones-instituicao/{idTelefoneInstituicao}', [TelefoneInstituicaoController::class, 'destroy']);

    // Endereço
    Route::get('/instituicoes/{idInstituicao}/endereco', [EnderecoInstituicaoController::class, 'show']);
    Route::post('/instituicoes/{idInstituicao}/endereco', [EnderecoInstituicaoController::class, 'store']);
    Route::delete('/instituicoes/{idInstituicao}/endereco', [EnderecoInstituicaoController::class, 'destroy']);
});


// ==================================================
// ESTOQUE E PREÇOS
// ==================================================

Route::get('/estoque', [EstoqueController::class, 'painel'])
    ->middleware(Authenticate::class)
    ->name('estoque.painel');

Route::post('/estoque/adicionar', [EstoqueController::class, 'adicionar'])
    ->middleware(Authenticate::class)
    ->name('estoque.adicionar');

Route::patch('/estoque/alterar', [EstoqueController::class, 'alterar'])
    ->middleware(Authenticate::class)
    ->name('estoque.alterar');

Route::delete('/estoque/material', [EstoqueController::class, 'excluirMaterial'])
    ->middleware(Authenticate::class)
    ->name('estoque.excluir');

Route::post('/estoque/preco', [EstoqueController::class, 'adicionarPreco'])
    ->middleware(Authenticate::class)
    ->name('estoque.preco.adicionar');

Route::put('/estoque/preco', [EstoqueController::class, 'alterarPreco'])
    ->middleware(Authenticate::class)
    ->name('estoque.preco.alterar');

Route::delete('/estoque/preco', [EstoqueController::class, 'excluirPreco'])
    ->middleware(Authenticate::class)
    ->name('estoque.preco.excluir');


// ==================================================
// CONTRIBUIDORES
// ==================================================

Route::get('/contribuidor', [ContribuidorController::class, 'painel'])
    ->middleware(Authenticate::class)
    ->name('contribuidor.painel');

Route::get('/contribuidor/criar', [ContribuidorController::class, 'criar'])
    ->middleware(Authenticate::class)
    ->name('contribuidor.criar.form');

Route::post('/contribuidor', [ContribuidorController::class, 'store'])
    ->middleware(Authenticate::class)
    ->name('contribuidor.store');

Route::get('/contribuidor/{id}/alterar', [ContribuidorController::class, 'editar'])
    ->middleware(Authenticate::class)
    ->name('contribuidor.alterar.form');

Route::patch('/contribuidor/{id}', [ContribuidorController::class, 'atualizar'])
    ->middleware(Authenticate::class)
    ->name('contribuidor.atualizar');

Route::delete('/contribuidor/{id}', [ContribuidorController::class, 'excluir'])
    ->middleware(Authenticate::class)
    ->name('contribuidor.excluir');

Route::post('/contribuidor/{id}/adicionar', [ContribuidorController::class, 'adicionarQuantidade'])
    ->middleware(Authenticate::class)
    ->name('contribuidor.adicionar');


// ==================================================
// TELAS DE VISUALIZAÇÃO / PREVIEW
// ==================================================

Route::get('/preview/meu-perfil', function () {
    return view('painelInstituicao.meuPerfil');
})
    ->middleware(Authenticate::class);

Route::get('/preview/evento-alterar', function () {
    return view('painelInstituicao.eventoAlterar');
})
    ->middleware(Authenticate::class);


// ==================================================
// FOTO DE PERFIL
// ==================================================

Route::get('/foto-perfil/{tipo}/{id}', function (string $tipo, int $id) {

    $caminho = $tipo === 'instituicao'
        ? \App\Models\ImagemInstituicao::where('idInstituicao', $id)->value('caminhoImagemInstituicao')
        : \App\Models\ImagemUsuario::where('idUsuario', $id)->value('caminhoImagemUsuario');

    abort_unless(
        $caminho && Storage::disk('public')->exists($caminho),
        404
    );

    while (ob_get_level()) {
        ob_end_clean();
    }

    return Storage::disk('public')->response($caminho);

})
    ->middleware(Authenticate::class)
    ->name('foto.perfil');