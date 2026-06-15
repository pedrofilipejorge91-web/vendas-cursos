<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pessoa;
use App\Models\Estudante;
use App\Services\NotificacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EstudanteController extends Controller
{
    // LISTAR
    public function index()
    {
        $estudantes = Estudante::with('pessoa.user')->get();

        return view('admin.estudante.dashboard', compact('estudantes'));
    }

    // CREATE (criação manual pelo admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',

            'primeironome' => 'required',
            'segundonome' => 'required',
            'BI' => 'required|unique:pessoas,BI',
            'genero' => 'required',
            'nacionalidade' => 'required',
            'data_nascimento' => 'required',
            'rua' => 'required',
            'bairro' => 'required',
            'contacto' => 'required|unique:pessoas,contacto',

            'escola_actual' => 'nullable|string',
        ], [
            'email.unique' => 'O email inserido ja existe.',
            'BI.unique' => 'O B.I inserido ja existe.',
            'contacto.unique' => 'O contacto inserido ja existe.',
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

            // ESTUDANTE - ✅ Agora criado como ATIVO automaticamente
            $estudante = Estudante::create([
                'pessoa_id' => $pessoa->id,
                'escola_actual' => $request->escola_actual,
                'status' => 'ativo', // ✅ Alterado de 'inativo' para 'ativo'
                'data_inscricao' => now(),
            ]);

            DB::commit();

            // ✅ Notificação atualizada - conta já está ativa
            app(NotificacaoService::class)->enviar(
                $user,
                'Conta de aluno criada com sucesso',
                'A equipa administrativa criou a sua conta de aluno. A conta ja esta ativa e pronta para uso. Podes fazer login e comecar a explorar os nossos cursos!',
                ['email'],
                [
                    'intro' => 'Ola ' . $request->primeironome . ',',
                    'linhas' => [
                        'Perfil' => 'Aluno',
                        'Estado da conta' => 'Ativo',
                        'Proximo passo' => 'Faz login e comeca a aprender!',
                    ],
                    'acao_url' => route('login'),
                    'acao_texto' => 'Fazer Login',
                    'rodape' => 'As credenciais de acesso foram definidas pela administracao. Em caso de duvida, contacta o suporte.',
                    'preheader' => 'A tua conta esta ativa e pronta a usar!',
                ]
            );

            return redirect()->back()->with('success', 'Estudante criado com sucesso! A conta ja esta ativa.');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar estudante: ' . $e->getMessage());
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
            'BI' => [
                'required',
                Rule::unique('pessoas', 'BI')->ignore($estudante->pessoa?->id),
            ],
            'genero' => 'required',
            'nacionalidade' => 'required',
            'data_nascimento' => 'required',
            'rua' => 'required',
            'bairro' => 'required',
            'contacto' => [
                'required',
                Rule::unique('pessoas', 'contacto')->ignore($estudante->pessoa?->id),
            ],
            'escola_actual' => 'nullable|string',
            'status' => 'required|in:ativo,inativo',
        ], [
            'BI.unique' => 'O B.I inserido ja existe.',
            'contacto.unique' => 'O contacto inserido ja existe.',
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

    // BOTÃO STATUS SUSPENDER/REATIVAR conta em casos excecionais
    public function mudarStatus($id)
    {
        $estudante = Estudante::findOrFail($id);

        // ALTERAR DE STATUS
        $estudante->status = $estudante->status == 'ativo'
            ? 'inativo'
            : 'ativo';

        $estudante->save();

        $user = $estudante->pessoa?->user;
        $ativo = $estudante->status === 'ativo';

        // FLUXOS DE Mensagens 
        app(NotificacaoService::class)->enviar(
            $user,
            $ativo ? 'Conta reativada' : 'Conta suspensa temporariamente',
            $ativo
                ? 'A sua conta de aluno foi reativada. Ja pode iniciar sessao e aceder novamente aos cursos disponiveis.'
                : 'A sua conta de aluno foi suspensa temporariamente pela administracao. Se precisar de ajuda, contacte o suporte.',
            ['email', 'sms'],
            [
                'linhas' => [
                    'Estado da conta' => $ativo ? 'Ativo' : 'Suspenso',
                    'Perfil' => 'Aluno',
                ],
                'acao_url' => $ativo ? route('login') : null,
                'acao_texto' => $ativo ? 'Entrar na plataforma' : null,
                'preheader' => $ativo ? 'A sua conta foi reativada.' : 'O acesso a sua conta foi suspenso.',
            ]
        );

        return redirect()->back()
            ->with('success', 'Status actualizado com sucesso!');
    }
}