@extends('formador-layouts.apps')

@section('content')

@php
    $totalCursos = $totalCursos ?? $cursos->count();
    $cursosRascunho = $cursos->where('status', 'rascunho')->count();
    $cursosRecentes = $cursos->take(4);
@endphp

<div class="formador-dashboard">
    <section class="formador-hero">
        <div class="formador-hero-copy">
            <span class="eyebrow">Painel do Formador</span>
            <h1>Olá, {{ auth()->user()->name }}</h1>
            <p>Gerencie cursos, aulas, certificados e acompanhe a evolução dos seus alunos num espaço mais claro e produtivo.</p>
            <div class="hero-actions">
                <a href="{{ route('formador.cursos') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Novo curso
                </a>
                <a href="{{ route('formador.aulas') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-play-btn me-2"></i>Gerir aulas
                </a>
            </div>
        </div>

        <div class="formador-hero-panel">
            <span>Resumo rápido</span>
            <strong>{{ $totalCursos }}</strong>
            <small>curso{{ $totalCursos === 1 ? '' : 's' }} na sua área</small>
        </div>
    </section>

    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-icon primary"><i class="bi bi-mortarboard"></i></div>
            <span>Cursos ativos</span>
            <strong>{{ $cursosAtivos }}</strong>
            <small>{{ $cursosRascunho }} em rascunho</small>
        </article>

        <article class="stat-card">
            <div class="stat-icon success"><i class="bi bi-people"></i></div>
            <span>Estudantes</span>
            <strong>{{ $totalAlunos }}</strong>
            <small>inscrições nos seus cursos</small>
        </article>

        <article class="stat-card">
            <div class="stat-icon warning"><i class="bi bi-award"></i></div>
            <span>Certificados</span>
            <strong>Em análise</strong>
            <small>acompanhe as solicitações</small>
        </article>
    </section>

    <section class="content-grid">
        <div class="panel-card courses-panel">
            <div class="panel-heading">
                <div>
                    <span class="eyebrow">Catálogo</span>
                    <h2>Cursos recentes</h2>
                </div>
                <a href="{{ route('formador.cursos') }}" class="btn btn-sm btn-outline-primary">
                    Ver todos
                </a>
            </div>

            <div class="course-list">
                @forelse($cursosRecentes as $curso)
                    <article class="course-row">
                        <img
                            src="{{ $curso->foto ? asset('storage/'.$curso->foto) : asset('assets/img/paruana/formando-instrutor.jpg') }}"
                            alt="{{ $curso->titulo }}">

                        <div class="course-row-body">
                            <div class="course-row-top">
                                <h3>{{ $curso->titulo }}</h3>
                                <span class="status-pill status-{{ $curso->status }}">{{ ucfirst($curso->status) }}</span>
                            </div>

                            <div class="course-meta">
                                <span><i class="bi bi-people"></i>{{ $curso->inscricoes->count() }} inscritos</span>
                                <span><i class="bi bi-clock"></i>{{ $curso->duracao_horas }}h</span>
                                <span><i class="bi bi-cash-coin"></i>{{ number_format($curso->preco, 2, ',', '.') }} Kz</span>
                            </div>

                            <div class="course-actions">
                                <a href="{{ route('formador.cursos.edit', $curso->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square me-1"></i>Editar
                                </a>

                                <form action="{{ route('formador.cursos.destroy', $curso->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        <i class="bi bi-trash me-1"></i>Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-journal-plus"></i>
                        <h3>Nenhum curso encontrado</h3>
                        <p>Comece criando o seu primeiro curso para que ele siga para análise e publicação.</p>
                        <a href="{{ route('formador.cursos') }}" class="btn btn-primary">Criar curso</a>
                    </div>
                @endforelse
            </div>
        </div>

        <aside class="panel-card next-actions">
            <div class="panel-heading">
                <div>
                    <span class="eyebrow">Prioridades</span>
                    <h2>Ações rápidas</h2>
                </div>
            </div>

            <a href="{{ route('formador.cursos') }}" class="action-item">
                <i class="bi bi-mortarboard"></i>
                <div>
                    <strong>Organizar cursos</strong>
                    <span>Atualize capas, preços e descrições.</span>
                </div>
            </a>

            <a href="{{ route('formador.aulas') }}" class="action-item">
                <i class="bi bi-play-btn"></i>
                <div>
                    <strong>Gerir aulas</strong>
                    <span>Adicione vídeos, PDFs e materiais.</span>
                </div>
            </a>

            <a href="{{ route('formador.certificados.solicitacoes') }}" class="action-item">
                <i class="bi bi-award"></i>
                <div>
                    <strong>Certificados</strong>
                    <span>Responda solicitações pendentes.</span>
                </div>
            </a>
        </aside>
    </section>
</div>

@endsection
