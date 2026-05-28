@extends('estudant-layouts.apps')

@section('content')
@php
    $perguntas = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($questionario?->perguntas ?? '')))));
    $respostas = $resposta ? json_decode($resposta->respostas, true) : [];
    $podeResponder = $questionario && !empty($perguntas) && !in_array($solicitacao->status, [
        \App\Models\CertificadoSolicitacao::STATUS_AGUARDANDO_ADMIN,
        \App\Models\CertificadoSolicitacao::STATUS_APROVADO,
    ], true);
@endphp

<section class="section">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h3 class="mb-2">Questionario de Certificado</h3>
                    <p class="text-muted mb-0">Curso: <strong>{{ $solicitacao->curso?->titulo ?? '-' }}</strong></p>
                </div>
                <span class="badge bg-{{ $solicitacao->statusBadge() }}">{{ $solicitacao->statusLabel() }}</span>
            </div>

            @if(!$questionario || empty($perguntas))
                <div class="alert alert-warning">O formador ainda nao publicou a prova. Voce sera notificado quando estiver disponivel.</div>
            @else
                @if($resposta)
                    <div class="alert alert-info">Voce ja enviou respostas. Pode reenviar enquanto o formador ainda nao encaminhou a nota ao admin.</div>
                @endif

                <form method="POST" action="{{ route('estudante.certificados.responder_questionario', $questionario) }}">
                    @csrf

                    @foreach($perguntas as $i => $pergunta)
                        <div class="mb-3">
                            <label class="form-label">{{ $i + 1 }}. {{ $pergunta }}</label>
                            <textarea class="form-control" name="respostas[]" rows="3" required @disabled(!$podeResponder)>{{ old("respostas.$i", $respostas[$i] ?? '') }}</textarea>
                        </div>
                    @endforeach

                    <button class="btn btn-success" type="submit" @disabled(!$podeResponder)>
                        Enviar respostas ao formador
                    </button>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
