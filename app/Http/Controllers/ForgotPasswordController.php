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
                'Use este link para redefinir a sua senha: '.$url,
                ['sms']
            );

            return back()->with(['status' => 'Link de recuperacao enviado por SMS, se o servico estiver configurado.']);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => trans($status)])
            : back()->withErrors(['email' => trans($status)]);
    }
}
