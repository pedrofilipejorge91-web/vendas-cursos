@extends('estudant-layouts.apps')

@section('content')
<section class="section">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5">
            <h3 class="mb-2">Questionário de Certificado</h3>
            <p class="text-muted mb-4">Curso: <strong>{{ $solicitacao->curso?->titulo ?? '-' }}</strong></p>

            <form method="POST" action="{{ route('estudante.certificados.responder_questionario', $questionario) }}">
                @csrf

                <div class="mb-4">
                    <h5 class="mb-3">Perguntas</h5>

                    @php
                        $perguntasRaw = $questionario->perguntas ?? '';
                        $perguntas = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string)$perguntasRaw)));
                    @endphp

                    @if(empty($perguntas))
                        <div class="alert alert-warning">Este questionário ainda não foi publicado pelo instrutor.</div>
                    @else
                        @foreach($perguntas as $i => $pergunta)
                            <div class="mb-3">
                                <label class="form-label">{{ $i+1 }}. {{ $pergunta }}</label>
                                <textarea class="form-control" name="respostas[]" rows="3" required></textarea>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button class="btn btn-success" type="submit" {{ empty($perguntas) ? 'disabled' : '' }}>
                    Enviar respostas
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

