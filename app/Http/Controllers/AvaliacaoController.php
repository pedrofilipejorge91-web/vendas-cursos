<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Curso;
use App\Models\Matricula;
use App\Services\NotificacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoController extends Controller
{
    public function store(Request $request, Curso $curso)
    {
        $request->validate([
            'nota' => 'required|integer|min:0|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $matricula = Matricula::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->first();

        $estudante = $user->pessoa?->estudante;

        abort_unless($matricula && $matricula->progresso >= 50, 403);
        abort_unless($estudante, 403);

        $avaliacao = Avaliacao::updateOrCreate([
            'curso_id' => $curso->id,
            'estudante_id' => $estudante->id,
        ], [
            'nota' => $request->nota,
            'comentario' => $request->comentario,
        ]);

        $curso->load('formador.pessoa.user');

        app(NotificacaoService::class)->enviar(
            $curso->formador?->pessoa?->user,
            'Novo comentario de aluno',
            'O aluno '.$user->name.' avaliou o curso '.$curso->titulo.' com '.$avaliacao->nota.'/5 e deixou um comentario.',
            ['email'],
            [
                'linhas' => [
                    'Curso' => $curso->titulo,
                    'Aluno' => $user->name,
                    'Avaliacao' => $avaliacao->nota.'/5',
                ],
                'acao_url' => route('formador.comentarios'),
                'acao_texto' => 'Responder comentario',
                'preheader' => 'Um aluno comentou num dos seus cursos.',
            ]
        );

        return back()->with('success', 'Avaliação enviada com sucesso.');
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

        $avaliacao->load('curso', 'estudante.pessoa.user');

        app(NotificacaoService::class)->enviar(
            $avaliacao->estudante?->pessoa?->user,
            'O formador respondeu ao seu comentario',
            'O formador respondeu ao comentario que deixou no curso '.$avaliacao->curso?->titulo.'.',
            ['email'],
            [
                'linhas' => [
                    'Curso' => $avaliacao->curso?->titulo ?? '-',
                    'A sua avaliacao' => $avaliacao->nota.'/5',
                ],
                'acao_url' => route('home.detalhe', $avaliacao->curso_id),
                'acao_texto' => 'Ver resposta',
                'preheader' => 'Tem uma resposta nova ao seu comentario.',
            ]
        );

        return back()->with('success', 'Resposta publicada com sucesso.');
    }
}
