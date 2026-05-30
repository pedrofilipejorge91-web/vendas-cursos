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
    // =========================
    // FORM REGISTER
    // =========================
    public function showRegister()
    {
        return view('auth.register');
    }

    // =========================
    // REGISTRO DO ESTUDANTE
    // =========================
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

            // 1. USER
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tipo' => 'estudante',
                'termos_aceites_em' => now(),
                'privacidade_aceite_em' => now(),
            ]);

            // 2. PESSOA
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

            // 3. ESTUDANTE (INATIVO POR PADRÃO)
            Estudante::create([
                'pessoa_id' => $pessoa->id,
                'escola_actual' => $request->escola_actual,
                'status' => 'inativo',
                'data_inscricao' => now(),
            ]);

            DB::commit();

            $notificador = app(NotificacaoService::class);
            $notificador->enviar(
                $user,
                'Conta criada com sucesso',
                'A sua conta de aluno foi criada e esta em analise. Assim que o administrador activar a conta, avisaremos por email.',
                ['email'],
                [
                    'intro' => 'Bem-vindo a Paruana Comercial.',
                    'linhas' => [
                        'Estado da conta' => 'Aguardando activacao',
                        'Perfil' => 'Aluno',
                    ],
                    'acao_url' => route('login'),
                    'acao_texto' => 'Ir para login',
                    'rodape' => 'Enquanto aguarda a activacao, pode guardar este email como confirmacao do seu cadastro.',
                    'preheader' => 'Cadastro recebido e aguardando activacao administrativa.',
                ]
            );

            User::where('tipo', 'admin')->each(function (User $admin) use ($notificador, $user) {
                $notificador->enviar(
                    $admin,
                    'Nova conta de aluno aguardando activacao',
                    'O aluno '.$user->name.' concluiu o cadastro e precisa de activacao para aceder a plataforma.',
                    ['email'],
                    [
                        'linhas' => [
                            'Aluno' => $user->name,
                            'Email' => $user->email,
                        ],
                        'acao_url' => route('estudante.index'),
                        'acao_texto' => 'Rever alunos',
                        'preheader' => 'Existe um novo aluno aguardando activacao.',
                    ]
                );
            });

            return redirect()->route('login')
                ->with('success', 'Conta criada! Aguarde aprovação do administrador.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    // =========================
    // LOGIN FORM
    // =========================
    public function loginForm()
    {
        return view('auth.login');
    }

    // =========================
    // LOGIN
    // =========================
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

        // 🔒 BLOQUEIO ESTUDANTE INATIVO
        if ($user->tipo === 'estudante') {

            $estudante = $user->pessoa?->estudante;

            if (!$estudante || $estudante->status !== 'ativo') {
                return back()->with('error', 'Conta ainda não ativada pelo administrador.');
            }
        }

        // LOGIN
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

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

    // =========================
    // LOGOUT
    // =========================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
