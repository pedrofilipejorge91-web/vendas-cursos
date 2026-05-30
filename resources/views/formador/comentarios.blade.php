@extends('formador-layouts.apps')

@section('content')
<div class="formador-dashboard">
    <div class="edit-page-header">
        <div>
            <span class="eyebrow">Comentários</span>
            <h1>Avaliações dos alunos</h1>
            <p>Leia e responda aos comentários deixados nos seus cursos.</p>
        </div>
    </div>

    <section class="panel-card">
        @forelse($avaliacoes as $avaliacao)
            <article class="support-item">
                <div>
                    <span class="status-pill status-publicado">{{ $avaliacao->nota }}/5</span>
                    <h3>{{ $avaliacao->curso?->titulo }}</h3>
                    <p class="text-muted mb-2">{{ $avaliacao->estudante?->pessoa?->primeironome ?? 'Aluno' }} · {{ $avaliacao->created_at?->format('d/m/Y') }}</p>
                    <p>{{ $avaliacao->comentario ?: 'Sem comentário escrito.' }}</p>
                    @if($avaliacao->resposta_instrutor)
                        <div class="alert alert-success">
                            <strong>Resposta publicada:</strong> {{ $avaliacao->resposta_instrutor }}
                        </div>
                    @endif
                    <form action="{{ route('avaliacoes.responder', $avaliacao) }}" method="POST" class="mt-3">
                        @csrf
                        <textarea name="resposta_instrutor" class="form-control mb-2" rows="3" placeholder="Escreva uma resposta ao aluno..." required>{{ old('resposta_instrutor', $avaliacao->resposta_instrutor) }}</textarea>
                        @error('resposta_instrutor')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-send me-2"></i>{{ $avaliacao->resposta_instrutor ? 'Actualizar resposta' : 'Responder' }}
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <i class="bi bi-chat-dots"></i>
                <h3>Sem comentários</h3>
                <p>Ainda não existem avaliações nos seus cursos.</p>
            </div>
        @endforelse

        {{ $avaliacoes->links() }}
    </section>
</div>
@endsection
