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

        return view('notificacoes.index', compact('notificacoes'));
    }

    public function marcarLidas()
    {
        Notificacao::where('user_id', Auth::id())
            ->whereNull('lida_em')
            ->update(['lida_em' => now()]);

        return back()->with('success', 'Notificacoes marcadas como lidas.');
    }
}
