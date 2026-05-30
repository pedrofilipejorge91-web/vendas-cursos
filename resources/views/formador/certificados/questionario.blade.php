@extends('formador-layouts.apps')

@section('content')
@php
    $perguntasJson = json_decode((string) ($questionario?->perguntas ?? ''), true);
    $perguntas = json_last_error() === JSON_ERROR_NONE && is_array($perguntasJson)
        ? array_values(array_filter(array_map('trim', $perguntasJson)))
        : array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($questionario?->perguntas ?? '')))));
    $perguntasForm = old('perguntas', $perguntas ?: ['']);
    $respostas = $resposta ? json_decode($resposta->respostas, true) : [];
    $provaBloqueada = $solicitacao->status === \App\Models\CertificadoSolicitacao::STATUS_APROVADO;
@endphp

<style>
    .question-builder {
        display: grid;
        gap: 12px;
    }

    .question-builder-item {
        display: grid;
        grid-template-columns: 38px minmax(0, 1fr) 42px;
        gap: 10px;
        align-items: start;
    }

    .question-number {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #eef2ff;
        color: #3730a3;
        font-weight: 700;
    }

    .question-remove {
        width: 42px;
        height: 38px;
        padding: 0;
    }

    @media (max-width: 575.98px) {
        .question-builder-item {
            grid-template-columns: 34px minmax(0, 1fr);
        }

        .question-remove {
            grid-column: 2;
            width: 100%;
        }
    }
</style>

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
                        <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                            <label class="form-label mb-0">Perguntas da prova</label>
                            <button class="btn btn-outline-primary btn-sm" type="button" id="add-question" @disabled($provaBloqueada)>
                                <i class="bi bi-plus-lg me-1"></i>Adicionar pergunta
                            </button>
                        </div>

                        <div class="question-builder" id="question-builder">
                            @foreach($perguntasForm as $index => $pergunta)
                                <div class="question-builder-item" data-question-item>
                                    <span class="question-number" data-question-number>{{ $index + 1 }}</span>
                                    <div>
                                        <textarea
                                            name="perguntas[]"
                                            class="form-control"
                                            rows="2"
                                            required
                                            @disabled($provaBloqueada)
                                            placeholder="Escreva a pergunta {{ $index + 1 }}"
                                        >{{ $pergunta }}</textarea>
                                        @error("perguntas.$index")
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button class="btn btn-outline-danger question-remove" type="button" data-remove-question title="Remover pergunta" aria-label="Remover pergunta" @disabled($provaBloqueada)>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        @error('perguntas')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror

                        <button class="btn btn-primary mt-3" type="submit" @disabled($provaBloqueada)>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const builder = document.getElementById('question-builder');
    const addButton = document.getElementById('add-question');

    function renumberQuestions() {
        const items = Array.from(builder.querySelectorAll('[data-question-item]'));

        items.forEach(function (item, index) {
            const number = item.querySelector('[data-question-number]');
            const textarea = item.querySelector('textarea');
            const removeButton = item.querySelector('[data-remove-question]');

            number.textContent = index + 1;
            textarea.placeholder = 'Escreva a pergunta ' + (index + 1);
            removeButton.disabled = items.length === 1;
        });
    }

    function createQuestion() {
        const item = document.createElement('div');
        item.className = 'question-builder-item';
        item.setAttribute('data-question-item', '');
        item.innerHTML = `
            <span class="question-number" data-question-number></span>
            <div>
                <textarea name="perguntas[]" class="form-control" rows="2" required></textarea>
            </div>
            <button class="btn btn-outline-danger question-remove" type="button" data-remove-question title="Remover pergunta" aria-label="Remover pergunta">
                <i class="bi bi-trash"></i>
            </button>
        `;

        builder.appendChild(item);
        renumberQuestions();
        item.querySelector('textarea').focus();
    }

    addButton?.addEventListener('click', createQuestion);

    builder?.addEventListener('click', function (event) {
        const removeButton = event.target.closest('[data-remove-question]');

        if (!removeButton) {
            return;
        }

        const items = builder.querySelectorAll('[data-question-item]');

        if (items.length <= 1) {
            return;
        }

        removeButton.closest('[data-question-item]').remove();
        renumberQuestions();
    });

    renumberQuestions();
});
</script>
@endsection
