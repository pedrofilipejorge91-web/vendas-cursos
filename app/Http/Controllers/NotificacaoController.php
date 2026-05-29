<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller
{
    public function index()
    {
        $notificacoes = Notificacao::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        $view = match (Auth::user()?->tipo) {
            'admin' => 'admin.notificacoes.index',
            'estudante' => 'estudante.notificacoes',
            'formador' => 'formador.notificacoes',
            default => 'admin.notificacoes.index',
        };

        return view($view, compact('notificacoes'));
    }

    public function marcarLidas()
    {
        Notificacao::where('user_id', Auth::id())
            ->whereNull('lida_em')
            ->update(['lida_em' => now()]);

        return back()->with('success', 'Notificações marcadas como lidas.');
    }

    public function destroy(Notificacao $notificacao)
    {
        abort_unless($notificacao->user_id === Auth::id(), 403);

        $notificacao->delete();

        return back()->with('success', 'Notificação apagada.');
    }
}
