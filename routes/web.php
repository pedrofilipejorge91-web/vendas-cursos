<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\controllers\AulaController;
use App\Http\controllers\AdminDashboardController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CursoAcessoController;
use App\Http\controllers\CursoController;
use App\Http\controllers\EstudanteController;
use App\Http\Controllers\FormadorController;
use App\Http\controllers\FormadorDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\controllers\InscricaoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\AdminUserController;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cursos', [HomeController::class, 'catalogo'])->name('home.catalogo');

// Rota para exibir os cursos de uma categoria específica
Route::prefix('home')->name('home.')->group(function () {
    Route::get('/categoria/{id}', [HomeController::class, 'categoria'])->name('categorias');
    Route::get('/detalhe/{id}', [HomeController::class, 'show'])->name('detalhe');
});
// LOGIN
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PASSWORD RESET
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

// CADASTRO DO ESTUDANTE
Route::post('/register/estudante', [AuthController::class, 'registerEstudante'])->name('auth.register.estudante');
// ADMIN
Route::middleware(['auth', 'tipo:admin'])->group(function () {

    // Certificados (admin decide aprovar/rejeitar solicitações)
    Route::get('/admin/certificados/solicitacoes', [\App\Http\Controllers\CertificadoSolicitacaoController::class, 'listarAdmin'])->name('admin.certificados.solicitacoes');
    Route::post('/admin/certificados/solicitacoes/{solicitacao}/decidir', [\App\Http\Controllers\CertificadoSolicitacaoController::class, 'decidirAdmin'])->name('admin.certificados.decidir');

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/admin/relatorios', [RelatorioController::class, 'admin'])->name('admin.relatorios');
    Route::get('/admin/administradores', [AdminUserController::class, 'index'])->name('admin.administradores.index');
    Route::post('/admin/administradores', [AdminUserController::class, 'store'])->name('admin.administradores.store');
    Route::put('/admin/administradores/{administrador}', [AdminUserController::class, 'update'])->name('admin.administradores.update');
    Route::delete('/admin/administradores/{administrador}', [AdminUserController::class, 'destroy'])->name('admin.administradores.destroy');

    Route::get('/admin/formadores', [FormadorController::class, 'index'])->name('formador.index');
    Route::post('/admin/formadores', [FormadorController::class, 'store'])->name('formador.store');
    Route::put('/admin/formadores/{id}', [FormadorController::class, 'update'])->name('formador.update');
    Route::delete('/admin/formadores/{id}', [FormadorController::class, 'destroy'])->name('formador.destroy');

   Route::get('/admin/Categoria/dashboard',[CategoriaController::class, 'index' ])->name('categoria.index');
Route::get('/admin/Categoria/Create',[CategoriaController::class, 'create' ])->name('categoria.create');
Route::post('/admin/Categoria/store',[CategoriaController::class, 'store'])->name('categoria.store');
Route::match(['post','get','put','delete'],'/admin/Categoria/delete/{id}',[CategoriaController::class, 'destroy'])->name('categoria.destroy');
Route::match(['post','get','put','delete'],'/admin/Categoria/update/{id}',[CategoriaController::class, 'update'])->name('categoria.update');
 

    Route::get('/admin/cursos', [CursoController::class, 'index'])->name('curso.index');
    Route::post('/admin/cursos', [CursoController::class, 'store'])->name('curso.store');
    Route::put('/admin/cursos/{id}', [CursoController::class, 'update'])->name('curso.update');
    Route::delete('/admin/cursos/{id}', [CursoController::class, 'destroy'])->name('curso.detroy');
    Route::post('/admin/cursos/{id}/publicar', [CursoController::class, 'publicar'])->name('curso.publicar');
    Route::post('/admin/cursos/{id}/rejeitar', [CursoController::class, 'rejeitar'])->name('curso.rejeitar');

    Route::get('/admin/aulas', [AulaController::class, 'index'])->name('aula.index');
    Route::post('/admin/aulas', [AulaController::class, 'store'])->name('aula.store');
    Route::put('/admin/aulas/{id}', [AulaController::class, 'update'])->name('aulas.update');
    Route::match(['post','get','put','delete'],'/admin/aulas/{id}', [AulaController::class, 'destroy'])->name('aulas.destroy');

    
    Route::get('/admin/estudante', [EstudanteController::class, 'index'])->name('estudante.index');
    Route::post('/admin/estudante', [EstudanteController::class, 'store'])->name('estudante.store');
    Route::put('/admin/estudante/{id}', [EstudanteController::class, 'update'])->name('estudante.update');
    Route::delete('/admin/estudante/{id}', [EstudanteController::class, 'destroy'])->name('estudante.destroy');
    Route::put('/estudante/status/{id}',[EstudanteController::class, 'mudarStatus'])->name('estudante.status');

});
////////////////////////////////////////////////////////////////////////////////
// FORMADOR
Route::middleware(['auth', 'tipo:formador'])->group(function () {



    Route::get('/formador/dashboard', function () {
        return view('formador.dashboard');
    })->name('formador.dashboard');
    Route::get('/formador/dashboard', [FormadorDashboardController::class, 'index'])->name('formador.dashboard');
    Route::get('/formador/relatorios', [RelatorioController::class, 'formador'])->name('formador.relatorios');
    Route::get('/formador/cursos', [CursoController::class, 'index'])->name('formador.cursos');
    Route::post('/formador/cursos', [CursoController::class, 'store'])->name('formador.cursos.store');
    Route::get('/formador/cursos/{id}/edit', [CursoController::class, 'edit'])->name('formador.cursos.edit');
    Route::put('/formador/cursos/{id}', [CursoController::class, 'update'])->name('formador.cursos.update');
    Route::delete('/formador/cursos/{id}', [CursoController::class, 'destroy'])->name('formador.cursos.destroy');
    Route::get('/formador/aulas', [AulaController::class, 'index'])->name('formador.aulas');
    Route::post('/formador/aulas', [AulaController::class, 'store'])->name('formador.aulas.store');

    // admin decide (aprovar/rejeitar) para certificados

    Route::put('/formador/aulas/{id}', [AulaController::class, 'update'])->name('formador.aulas.update');
    Route::delete('/formador/aulas/{id}', [AulaController::class, 'destroy'])->name('formador.aulas.destroy');

    // Certificados / questionário
    Route::get('/formador/certificados/solicitacoes', [\App\Http\Controllers\CertificadoSolicitacaoController::class, 'listarInstrutor'])->name('formador.certificados.solicitacoes');
    Route::get('/formador/certificados/{solicitacao}/questionario', [\App\Http\Controllers\CertificadoSolicitacaoController::class, 'mostrarQuestionario'])->name('formador.certificados.questionario');
    Route::post('/formador/certificados/{solicitacao}/questionario', [\App\Http\Controllers\CertificadoSolicitacaoController::class, 'criarQuestionario'])->name('formador.certificados.questionario.criar');
    Route::post('/formador/certificados/{solicitacao}/nota', [\App\Http\Controllers\CertificadoSolicitacaoController::class, 'avaliadorEnviarNota'])->name('formador.certificados.nota');

});
/////////////////////////////////////////////////////////////////////////////////////////////
Route::middleware(['auth', 'tipo:estudante'])->group(function () {
Route::get('/estudante/dashboard', [CursoAcessoController::class, 'dashboard'])->name('dashboard');

    // Certificados / questionário
    Route::get('/meus-cursos/{matricula}/certificado/questionario', [\App\Http\Controllers\CertificadoSolicitacaoController::class, 'mostrarQuestionario'])->name('estudante.certificados.questionario');
    Route::post('/certificados/questionarios/{questionario}/responder', [\App\Http\Controllers\CertificadoSolicitacaoController::class, 'responderQuestionario'])->name('estudante.certificados.responder_questionario');
    Route::get('/estudante/perfil', [CursoAcessoController::class, 'perfil'])->name('estudante.perfil');
    Route::put('/estudante/perfil', [CursoAcessoController::class, 'actualizarPerfil'])->name('estudante.perfil.update');
   Route::post('/curso/inscrever/{id}',[CursoController::class, 'inscrever'])->name('curso.inscrever');
    Route::get('/meus-cursos', [CursoAcessoController::class, 'meusCursos'])->name('estudante.cursos');
    Route::get('/meus-cursos/{matricula}', [CursoAcessoController::class, 'show'])->name('estudante.curso');
    Route::get('/meus-cursos/{matricula}/aulas/{aula}', [CursoAcessoController::class, 'aula'])->name('estudante.aula');
    Route::get('/meus-cursos/{matricula}/aulas/{aula}/download', [CursoAcessoController::class, 'downloadAula'])->name('estudante.aula.download');
    Route::post('/meus-cursos/{matricula}/aulas/{aula}/concluir', [CursoAcessoController::class, 'concluirAula'])->name('estudante.aula.concluir');
    Route::get('/meus-cursos/{matricula}/certificado', [CursoAcessoController::class, 'certificado'])->name('estudante.certificado');
    Route::get('/meus-cursos/{matricula}/certificado/download', [CursoAcessoController::class, 'certificadoPdf'])->name('estudante.certificado.download');

});

///////////////////////////////////////////////////////////
Route::get('/certificados/verificar/{codigo}', [CursoAcessoController::class, 'verificarCertificado'])->name('certificado.verificar');

// Rotas de Carrinho e Pagamento (Acessíveis a usuários logados)
Route::middleware('auth')->group(function () {
Route::get('/home/carrinho', [CarrinhoController::class, 'index'])->name('home.carrinho');
Route::post('/carrinho/remove', [CarrinhoController::class, 'remove'])->name('carrinho.remove');
Route::post('/carrinho/add', [CarrinhoController::class, 'add'])->name('carrinho.add');
Route::post('/carrinho/comprar-agora', [CarrinhoController::class, 'buyNow'])->name('carrinho.buy-now');
Route::post('/carrinho/limpar', [CarrinhoController::class, 'clear'])->name('carrinho.clear');

Route::get('/pagamento', [PagamentoController::class, 'index'])
    ->name('pagamento');

Route::get('/pagamento/comprovante/{pedido}', [PagamentoController::class, 'comprovante'])->name('pagamento.comprovante');
Route::post('/pagamento/{pedido}/confirmar', [PagamentoController::class, 'confirmar'])->name('pagamento.confirmar');

// Processar pagamento
Route::post('/home/pagamento', [PagamentoController::class, 'processar'])
    ->name('pagamento.processar');
Route::post('/cursos/{curso}/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacoes.store');
Route::post('/avaliacoes/{avaliacao}/responder', [AvaliacaoController::class, 'responder'])->name('avaliacoes.responder');
});
////////////////////////////////////////////////////////////////////////////////////////
