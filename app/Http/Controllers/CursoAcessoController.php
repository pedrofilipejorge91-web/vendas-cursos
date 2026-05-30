<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Avaliacao;
use App\Models\Certificado;
use App\Models\CertificadoSolicitacao;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\ProgressoAula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CursoAcessoController extends Controller
{
    // garante compatibilidade com o fluxo novo de solicitacao de certificado
    // (admin precisa aprovar para liberar download do PDF)
    public function dashboard()
    {
        $matriculas = Matricula::with('curso.categoria', 'curso.aulas', 'certificado')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $metricas = [
            'cursos' => $matriculas->count(),
            'em_andamento' => $matriculas->where('progresso', '<', 100)->count(),
            'concluidos' => $matriculas->where('progresso', '>=', 100)->count(),
            'certificados' => $matriculas->filter(fn ($matricula) => $matricula->certificado)->count(),
        ];

        $proximaAula = null;
        $matriculaActual = $matriculas->firstWhere('progresso', '<', 100) ?? $matriculas->first();

        if ($matriculaActual) {
            $aulasConcluidas = $matriculaActual->progressos()->whereNotNull('concluido_em')->pluck('aula_id');
            $proximaAula = $matriculaActual->curso->aulas()
                ->whereNotIn('id', $aulasConcluidas)
                ->orderBy('numero_aula')
                ->first() ?? $matriculaActual->curso->aulas()->orderBy('numero_aula')->first();
        }

        $cursosRecomendados = Curso::with('categoria')
            ->where('status', 'publicado')
            ->whereNotIn('id', $matriculas->pluck('curso_id'))
            ->latest()
            ->take(3)
            ->get();

        return view('estudante.dashboard', compact('matriculas', 'metricas', 'matriculaActual', 'proximaAula', 'cursosRecomendados'));
    }

    public function meusCursos()
    {
        $matriculas = Matricula::with('curso.categoria', 'curso.aulas', 'certificado')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('estudante.cursos', compact('matriculas'));
    }

    public function perfil()
    {
        $user = Auth::user()->load('pessoa.estudante');

        return view('estudante.perfil', [
            'user' => $user,
            'pessoa' => $user->pessoa,
            'estudante' => $user->pessoa?->estudante,
        ]);
    }

    public function actualizarPerfil(Request $request)
    {
        $user = Auth::user()->load('pessoa.estudante');
        $pessoa = $user->pessoa;
        $estudante = $pessoa?->estudante;

        abort_unless($pessoa && $estudante, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'primeironome' => 'required|string|max:255',
            'segundonome' => 'required|string|max:255',
            'contacto' => 'required|string|max:30',
            'rua' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'localizacao' => 'nullable|string|max:255',
            'area_interesse' => 'nullable|string|max:255',
            'formacao' => 'nullable|string|max:255',
            'escola_actual' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $validated['name'],
        ]);

        $pessoa->update([
            'primeironome' => $validated['primeironome'],
            'segundonome' => $validated['segundonome'],
            'contacto' => $validated['contacto'],
            'rua' => $validated['rua'] ?? null,
            'bairro' => $validated['bairro'] ?? null,
        ]);

        $estudante->update([
            'localizacao' => $validated['localizacao'] ?? null,
            'area_interesse' => $validated['area_interesse'] ?? null,
            'formacao' => $validated['formacao'] ?? null,
            'escola_actual' => $validated['escola_actual'] ?? null,
        ]);

        return back()->with('success', 'Perfil actualizado com sucesso.');
    }

    public function show(Matricula $matricula)
    {
        $this->autorizarAluno($matricula);

        $matricula->load('curso.aulas', 'curso.avaliacoes.estudante.pessoa', 'progressos', 'certificado');
        $aulaAtual = $matricula->curso->aulas->first();
        $minhaAvaliacao = $this->obterMinhaAvaliacao($matricula);

        return view('estudante.curso', compact('matricula', 'aulaAtual', 'minhaAvaliacao'));
    }

    public function aula(Matricula $matricula, Aula $aula)
    {
        $this->autorizarAluno($matricula);
        abort_unless($aula->curso_id === $matricula->curso_id, 404);

        $matricula->load('curso.aulas', 'curso.avaliacoes.estudante.pessoa', 'progressos', 'certificado');
        $aulaAtual = $aula;
        $minhaAvaliacao = $this->obterMinhaAvaliacao($matricula);

        return view('estudante.curso', compact('matricula', 'aulaAtual', 'minhaAvaliacao'));
    }

    public function concluirAula(Request $request, Matricula $matricula, Aula $aula)
    {
        $this->autorizarAluno($matricula);
        abort_unless($aula->curso_id === $matricula->curso_id, 404);

        ProgressoAula::updateOrCreate([
            'matricula_id' => $matricula->id,
            'aula_id' => $aula->id,
        ], [
            'concluido_em' => now(),
        ]);

        $this->actualizarProgresso($matricula);

        return back()->with('success', 'Aula marcada como concluida.');
    }

    public function certificado(Matricula $matricula)
    {
        $this->autorizarAluno($matricula);

        abort_unless((float) $matricula->progresso >= 100, 403);

        // fluxo: ao concluir, cria a solicitação pro instrutor/admin.
        $this->garantirSolicitacaoCertificado($matricula);

        // certificado (QR/PDF) só deve ser baixado após aprovação do admin.
        $certificado = Certificado::firstOrCreate([
            'matricula_id' => $matricula->id,
        ], [
            'codigo' => 'CERT-'.now()->format('Ymd').'-'.Str::upper(Str::random(8)),
            'emitido_em' => now(),
        ]);

        $matricula->load('curso.formador.pessoa', 'curso.aulas', 'user');

        return view('estudante.certificado', compact('matricula', 'certificado'));
    }


    public function certificadoPdf(Matricula $matricula)
    {
        $this->autorizarAluno($matricula);

        abort_unless((float) $matricula->progresso >= 100, 403);

        // somente libera o PDF se o admin aprovou.
        $solicitacao = $this->obterSolicitacaoCertificado($matricula);
        abort_unless($solicitacao && $solicitacao->status === CertificadoSolicitacao::STATUS_APROVADO, 403);

        $certificado = Certificado::firstOrCreate([
            'matricula_id' => $matricula->id,
        ], [
            'codigo' => 'CERT-'.now()->format('Ymd').'-'.Str::upper(Str::random(8)),
            'emitido_em' => now(),
        ]);


        $matricula->load('curso.formador.pessoa', 'user');

        // Generate QR Code
        $verificationUrl = route('certificado.verificar', $certificado->codigo);
        $qrCode = QrCode::size(200)->generate($verificationUrl);

        $pdf = Pdf::loadView('estudante.certificado-pdf', [
            'matricula' => $matricula,
            'certificado' => $certificado,
            'solicitacao' => $solicitacao,
            'qrCode' => $qrCode,
            'verificationUrl' => $verificationUrl,
            'centro' => [
                'nome' => env('CENTRO_FORMACAO_NOME', 'Centro de Formacao Paruana Comercial'),
                'nif' => env('CENTRO_FORMACAO_NIF'),
                'endereco' => env('CENTRO_FORMACAO_ENDERECO', 'Angola'),
                'contacto' => env('CENTRO_FORMACAO_CONTACTO'),
            ],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('certificado-' . Str::slug($matricula->user->name) . '.pdf');
    }

    public function downloadAula(Matricula $matricula, Aula $aula)
    {
        $this->autorizarAluno($matricula);
        abort_unless($aula->curso_id === $matricula->curso_id, 404);

        if (! $aula->permite_download) {
            return back()->withErrors(['download' => 'O download deste material nao foi permitido pelo instrutor.']);
        }

        if (! $aula->arquivo_video || ! Storage::disk('public')->exists($aula->arquivo_video)) {
            return back()->withErrors(['download' => 'Conteúdo não disponível para download.']);
        }

        return Storage::disk('public')->download($aula->arquivo_video, sprintf('%s-%s.%s', Str::slug($aula->titulo), $aula->id, pathinfo($aula->arquivo_video, PATHINFO_EXTENSION)));
    }

    public function verificarCertificado(string $codigo)
    {
        $certificado = Certificado::with('matricula.curso', 'matricula.user')
            ->where('codigo', $codigo)
            ->firstOrFail();

        return view('estudante.verificar-certificado', compact('certificado'));
    }

    private function actualizarProgresso(Matricula $matricula): void
    {
        $totalAulas = $matricula->curso->aulas()->count();
        $aulasConcluidas = $matricula->progressos()->whereNotNull('concluido_em')->count();
        $progresso = $totalAulas > 0 ? round(($aulasConcluidas / $totalAulas) * 100, 2) : 100;

        $matricula->update([
            'progresso' => $progresso,
            'concluido_em' => $progresso >= 100 ? now() : null,
        ]);
    }

    private function obterSolicitacaoCertificado(Matricula $matricula): ?CertificadoSolicitacao
    {
        // Solicitação única por matrícula (conforme unique no migration).
        return CertificadoSolicitacao::where('matricula_id', $matricula->id)->first();
    }

    private function garantirSolicitacaoCertificado(Matricula $matricula): void
    {
        // Evita duplicar notificação/solicitação caso o aluno abra o certificado várias vezes.
        $solicitacaoExistente = $this->obterSolicitacaoCertificado($matricula);
        if ($solicitacaoExistente) {
            return;
        }

        $formadorId = $matricula->curso?->formador?->id;
        abort_unless($formadorId, 404);

        $solicitacao = CertificadoSolicitacao::create([
            'matricula_id' => $matricula->id,
            'curso_id' => $matricula->curso_id,
            // FK aponta para `estudantes.id`, então precisamos usar o id correto da tabela estudantes
            'estudante_id' => $matricula->user?->pessoa?->estudante?->id,
            'instrutor_id' => $formadorId,
            'status' => CertificadoSolicitacao::STATUS_AGUARDANDO_QUESTIONARIO,
        ]);

        // Notificar o instrutor do curso sobre a solicitação.
        // (Mantemos compatível com o NotificacaoService já existente no projeto.)
        try {
            $userInstrutor = $solicitacao->instrutor?->pessoa?->user;
            if ($userInstrutor) {
                app(\App\Services\NotificacaoService::class)->enviar(
                    $userInstrutor,
                    'Solicitacao de certificado criada',
                    'O aluno concluiu o curso '.$matricula->curso?->titulo.'. Elabore o questionario para dar continuidade ao processo de emissao do certificado.',
                    ['email', 'sms', 'whatsapp'],
                    [
                        'linhas' => [
                            'Aluno' => $matricula->user?->name ?? '-',
                            'Curso' => $matricula->curso?->titulo ?? '-',
                            'Progresso' => '100%',
                        ],
                        'acao_url' => route('formador.certificados.questionario', $solicitacao),
                        'acao_texto' => 'Criar questionario',
                        'preheader' => 'Aluno concluiu o curso e aguarda questionario de certificado.',
                    ]
                );
            }
        } catch (\Throwable $e) {
            // Não quebrar fluxo do aluno se notificação falhar.
        }
    }

    private function autorizarAluno(Matricula $matricula): void
    {
        abort_unless($matricula->user_id === Auth::id() || Auth::user()?->tipo === 'admin', 403);
    }

    private function obterMinhaAvaliacao(Matricula $matricula): ?Avaliacao
    {
        $estudanteId = Auth::user()?->pessoa?->estudante?->id;

        if (! $estudanteId) {
            return null;
        }

        return Avaliacao::where('curso_id', $matricula->curso_id)
            ->where('estudante_id', $estudanteId)
            ->first();
    }

}
