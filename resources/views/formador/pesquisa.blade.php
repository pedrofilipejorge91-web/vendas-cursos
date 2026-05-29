@extends('formador-layouts.apps')

@section('content')
<div class="formador-dashboard">
    <div class="edit-page-header">
        <div>
            <span class="eyebrow">Pesquisa</span>
            <h1>Resultados</h1>
            <p>Resultados encontrados para “{{ $termo ?: '...' }}”.</p>
        </div>
    </div>

    <div class="content-grid">
        <section class="panel-card">
            <div class="panel-heading"><div><span class="eyebrow">Cursos</span><h2>Meus cursos</h2></div></div>
            <div class="course-list">
                @forelse($cursos as $curso)
                    <a class="action-item" href="{{ route('formador.cursos.edit', $curso->id) }}">
                        <i class="bi bi-mortarboard"></i>
                        <div><strong>{{ $curso->titulo }}</strong><span>{{ ucfirst($curso->status) }} · {{ $curso->categoria?->nome ?? 'Sem categoria' }}</span></div>
                    </a>
                @empty
                    <p class="text-muted mb-0">Nenhum curso encontrado.</p>
                @endforelse
            </div>
        </section>

        <aside class="panel-card">
            <div class="panel-heading"><div><span class="eyebrow">Aulas</span><h2>Conteúdos</h2></div></div>
            @forelse($aulas as $aula)
                <a class="action-item" href="{{ route('formador.aulas.show', $aula) }}">
                    <i class="bi bi-play-btn"></i>
                    <div><strong>{{ $aula->titulo }}</strong><span>{{ $aula->curso?->titulo }} · {{ $aula->duracao_minutos }} min</span></div>
                </a>
            @empty
                <p class="text-muted mb-0">Nenhuma aula encontrada.</p>
            @endforelse
        </aside>
    </div>

    <section class="panel-card mt-3">
        <div class="panel-heading"><div><span class="eyebrow">Certificados</span><h2>Solicitações</h2></div></div>
        @forelse($certificados as $solicitacao)
            <a class="action-item" href="{{ route('formador.certificados.questionario', $solicitacao) }}">
                <i class="bi bi-award"></i>
                <div><strong>{{ $solicitacao->matricula?->user?->name ?? 'Aluno' }}</strong><span>{{ $solicitacao->curso?->titulo }} · {{ $solicitacao->statusLabel() }}</span></div>
            </a>
        @empty
            <p class="text-muted mb-0">Nenhuma solicitação encontrada.</p>
        @endforelse
    </section>
</div>
@endsection
