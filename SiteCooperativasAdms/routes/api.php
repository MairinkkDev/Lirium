<?php

use Illuminate\Support\Facades\Route;

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


// DASHBOARD
Route::get('/dashboard/usuarios/total', [DashboardController::class, 'totalUsuarios']);
Route::get('/dashboard/usuarios/ativos', [DashboardController::class, 'usuariosAtivos']);
Route::get('/dashboard/usuarios/inativos', [DashboardController::class, 'usuariosInativos']);

Route::get('/dashboard/instituicoes/total', [DashboardController::class, 'totalInstituicoes']);

Route::get('/dashboard/denuncias/total', [DashboardController::class, 'totalDenuncias']);
Route::get('/dashboard/denuncias/pendentes', [DashboardController::class, 'denunciasPendentes']);
Route::get('/dashboard/denuncias/em-analise', [DashboardController::class, 'denunciasEmAnalise']);
Route::get('/dashboard/denuncias/resolvidas', [DashboardController::class, 'denunciasResolvidas']);

// USUÁRIOS
Route::get('/usuarios', [UsuarioController::class, 'index']);
Route::get('/usuarios/{idUsuario}', [UsuarioController::class, 'show']);
Route::post('/usuarioCriar', [UsuarioController::class, 'store']);
Route::put('/usuarios/{idUsuario}', [UsuarioController::class, 'update']);
Route::delete('/usuarios/{idUsuario}', [UsuarioController::class, 'destroy']);


// ECOPONTOS
Route::get('/ecopontos', [EcopontoController::class, 'index']);
Route::post('/ecopontos', [EcopontoController::class, 'store']);
Route::get('/ecopontos/{idEcoponto}', [EcopontoController::class, 'show']);
Route::delete('/ecopontos/{idEcoponto}', [EcopontoController::class, 'destroy']);


// DENÚNCIAS
Route::get('/denuncias', [DenunciaController::class, 'index']);
Route::post('/denuncias', [DenunciaController::class, 'store']);
Route::get('/denuncias/{idDenuncia}', [DenunciaController::class, 'show']);
Route::delete('/denuncias/{idDenuncia}', [DenunciaController::class, 'destroy']);


// MATERIAIS
Route::get('/materiais', [MaterialReciclavelController::class, 'index']);
Route::post('/materiais', [MaterialReciclavelController::class, 'store']);
Route::get('/materiais/{idMaterialReciclavel}', [MaterialReciclavelController::class, 'show']);
Route::delete('/materiais/{idMaterialReciclavel}', [MaterialReciclavelController::class, 'destroy']);


// INSTITUIÇÕES
Route::get('/instituicoes', [InstituicaoController::class, 'index']);
Route::post('/instituicoes', [InstituicaoController::class, 'store']);
Route::get('/instituicoes/{idInstituicao}', [InstituicaoController::class, 'show']);
Route::delete('/instituicoes/{idInstituicao}', [InstituicaoController::class, 'destroy']);

    
// CATEGORIAS DE DENÚNCIA
Route::get('/categorias-denuncia', [CategoriaDenunciaController::class, 'index']);
Route::post('/categorias-denuncia', [CategoriaDenunciaController::class, 'store']);
Route::get('/categorias-denuncia/{idCategoriaDenuncia}', [CategoriaDenunciaController::class, 'show']);
Route::delete('/categorias-denuncia/{idCategoriaDenuncia}', [CategoriaDenunciaController::class, 'destroy']);


// ADMINISTRADORES
Route::get('/administradores', [AdministradorController::class, 'index']);
Route::get('/administradores/{idAdministrador}', [AdministradorController::class, 'show']);
Route::post('/administradores', [AdministradorController::class, 'store']);
Route::delete('/administradores/{idAdministrador}', [AdministradorController::class, 'destroy']);


// TELEFONES DO USUÁRIO
Route::get('/usuarios/{idUsuario}/telefones', [TelefoneUsuarioController::class, 'index']);
Route::post('/usuarios/{idUsuario}/telefones', [TelefoneUsuarioController::class, 'store']);
Route::delete('/telefones-usuario/{idTelefoneUsuario}', [TelefoneUsuarioController::class, 'destroy']);


// TELEFONES DA INSTITUIÇÃO
Route::get('/instituicoes/{idInstituicao}/telefones', [TelefoneInstituicaoController::class, 'index']);
Route::post('/instituicoes/{idInstituicao}/telefones', [TelefoneInstituicaoController::class, 'store']);
Route::delete('/telefones-instituicao/{idTelefoneInstituicao}', [TelefoneInstituicaoController::class, 'destroy']);


// ENDEREÇO DA INSTITUIÇÃO
Route::get('/instituicoes/{idInstituicao}/endereco', [EnderecoInstituicaoController::class, 'show']);
Route::post('/instituicoes/{idInstituicao}/endereco', [EnderecoInstituicaoController::class, 'store']);
Route::delete('/instituicoes/{idInstituicao}/endereco', [EnderecoInstituicaoController::class, 'destroy']);
