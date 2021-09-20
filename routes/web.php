<?php

use App\Http\Controllers\ChamadaController;
use App\Http\Controllers\SisuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CotaController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\InscricaoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/primeiroAcesso', [CandidatoController::class, 'prepararAdicionar'])->name('primeiro.acesso');
Route::post('/verificacao', [CandidatoController::class, 'verificacao'])->name('primeiroAcesso.verificacao');
Route::get('/editar', [CandidatoController::class , 'editarAcesso'])->name('primeiroAcesso.editar');
Route::post('/atualizar', [UserController::class , 'update'])->name('primeiroAcesso.atualizar');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::resource('usuarios', UserController::class);

    Route::resource('sisus', SisuController::class);

    Route::resource('chamadas', ChamadaController::class);

    Route::get('/sisus/{sisu_id}/criar-chamada', [ChamadaController::class, 'create'])
        ->name('chamadas.create');

    Route::post('/sisus/{sisu_id}/importar-candidatos/{chamada_id}', [ChamadaController::class, 'importarCandidatos'])
    ->name('chamadas.importar.candidatos');

    Route::resource('cursos', CursoController::class);

    Route::resource('cotas', CotaController::class);

    Route::resource('inscricaos', InscricaoController::class);
    Route::get('/inscricaos/{inscricao_id}/documentacao', [InscricaoController::class, 'showInscricaoDocumentacao'])->name('inscricao.documentacao');
    Route::post('/inscricaos/{inscricao_id}/enviar-documentos', [InscricaoController::class, 'enviarDocumentos'])->name('inscricao.enviar.documentos');
    Route::get('/inscricaos/{inscricao_id}/ver-documento/{documento_nome}', [InscricaoController::class, 'showDocumento'])->name('inscricao.arquivo');

    Route::get('cotas/{cota}/remanejamento', [CotaController::class, 'remanejamento'])->name('cotas.remanejamento');

    Route::post('cotas/{cota}/remanejamento/atualizar', [CotaController::class, 'remanejamentoUpdate'])->name('cotas.remanejamento.update');
});
