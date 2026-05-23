<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoController extends Controller
{
    public function store(Request $request, Curso $curso)
    {
        $request->validate([
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $matricula = Matricula::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->first();

        $estudante = $user->pessoa?->estudante;

        abort_unless($matricula && $matricula->progresso >= 70, 403);
        abort_unless($estudante, 403);

        Avaliacao::updateOrCreate([
            'curso_id' => $curso->id,
            'estudante_id' => $estudante->id,
        ], [
            'nota' => $request->nota,
            'comentario' => $request->comentario,
        ]);

        return back()->with('success', 'Avaliacao enviada com sucesso.');
    }

    public function responder(Request $request, Avaliacao $avaliacao)
    {
        $request->validate([
            'resposta_instrutor' => 'required|string|max:1000',
        ]);

        $avaliacao->load('curso');
        $user = Auth::user();
        $formadorId = $user?->pessoa?->formador?->id;

        abort_unless($user?->tipo === 'admin' || $avaliacao->curso->formador_id === $formadorId, 403);

        $avaliacao->update([
            'resposta_instrutor' => $request->resposta_instrutor,
            'respondido_em' => now(),
        ]);

        return back()->with('success', 'Resposta publicada com sucesso.');
    }
}
