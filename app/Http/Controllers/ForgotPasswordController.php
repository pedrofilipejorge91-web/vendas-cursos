<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Services\NotificacaoService;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'canal' => 'required|in:email,sms',
            'email' => 'required_if:canal,email|nullable|email|exists:users,email',
            'contacto' => 'required_if:canal,sms|nullable|string',
        ]);

        if ($request->canal === 'sms') {
            $contacto = preg_replace('/[^0-9+]/', '', $request->contacto);
            $contactoSemIndicativo = ltrim(str_replace('+244', '', $contacto), '0');

            $user = User::whereHas('pessoa', function ($query) use ($contacto, $contactoSemIndicativo) {
                $query->where('contacto', $contacto)
                    ->orWhere('contacto', 'like', '%'.$contactoSemIndicativo);
            })->first();

            if (! $user) {
                return back()->withErrors(['contacto' => 'Contacto nao encontrado.']);
            }

            $token = Password::broker()->createToken($user);
            $url = route('password.reset', ['token' => $token, 'email' => $user->email]);

            app(NotificacaoService::class)->enviar(
                $user,
                'Recuperacao de senha',
                'Paruana Comercial: redefina a sua senha neste link valido por 60 minutos: '.$url,
                ['sms']
            );

            return back()->with(['status' => 'Link de recuperacao enviado por SMS, se o servico estiver configurado.']);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = Password::broker()->createToken($user);
        $url = route('password.reset', ['token' => $token, 'email' => $user->email]);

        app(NotificacaoService::class)->enviar(
            $user,
            'Recuperacao de senha',
            'Recebemos um pedido para redefinir a senha da sua conta. O link abaixo e valido por 60 minutos.',
            ['email'],
            [
                'intro' => 'Use o botao para criar uma nova senha e voltar a aceder aos seus cursos.',
                'acao_url' => $url,
                'acao_texto' => 'Redefinir senha',
                'rodape' => 'Se nao solicitou esta recuperacao, ignore este email. A sua senha actual continuara a funcionar.',
                'preheader' => 'Link seguro para recuperar o acesso a sua conta.',
            ]
        );

        return back()->with(['status' => 'Enviamos o link de recuperacao para o seu email.']);
    }
}
