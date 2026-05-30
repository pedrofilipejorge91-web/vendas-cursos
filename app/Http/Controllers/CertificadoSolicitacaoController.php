<?php

namespace App\Http\Controllers;

use App\Models\CertificadoQuestionario;
use App\Models\CertificadoResposta;
use App\Models\CertificadoSolicitacao;
use App\Models\Matricula;
use App\Models\User;
use App\Services\NotificacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificadoSolicitacaoController extends Controller
{
    public function listarInstrutor()
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'formador', 403);

        $formadorId = $user->pessoa?->formador?->id;
        abort_unless($formadorId, 403);

        $solicitacoes = CertificadoSolicitacao::with([
            'matricula.curso',
            'matricula.user',
            'curso',
            'questionario.respostas.estudante.pessoa.user',
        ])
            ->where('instrutor_id', $formadorId)
            ->orderByDesc('created_at')
            ->get();

        return view('formador.certificados.solicitacoes', compact('solicitacoes'));
    }

    public function mostrarQuestionario(CertificadoSolicitacao $solicitacao)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'formador', 403);

        $formadorId = $user->pessoa?->formador?->id;
        abort_unless($formadorId && (int) $solicitacao->instrutor_id === (int) $formadorId, 403);

        $solicitacao->load(['matricula.user', 'curso', 'questionario.respostas.estudante.pessoa.user']);
        $questionario = $solicitacao->questionario()->first();
        $resposta = $questionario?->respostas()->with('estudante.pessoa.user')->latest('enviado_em')->first();

        return view('formador.certificados.questionario', compact('solicitacao', 'questionario', 'resposta'));
    }

    public function mostrarQuestionarioAluno(Matricula $matricula)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'estudante', 403);
        abort_unless((int) $matricula->user_id === (int) $user->id, 403);
        abort_unless((float) $matricula->progresso >= 100, 403);

        $solicitacao = $this->garantirSolicitacao($matricula);
        $questionario = $solicitacao->questionario()->with('respostas')->first();
        $resposta = $questionario?->respostas()
            ->where('estudante_id', $user->pessoa?->estudante?->id)
            ->first();

        return view('estudante.certificados.questionario', compact('solicitacao', 'questionario', 'resposta'));
    }

    public function responderQuestionario(Request $request, CertificadoQuestionario $questionario)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'estudante', 403);

        $estudanteId = $user->pessoa?->estudante?->id;
        abort_unless($estudanteId, 403);

        $solicitacao = $questionario->solicitacao()->with('instrutor.pessoa.user', 'curso')->firstOrFail();
        abort_unless((int) $solicitacao->estudante_id === (int) $estudanteId, 403);
        abort_if($solicitacao->status === CertificadoSolicitacao::STATUS_APROVADO, 403);

        $perguntas = $this->perguntas($questionario);
        abort_unless(count($perguntas) > 0, 422, 'O formador ainda nao publicou a prova.');

        $request->validate([
            'respostas' => 'required|array|size:'.count($perguntas),
            'respostas.*' => 'required|string|max:5000',
        ]);

        CertificadoResposta::updateOrCreate(
            [
                'questionario_id' => $questionario->id,
                'estudante_id' => $estudanteId,
            ],
            [
                'respostas' => json_encode(array_values($request->respostas), JSON_UNESCAPED_UNICODE),
                'enviado_em' => now(),
            ]
        );

        $solicitacao->update([
            'status' => CertificadoSolicitacao::STATUS_AGUARDANDO_CORRECAO,
        ]);

        $this->notificar(
            $solicitacao->instrutor?->pessoa?->user,
            'Questionario respondido',
            'O aluno respondeu a prova do curso '.$solicitacao->curso?->titulo.'. Faca a correcao e atribua uma nota.',
            [
                'linhas' => [
                    'Curso' => $solicitacao->curso?->titulo ?? '-',
                    'Estado' => 'Aguardando correcao',
                ],
                'acao_url' => route('formador.certificados.questionario', $solicitacao),
                'acao_texto' => 'Corrigir prova',
                'preheader' => 'Uma prova de certificado foi respondida.',
            ]
        );

        return back()->with('success', 'Respostas enviadas ao formador com sucesso.');
    }

    public function criarQuestionario(Request $request, CertificadoSolicitacao $solicitacao)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'formador', 403);

        $formadorId = $user->pessoa?->formador?->id;
        abort_unless($formadorId && (int) $solicitacao->instrutor_id === (int) $formadorId, 403);
        abort_if($solicitacao->status === CertificadoSolicitacao::STATUS_APROVADO, 403);

        $validated = $request->validate([
            'perguntas' => 'required|array|min:1|max:50',
            'perguntas.*' => 'required|string|max:1000',
        ]);

        $perguntas = collect($validated['perguntas'])
            ->map(fn ($pergunta) => trim((string) $pergunta))
            ->filter()
            ->values();

        abort_unless($perguntas->isNotEmpty(), 422, 'Informe pelo menos uma pergunta.');

        $questionario = CertificadoQuestionario::updateOrCreate(
            [
                'matricula_id' => $solicitacao->matricula_id,
            ],
            [
                'curso_id' => $solicitacao->curso_id,
                'solicitacao_id' => $solicitacao->id,
                'perguntas' => json_encode($perguntas->all(), JSON_UNESCAPED_UNICODE),
                'criado_em' => now(),
                'fechado_em' => null,
            ]
        );

        $solicitacao->update([
            'status' => CertificadoSolicitacao::STATUS_AGUARDANDO_RESPOSTA,
            'nota_curso' => null,
            'enviado_em' => null,
            'decidido_em' => null,
        ]);

        $questionario->respostas()->delete();

        $this->notificar(
            $solicitacao->matricula?->user,
            'Prova de certificado disponivel',
            'O formador publicou a prova do curso '.$solicitacao->curso?->titulo.'. Responda para continuar o processo do certificado.',
            [
                'linhas' => [
                    'Curso' => $solicitacao->curso?->titulo ?? '-',
                    'Estado' => 'Aguardando resposta do aluno',
                ],
                'acao_url' => route('estudante.certificados.questionario', $solicitacao->matricula_id),
                'acao_texto' => 'Responder prova',
                'preheader' => 'A prova para emissao do certificado esta disponivel.',
            ]
        );

        return back()->with('success', 'Questionario publicado para o aluno.');
    }

    public function avaliadorEnviarNota(Request $request, CertificadoSolicitacao $solicitacao)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'formador', 403);

        $formadorId = $user->pessoa?->formador?->id;
        abort_unless($formadorId && (int) $solicitacao->instrutor_id === (int) $formadorId, 403);

        $questionario = $solicitacao->questionario()->with('respostas')->first();
        abort_unless($questionario && $questionario->respostas->isNotEmpty(), 422, 'O aluno ainda nao respondeu a prova.');

        $request->validate([
            'nota_curso' => 'required|numeric|min:0|max:20',
            'observacoes' => 'nullable|string|max:2000',
        ]);

        $solicitacao->update([
            'nota_curso' => $request->nota_curso,
            'observacoes_admin' => $request->observacoes,
            'enviado_em' => now(),
            'status' => CertificadoSolicitacao::STATUS_AGUARDANDO_ADMIN,
        ]);

        $questionario->update([
            'fechado_em' => now(),
        ]);

        User::where('tipo', 'admin')->each(function (User $adminUser) use ($solicitacao) {
            $this->notificar(
                $adminUser,
                'Certificado aguardando aprovacao',
                'O formador corrigiu a prova e atribuiu nota ao aluno. Aprove ou rejeite a liberacao do certificado.',
                [
                    'linhas' => [
                        'Curso' => $solicitacao->curso?->titulo ?? '-',
                        'Nota' => $solicitacao->nota_curso,
                        'Estado' => 'Aguardando aprovacao administrativa',
                    ],
                    'acao_url' => route('admin.certificados.solicitacoes'),
                    'acao_texto' => 'Rever certificado',
                    'preheader' => 'Certificado pronto para decisao administrativa.',
                ]
            );
        });

        return back()->with('success', 'Nota enviada e solicitacao encaminhada ao admin.');
    }

    public function listarAdmin()
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'admin', 403);

        $solicitacoes = CertificadoSolicitacao::with(['matricula.curso', 'matricula.user', 'instrutor.pessoa.user'])
            ->whereIn('status', [
                CertificadoSolicitacao::STATUS_AGUARDANDO_ADMIN,
                CertificadoSolicitacao::STATUS_APROVADO,
                CertificadoSolicitacao::STATUS_REJEITADO,
                'pendente',
            ])
            ->whereNotNull('nota_curso')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.certificados.solicitacoes', compact('solicitacoes'));
    }

    public function decidirAdmin(Request $request, CertificadoSolicitacao $solicitacao)
    {
        $user = Auth::user();
        abort_unless($user && $user->tipo === 'admin', 403);
        abort_unless($solicitacao->nota_curso !== null, 422, 'O formador ainda nao enviou a nota.');

        $request->validate([
            'acao' => 'required|in:aprovar,rejeitar',
            'observacoes' => 'nullable|string|max:2000',
        ]);

        $status = $request->acao === 'aprovar'
            ? CertificadoSolicitacao::STATUS_APROVADO
            : CertificadoSolicitacao::STATUS_REJEITADO;

        $solicitacao->update([
            'status' => $status,
            'decidido_em' => now(),
            'observacoes_admin' => $request->observacoes,
        ]);

        $this->notificar(
            $solicitacao->matricula?->user,
            $status === CertificadoSolicitacao::STATUS_APROVADO ? 'Certificado liberado' : 'Certificado rejeitado',
            $status === CertificadoSolicitacao::STATUS_APROVADO
                ? 'O admin aprovou o seu certificado. Ja pode baixar o PDF.'
                : 'O admin rejeitou a liberacao do certificado. Consulte as observacoes e fale com o formador.',
            [
                'linhas' => [
                    'Curso' => $solicitacao->curso?->titulo ?? '-',
                    'Estado' => $status === CertificadoSolicitacao::STATUS_APROVADO ? 'Aprovado' : 'Rejeitado',
                ],
                'acao_url' => $status === CertificadoSolicitacao::STATUS_APROVADO
                    ? route('estudante.certificado', $solicitacao->matricula_id)
                    : route('estudante.certificados.questionario', $solicitacao->matricula_id),
                'acao_texto' => $status === CertificadoSolicitacao::STATUS_APROVADO ? 'Baixar certificado' : 'Ver detalhes',
                'preheader' => $status === CertificadoSolicitacao::STATUS_APROVADO
                    ? 'O seu certificado foi aprovado.'
                    : 'A sua solicitacao de certificado foi rejeitada.',
            ]
        );

        return back()->with('success', $status === CertificadoSolicitacao::STATUS_APROVADO ? 'Solicitacao aprovada.' : 'Solicitacao rejeitada.');
    }

    private function garantirSolicitacao(Matricula $matricula): CertificadoSolicitacao
    {
        $existente = CertificadoSolicitacao::where('matricula_id', $matricula->id)->first();

        if ($existente) {
            return $existente;
        }

        $matricula->loadMissing('curso.formador.pessoa.user', 'user.pessoa.estudante');
        $formadorId = $matricula->curso?->formador?->id;
        $estudanteId = $matricula->user?->pessoa?->estudante?->id;

        abort_unless($formadorId && $estudanteId, 404);

        $solicitacao = CertificadoSolicitacao::create([
            'matricula_id' => $matricula->id,
            'curso_id' => $matricula->curso_id,
            'estudante_id' => $estudanteId,
            'instrutor_id' => $formadorId,
            'status' => CertificadoSolicitacao::STATUS_AGUARDANDO_QUESTIONARIO,
        ]);

        $this->notificar(
            $matricula->curso?->formador?->pessoa?->user,
            'Aluno concluiu o curso',
            'O aluno '.$matricula->user?->name.' concluiu o curso '.$matricula->curso?->titulo.'. Crie a prova para iniciar a liberacao do certificado.',
            [
                'linhas' => [
                    'Aluno' => $matricula->user?->name ?? '-',
                    'Curso' => $matricula->curso?->titulo ?? '-',
                    'Progresso' => '100%',
                ],
                'acao_url' => route('formador.certificados.questionario', $solicitacao),
                'acao_texto' => 'Criar prova',
                'preheader' => 'Aluno concluiu o curso e aguarda prova de certificado.',
            ]
        );

        return $solicitacao;
    }

    private function perguntas(?CertificadoQuestionario $questionario): array
    {
        $conteudo = (string) $questionario?->perguntas;
        $perguntasJson = json_decode($conteudo, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($perguntasJson)) {
            return array_values(array_filter(array_map('trim', $perguntasJson)));
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $conteudo))));
    }

    private function notificar(?User $user, string $titulo, string $mensagem, array $emailDados = []): void
    {
        try {
            app(NotificacaoService::class)->enviar($user, $titulo, $mensagem, ['email', 'sms', 'whatsapp'], $emailDados);
        } catch (\Throwable $e) {
            // O fluxo principal nao deve falhar se a entrega externa estiver indisponivel.
        }
    }
}
