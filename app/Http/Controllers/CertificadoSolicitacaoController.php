<?php

namespace App\Http\Controllers;

use App\Models\CertificadoSolicitacao;
use App\Models\CertificadoQuestionario;
use App\Models\CertificadoResposta;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\ProgressoAula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\NotificacaoService;
use Illuminate\Support\Arr;

class CertificadoSolicitacaoController extends Controller
{
    public function listarInstrutor()
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'formador', 403);

        $formadorId = $user->pessoa?->formador?->id;
        abort_unless($formadorId, 403);

        $solicitacoes = CertificadoSolicitacao::with(['matricula.curso', 'matricula.user', 'curso', 'instrutor', 'questionario'])
            ->where('instrutor_id', $formadorId)
            ->orderByDesc('created_at')
            ->get();

        return view('formador.certificados.solicitacoes', compact('solicitacoes'));
    }

    public function mostrarQuestionario(CertificadoSolicitacao $solicitacao)
    {
        $user = Auth::user();
        abort_unless($user, 403);

        // Permissões:
        // - estudante vê apenas a própria solicitação
        // - formador pode acessar somente solicitações do curso dele (instrutor_id)
        if ((string) $user->tipo === 'estudante') {
            abort_unless(
                (int) $solicitacao->estudante_id === (int) $user->pessoa?->estudante?->id,
                403
            );
        } elseif ((string) $user->tipo === 'formador') {
            $formadorId = $user->pessoa?->formador?->id;
            abort_unless(
                $formadorId && (int) $solicitacao->instrutor_id === (int) $formadorId,
                403
            );
        } else {
            abort_unless(false, 403);
        }

        $questionario = $solicitacao->questionario()->firstOrCreate([
            'matricula_id' => $solicitacao->matricula_id,
            'curso_id' => $solicitacao->curso_id,
        ], [
            'perguntas' => null,
            'criado_em' => now(),
        ]);

        return view('estudante.certificados.questionario', [
            'solicitacao' => $solicitacao,
            'questionario' => $questionario,
        ]);
    }

    public function responderQuestionario(Request $request, CertificadoQuestionario $questionario)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'estudante', 403);

        $estudanteId = $user->pessoa?->estudante?->id;
        abort_unless($estudanteId, 403);

        $request->validate([
            'respostas' => 'required|array|max:200',
        ]);

        CertificadoResposta::updateOrCreate(
            [
                'questionario_id' => $questionario->id,
                'estudante_id' => $estudanteId,
            ],
            [
                'respostas' => json_encode($request->respostas, JSON_UNESCAPED_UNICODE),
                'enviado_em' => now(),
            ]
        );

        return back()->with('success', 'Respostas enviadas com sucesso.');
    }

    public function criarQuestionario(Request $request, CertificadoSolicitacao $solicitacao)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'formador', 403);

        $formadorId = $user->pessoa?->formador?->id;
        abort_unless($formadorId && (int) $solicitacao->instrutor_id === (int) $formadorId, 403);

        $request->validate([
            'perguntas_texto' => 'required|string|max:5000',
        ]);

        // perguntas simples como texto (separadas por linhas)
        $questionario = CertificadoQuestionario::updateOrCreate(
            [
                'matricula_id' => $solicitacao->matricula_id,
                'curso_id' => $solicitacao->curso_id,
                'solicitacao_id' => $solicitacao->id,
            ],
            [
                'perguntas' => $request->perguntas_texto,
                'criado_em' => now(),
            ]
        );

        return back()->with('success', 'Questionário publicado para o estudante.');
    }

    public function avaliadorEnviarNota(Request $request, CertificadoSolicitacao $solicitacao)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'formador', 403);

        $formadorId = $user->pessoa?->formador?->id;
        abort_unless($formadorId && (int) $solicitacao->instrutor_id === (int) $formadorId, 403);

        $request->validate([
            'nota_curso' => 'required|numeric|min:1|max:5',
            'observacoes' => 'nullable|string|max:2000',
        ]);

        $solicitacao->update([
            'nota_curso' => $request->nota_curso,
            'observacoes_admin' => $request->observacoes,
            'enviado_em' => $solicitacao->enviado_em ?? now(),
            'status' => 'pendente',
        ]);

        // Notificar o ADMIN para aprovar/rejeitar
        // (Regra: o formador notifica o admin após o aluno responder e o formador atribuir nota)
        $admins = \App\Models\User::where('tipo', 'admin')->get();

        foreach ($admins as $adminUser) {
            try {
                app(NotificacaoService::class)->enviar(
                    $adminUser,
                    'Nova solicitação de certificado',
                    'O instrutor enviou a nota do curso. Verifique e aprove/rejeite a solicitação.',
                    ['email', 'sms', 'whatsapp']
                );
            } catch (\Throwable $e) {
                // Não quebrar o fluxo do formador se a notificação falhar.
            }
        }


        return back()->with('success', 'Nota enviada e solicitação encaminhada ao admin.');
    }

    public function listarAdmin()
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'admin', 403);

        $solicitacoes = CertificadoSolicitacao::with(['matricula.curso', 'matricula.user', 'instrutor'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.certificados.solicitacoes', compact('solicitacoes'));
    }

    public function decidirAdmin(Request $request, CertificadoSolicitacao $solicitacao)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'admin', 403);

        $request->validate([
            'acao' => 'required|in:aprovar,rejeitar',
            'observacoes' => 'nullable|string|max:2000',
        ]);

        $status = $request->acao === 'aprovar' ? 'aprovado' : 'rejeitado';

        $solicitacao->update([
            'status' => $status,
            'decidido_em' => now(),
            'observacoes_admin' => $request->observacoes,
        ]);

        // se aprovado, ainda não geramos PDF automático; o download vai liberar.
        return back()->with('success', $status === 'aprovado' ? 'Solicitação aprovada.' : 'Solicitação rejeitada.');
    }
}

