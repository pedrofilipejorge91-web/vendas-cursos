<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pessoa;
use App\Models\Estudante;
use App\Services\NotificacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstudanteController extends Controller
{
    // LISTAR
    public function index()
    {
        $estudantes = Estudante::with('pessoa.user')->get();

        return view('admin.estudante.dashboard', compact('estudantes'));
    }

    // CREATE (opcional se usar modal)
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',

        'primeironome' => 'required',
        'segundonome' => 'required',
        'BI' => 'required',
        'genero' => 'required',
        'nacionalidade' => 'required',
        'data_nascimento' => 'required',
        'rua' => 'required',
        'bairro' => 'required',
        'contacto' => 'required',

        'escola_actual' => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {

        // USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tipo' => 'estudante',
        ]);

        // PESSOA
        $pessoa = Pessoa::create([
            'user_id' => $user->id,
            'primeironome' => $request->primeironome,
            'segundonome' => $request->segundonome,
            'BI' => $request->BI,
            'genero' => $request->genero,
            'nacionalidade' => $request->nacionalidade,
            'data_nascimento' => $request->data_nascimento,
            'rua' => $request->rua,
            'bairro' => $request->bairro,
            'contacto' => $request->contacto,
        ]);

        // ESTUDANTE
        $estudante = Estudante::create([
            'pessoa_id' => $pessoa->id,
            'escola_actual' => $request->escola_actual,
            'status' => 'inativo',
            'data_inscricao' => now(),
        ]);

        DB::commit();

        app(NotificacaoService::class)->enviar(
            $user,
            'Conta de aluno criada',
            'A equipa administrativa criou a sua conta de aluno. A conta ainda precisa de activacao para permitir o acesso.',
            ['email'],
            [
                'linhas' => [
                    'Perfil' => 'Aluno',
                    'Estado da conta' => 'Inactivo',
                ],
                'acao_url' => route('login'),
                'acao_texto' => 'Abrir plataforma',
                'rodape' => 'Use o email cadastrado e a senha definida no registo administrativo para iniciar sessao quando a conta estiver activa.',
            ]
        );

        return redirect()->back()->with('success', 'Estudante criado com sucesso!');

    } catch (\Exception $e) {

        DB::rollback();

        dd($e->getMessage()); // 🔥 MUITO IMPORTANTE PARA VER O ERRO REAL
    }
}

    // SHOW
    public function show($id)
    {
        $estudante = Estudante::with([
    'pessoa.user',
    'cursos'
])->findOrFail($id);

        return view('admin.estudante.show', compact('estudante'));
    }

    // EDIT
    public function edit($id)
    {
        $estudante = Estudante::with('pessoa.user')->findOrFail($id);

        return view('admin.estudante.update', compact('estudante'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $estudante = Estudante::findOrFail($id);

        $request->validate([
            'primeironome' => 'required',
            'segundonome' => 'required',
            'BI' => 'required',
            'genero' => 'required',
            'nacionalidade' => 'required',
            'data_nascimento' => 'required',
            'rua' => 'required',
            'bairro' => 'required',
            'contacto' => 'required',
            'escola_actual' => 'nullable|string',
            'status' => 'required|in:ativo,inativo',
        ]);

        // PESSOA
        $estudante->pessoa->update([
            'primeironome' => $request->primeironome,
            'segundonome' => $request->segundonome,
            'BI' => $request->BI,
            'genero' => $request->genero,
            'nacionalidade' => $request->nacionalidade,
            'data_nascimento' => $request->data_nascimento,
            'rua' => $request->rua,
            'bairro' => $request->bairro,
            'contacto' => $request->contacto,
        ]);

        // ESTUDANTE
        $estudante->update([
            'escola_actual' => $request->escola_actual,
            'status' => $request->status,
        ]);

        return redirect()->back()
            ->with('success', 'Estudante atualizado com sucesso!');
    }

    // DELETE (CASCADE TOTAL)
    public function destroy($id)
    {
        $estudante = Estudante::with('pessoa.user')->findOrFail($id);

        DB::beginTransaction();

        try {

            // delete estudante
            $estudante->delete();

            // delete pessoa
            if ($estudante->pessoa) {
                $estudante->pessoa->delete();
            }

            // delete user
            if ($estudante->pessoa && $estudante->pessoa->user) {
                $estudante->pessoa->user->delete();
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Estudante eliminado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erro ao eliminar: ' . $e->getMessage());
        }
    }

    // BOTÃO STATUS (ATIVAR/DESATIVAR)
  public function mudarStatus($id)
{
    $estudante = Estudante::findOrFail($id);

    // ALTERAR STATUS
    $estudante->status = $estudante->status == 'ativo'
        ? 'inativo'
        : 'ativo';

    $estudante->save();

    $user = $estudante->pessoa?->user;
    $ativo = $estudante->status === 'ativo';

    app(NotificacaoService::class)->enviar(
        $user,
        $ativo ? 'Conta activada' : 'Conta desactivada',
        $ativo
            ? 'A sua conta de aluno foi activada. Ja pode iniciar sessao e aceder aos cursos disponiveis.'
            : 'A sua conta de aluno foi desactivada temporariamente. Se precisar de ajuda, contacte a administracao.',
        ['email', 'sms'],
        [
            'linhas' => [
                'Estado da conta' => $ativo ? 'Activo' : 'Inactivo',
                'Perfil' => 'Aluno',
            ],
            'acao_url' => $ativo ? route('login') : null,
            'acao_texto' => 'Entrar na plataforma',
            'preheader' => $ativo ? 'A sua conta ja esta activa.' : 'O acesso a sua conta foi suspenso.',
        ]
    );

    return redirect()->back()
        ->with('success', 'Status actualizado com sucesso!');
}
}
