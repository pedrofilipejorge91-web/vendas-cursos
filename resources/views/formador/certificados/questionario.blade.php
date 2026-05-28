@extends('formador-layouts.apps')

@section('content')
@php
    $perguntas = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($questionario?->perguntas ?? '')))));
    $respostas = $resposta ? json_decode($resposta->respostas, true) : [];
@endphp

<section class="section">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-2">Prova do certificado</h4>
                    <p class="text-muted mb-3">
                        {{ $solicitacao->matricula?->user?->name ?? '-' }} -
                        {{ $solicitacao->curso?->titulo ?? '-' }}
                    </p>
                    <span class="badge bg-{{ $solicitacao->statusBadge() }}">{{ $solicitacao->statusLabel() }}</span>

                    <form method="POST" action="{{ route('formador.certificados.questionario.criar', $solicitacao) }}" class="mt-4">
                        @csrf
                        <label class="form-label">Perguntas da prova</label>
                        <textarea name="perguntas_texto" class="form-control" rows="10" required placeholder="Escreva uma pergunta por linha">{{ old('perguntas_texto', $questionario?->perguntas) }}</textarea>
                        @error('perguntas_texto')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <button class="btn btn-primary mt-3" type="submit" @disabled($solicitacao->status === \App\Models\CertificadoSolicitacao::STATUS_APROVADO)>
                            Publicar prova para o aluno
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-3">Resposta e correcao</h4>

                    @if(!$questionario)
                        <div class="alert alert-warning">Crie a prova para o aluno responder.</div>
                    @elseif(!$resposta)
                        <div class="alert alert-info">A prova foi publicada. Aguardando resposta do aluno.</div>
                    @else
                        <div class="mb-4">
                            <p class="text-muted mb-2">Enviado em {{ $resposta->enviado_em?->format('d/m/Y H:i') ?? '-' }}</p>

                            @foreach($perguntas as $index => $pergunta)
                                <div class="border rounded p-3 mb-3">
                                    <strong>{{ $index + 1 }}. {{ $pergunta }}</strong>
                                    <p class="mb-0 mt-2">{{ $respostas[$index] ?? '-' }}</p>
                                </div>
                            @endforeach
                        </div>

                        <form method="POST" action="{{ route('formador.certificados.nota', $solicitacao) }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nota</label>
                                    <input type="number" name="nota_curso" class="form-control" min="0" max="20" step="0.01" value="{{ old('nota_curso', $solicitacao->nota_curso) }}" required>
                                    @error('nota_curso')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Observacoes para o admin</label>
                                    <textarea name="observacoes" class="form-control" rows="3">{{ old('observacoes', $solicitacao->observacoes_admin) }}</textarea>
                                </div>
                            </div>
                            <button class="btn btn-success mt-3" type="submit" @disabled($solicitacao->status === \App\Models\CertificadoSolicitacao::STATUS_APROVADO)>
                                Enviar nota ao admin
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
