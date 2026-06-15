<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Estudante;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\NotificacaoService;

class AuthController extends Controller
{
    
    // EXIBIR O FORMULARIO REGISTER
    
    public function showRegister()
    {
        return view('auth.register');
    }

    
    // REGISTRO DO ESTUDANTE
    
    public function registerEstudante(Request $request)
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
            'consentimento_dados' => 'accepted',
        ]);

        DB::beginTransaction();

        try {
            // 1. Criar o utilizador
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tipo' => 'estudante',
                'termos_aceites_em' => now(),
                'privacidade_aceite_em' => now(),
            ]);

            // 2. Criar os dados pessoais
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

            // 3. Criar o registo de estudante 
            Estudante::create([
                'pessoa_id' => $pessoa->id,
                'escola_actual' => $request->escola_actual,
                'status' => 'ativo',
                'data_inscricao' => now(),
            ]);

            DB::commit();

            // 4. Enviar notificação de boas-vindas ao aluno
            $notificador = app(NotificacaoService::class);
            $notificador->enviar(
                $user,
                'Bem-vindo à Paruana Comercial!',
                'A tua conta foi criada com sucesso e já está ativa. Podes começar a explorar os nossos cursos e fazer a tua inscrição agora mesmo!',
                ['email'],
                [
                    'intro' => 'Olá ' . $request->primeironome . ',',
                    'linhas' => [
                        'Estado da conta' => 'Ativa',
                        'Perfil' => 'Aluno',
                        'Próximo passo' => 'Escolhe um curso e começa a aprender!',
                    ],
                    'acao_url' => route('login'),
                    'acao_texto' => 'Fazer Login',
                    'rodape' => 'Paruana Comercial - Excelência no ensino e rigor nos resultados.',
                    'preheader' => 'A tua conta está ativa e pronta a usar!',
                ]
            );

            // 5. Notificar administradores sobre novo aluno (opcional - apenas informativo)
            User::where('tipo', 'admin')->each(function (User $admin) use ($notificador, $user, $request) {
                $notificador->enviar(
                    $admin,
                    'Novo aluno registado na plataforma',
                    'Um novo aluno acabou de se registar e a conta já foi ativada automaticamente.',
                    ['email'],
                    [
                        'linhas' => [
                            'Aluno' => $request->primeironome . ' ' . $request->segundonome,
                            'Email' => $user->email,
                            'Contacto' => $request->contacto,
                            'Escola' => $request->escola_actual ?? 'Não informado',
                        ],
                        'acao_url' => route('estudante.index'),
                        'acao_texto' => 'Ver Alunos',
                        'preheader' => 'Novo aluno ativo na plataforma.',
                    ]
                );
            });

            return redirect()->route('login')
                ->with('success', 'Inscrição realizada com sucesso! A tua conta já está ativa. Podes fazer login e começar a usar a plataforma.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erro ao criar conta: ' . $e->getMessage());
        }
    }

    
    // LOGIN FORMULARIO
    
    public function loginForm()
    {
        return view('auth.login');
    }

    
    // PROCESSO DE LOGIN
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Credenciais inválidas']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            //REDIRECIONAR COM BASE NO TIPO DE UTILIZADOR

            return match ($user->tipo) {
                'admin' => redirect()->route('admin.dashboard'),
                'formador' => redirect()->route('formador.dashboard'),
                'estudante' => redirect()->route('dashboard'),
                default => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas'
        ]);
    }

    
    // LOGOUT OU SAIR
    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}