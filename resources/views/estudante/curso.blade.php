@extends('estudant-layouts.apps')

@section('content')
@php
    $aulas = $matricula->curso->aulas->sortBy('numero_aula')->values();
    $aulasConcluidas = $matricula->progressos->whereNotNull('concluido_em')->pluck('aula_id')->all();
    $totalAulas = $aulas->count();
    $totalConcluidas = count($aulasConcluidas);
    $tempoTotal = $aulas->sum('duracao_minutos');
    $aulaIndex = $aulaAtual ? $aulas->search(fn ($aula) => $aula->id === $aulaAtual->id) : false;
    $aulaAnterior = $aulaIndex !== false && $aulaIndex > 0 ? $aulas[$aulaIndex - 1] : null;
    $proximaAula = $aulaIndex !== false && $aulaIndex < $totalAulas - 1 ? $aulas[$aulaIndex + 1] : null;
    $aulaConcluida = $aulaAtual ? in_array($aulaAtual->id, $aulasConcluidas) : false;
    $percentual = min((float) $matricula->progresso, 100);
    $tipoIcones = [
        'video' => 'bi-play-circle',
        'pdf' => 'bi-file-earmark-pdf',
        'quiz' => 'bi-ui-checks',
        'texto' => 'bi-card-text',
    ];
    $comentariosCurso = $matricula->curso->avaliacoes
        ->filter(fn ($avaliacao) => filled($avaliacao->comentario))
        ->sortByDesc('created_at');
@endphp

<style>
    .learning-shell {
        background: #f6f8fb;
        margin: -12px -12px 0;
        padding: 18px;
        min-height: calc(100vh - 140px);
    }

    .course-topbar {
        background: #111827;
        color: #fff;
        border-radius: 8px;
        padding: 22px;
    }

    .course-topbar .breadcrumb a,
    .course-topbar .breadcrumb-item,
    .course-topbar .breadcrumb-item.active {
        color: rgba(255, 255, 255, .72);
    }

    .course-topbar .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, .45);
    }

    .learning-stat {
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 8px;
        padding: 12px;
        min-height: 76px;
    }

    .lesson-stage,
    .lesson-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
    }

    .lesson-stage {
        overflow: hidden;
    }

    .lesson-media {
        background: #0b1220;
    }

    .lesson-toolbar {
        border-top: 1px solid #e5e7eb;
        padding: 16px;
    }

    .lesson-list {
        max-height: 620px;
        overflow: auto;
    }

    .lesson-item {
        display: block;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        color: #1f2937;
        text-decoration: none;
        background: #fff;
        transition: border-color .15s ease, background .15s ease, transform .15s ease;
    }

    .lesson-item:hover {
        border-color: #2563eb;
        background: #f8fbff;
        transform: translateY(-1px);
    }

    .lesson-item.is-active {
        border-color: #2563eb;
        box-shadow: inset 4px 0 0 #2563eb;
    }

    .lesson-item.is-complete {
        border-color: #bbf7d0;
        background: #f7fef9;
    }

    .lesson-meta {
        font-size: .78rem;
        color: #6b7280;
    }

    .sticky-learning-panel {
        position: sticky;
        top: 86px;
    }

    .course-rating-form {
        display: grid;
        gap: 12px;
    }

    .course-rating-form h3 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
    }

    .course-rating-field {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        border: 0;
        padding: 0;
        margin: 0;
    }

    .course-rating-field legend {
        width: 100%;
        margin: 0;
        color: #6b7280;
        font-size: .82rem;
        font-weight: 600;
    }

    .rating-zero input,
    .star-rating input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .rating-zero span,
    .star-rating label {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        color: #9ca3af;
        cursor: pointer;
        transition: color .15s ease, border-color .15s ease, background .15s ease;
    }

    .rating-zero input:checked + span,
    .rating-zero:hover span {
        border-color: #2563eb;
        background: #eff6ff;
        color: #2563eb;
        font-weight: 700;
    }

    .star-rating {
        display: inline-flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 4px;
    }

    .star-rating label {
        font-size: 1rem;
    }

    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        border-color: #f59e0b;
        background: #fffbeb;
        color: #f59e0b;
    }

    .course-rating-form textarea {
        width: 100%;
        min-height: 92px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 12px;
        resize: vertical;
    }

    .course-review-submit {
        min-height: 40px;
        border: 0;
        border-radius: 8px;
        color: #fff;
        background: #2563eb;
        font-weight: 700;
    }

    .student-comment-list {
        display: grid;
        gap: 12px;
    }

    .student-comment {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        background: #fff;
    }

    .student-comment-stars {
        display: flex;
        align-items: center;
        gap: 3px;
        color: #f59e0b;
        font-size: .9rem;
    }

    .student-comment-reply {
        margin-top: 10px;
        padding: 10px;
        border-radius: 8px;
        background: #eff6ff;
        color: #1e3a8a;
        font-size: .88rem;
    }

    @media (max-width: 991.98px) {
        .learning-shell {
            margin: -8px -8px 0;
            padding: 12px;
        }

        .course-topbar {
            padding: 16px;
        }

        .sticky-learning-panel {
            position: static;
        }

        .lesson-list {
            max-height: none;
        }
    }
</style>

<div class="learning-shell">
    <div class="course-topbar mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('estudante.cursos') }}">Meus cursos</a></li>
                <li class="breadcrumb-item active">Sala de aula</li>
            </ol>
        </nav>

        <div class="row g-4 align-items-end">
            <div class="col-lg-7">
                <span class="badge text-bg-light mb-3">{{ $matricula->curso->categoria->nome ?? 'Curso' }}</span>
                <h1 class="h2 fw-bold mb-2">{{ $matricula->curso->titulo }}</h1>
                <p class="mb-0 text-white-50">{{ Str::limit($matricula->curso->descricao, 160) }}</p>
            </div>

            <div class="col-lg-5">
                <div class="row g-2">
                    <div class="col-4">
                        <div class="learning-stat">
                            <div class="small text-white-50">Progresso</div>
                            <div class="h4 fw-bold mb-0">{{ number_format($percentual, 0) }}%</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="learning-stat">
                            <div class="small text-white-50">Aulas</div>
                            <div class="h4 fw-bold mb-0">{{ $totalConcluidas }}/{{ $totalAulas }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="learning-stat">
                            <div class="small text-white-50">Tempo</div>
                            <div class="h4 fw-bold mb-0">{{ $tempoTotal }}m</div>
                        </div>
                    </div>
                </div>

                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ $percentual }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row g-4">
            <div class="col-xl-8">
                <div class="lesson-stage">
                    @if($aulaAtual)
                        <div class="p-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary">
                                            <i class="bi {{ $tipoIcones[$aulaAtual->tipo] ?? 'bi-journal-text' }} me-1"></i>
                                            {{ ucfirst($aulaAtual->tipo) }}
                                        </span>
                                        @if($aulaConcluida)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check2-circle me-1"></i> Concluida
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">Em progresso</span>
                                        @endif
                                    </div>
                                    <h2 class="h4 fw-bold mb-1">{{ $aulaAtual->titulo }}</h2>
                                    <p class="text-muted mb-0">{{ $aulaAtual->descricao ?: 'Sem descricao para esta aula.' }}</p>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">Aula {{ $aulaIndex !== false ? $aulaIndex + 1 : '-' }} de {{ $totalAulas }}</div>
                                    <div class="fw-semibold">{{ $aulaAtual->duracao_minutos }} min</div>
                                </div>
                            </div>
                        </div>

                        <div class="ratio ratio-16x9 lesson-media">
                            @if($aulaAtual->tipo === 'video' && $aulaAtual->url_conteudo)
                                <video src="{{ $aulaAtual->url_conteudo }}" controls class="w-100 h-100"></video>
                            @elseif($aulaAtual->tipo === 'pdf' && $aulaAtual->url_conteudo)
                                <iframe src="{{ $aulaAtual->url_conteudo }}" title="{{ $aulaAtual->titulo }}"></iframe>
                            @elseif($aulaAtual->tipo === 'texto')
                                <div class="d-flex align-items-center justify-content-center bg-white p-4">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-card-text display-4 d-block mb-3"></i>
                                        <p class="mb-0">{{ $aulaAtual->descricao ?: 'Conteudo textual ainda nao disponivel.' }}</p>
                                    </div>
                                </div>
                            @elseif($aulaAtual->tipo === 'quiz')
                                <div class="d-flex align-items-center justify-content-center bg-white p-4">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-ui-checks display-4 d-block mb-3"></i>
                                        <p class="mb-0">Quiz da aula ainda nao configurado.</p>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center text-white-50">
                                    Conteudo da aula ainda nao disponivel.
                                </div>
                            @endif
                        </div>

                        <div class="lesson-toolbar">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                <div class="d-flex flex-wrap gap-2">
                                    @if($aulaAnterior)
                                        <a href="{{ route('estudante.aula', [$matricula, $aulaAnterior]) }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Anterior
                                        </a>
                                    @endif

                                    @if($proximaAula)
                                        <a href="{{ route('estudante.aula', [$matricula, $proximaAula]) }}" class="btn btn-outline-primary">
                                            Proxima <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    @endif
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    @if($aulaAtual->arquivo_video && $aulaAtual->permite_download)
                                        <a href="{{ route('estudante.aula.download', [$matricula, $aulaAtual]) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-download me-1"></i> Download
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('estudante.aula.concluir', [$matricula, $aulaAtual]) }}">
                                        @csrf
                                        <button class="btn btn-success" @disabled($aulaConcluida)>
                                            <i class="bi bi-check2-circle me-1"></i>
                                            {{ $aulaConcluida ? 'Aula concluida' : 'Marcar concluida' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-journal-x display-4 d-block mb-3"></i>
                            <h2 class="h5">Este curso ainda nao tem aulas cadastradas.</h2>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-4">
                <div class="sticky-learning-panel">
                    <div class="lesson-panel p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 fw-bold mb-0">Plano de estudo</h3>
                            @if($matricula->progresso >= 100)
                                <a href="{{ route('estudante.certificado', $matricula) }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-award me-1"></i> Certificado
                                </a>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="search" id="lesson-search" class="form-control" placeholder="Pesquisar aula">
                        </div>

                        <div class="btn-group w-100 mb-3" role="group" aria-label="Filtro de aulas">
                            <button type="button" class="btn btn-outline-secondary active" data-filter="all">Todas</button>
                            <button type="button" class="btn btn-outline-secondary" data-filter="pending">Pendentes</button>
                            <button type="button" class="btn btn-outline-secondary" data-filter="complete">Concluidas</button>
                        </div>

                        <div class="lesson-list d-grid gap-2" id="lesson-list">
                            @foreach($aulas as $aula)
                                @php
                                    $concluida = in_array($aula->id, $aulasConcluidas);
                                    $ativa = $aulaAtual && $aulaAtual->id === $aula->id;
                                @endphp
                                <a
                                    href="{{ route('estudante.aula', [$matricula, $aula]) }}"
                                    class="lesson-item {{ $ativa ? 'is-active' : '' }} {{ $concluida ? 'is-complete' : '' }}"
                                    data-status="{{ $concluida ? 'complete' : 'pending' }}"
                                    data-title="{{ Str::lower($aula->titulo) }}"
                                >
                                    <div class="d-flex justify-content-between gap-2">
                                        <div class="d-flex gap-2">
                                            <span class="badge rounded-pill text-bg-light align-self-start">{{ $aula->numero_aula }}</span>
                                            <div>
                                                <div class="fw-semibold">{{ $aula->titulo }}</div>
                                                <div class="lesson-meta">
                                                    <i class="bi {{ $tipoIcones[$aula->tipo] ?? 'bi-journal-text' }} me-1"></i>
                                                    {{ ucfirst($aula->tipo) }} · {{ $aula->duracao_minutos }} min
                                                </div>
                                            </div>
                                        </div>

                                        @if($concluida)
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        @elseif($ativa)
                                            <i class="bi bi-play-circle-fill text-primary"></i>
                                        @else
                                            <i class="bi bi-circle text-muted"></i>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="text-center text-muted small py-3 d-none" id="lesson-empty">
                            Nenhuma aula encontrada.
                        </div>
                    </div>

                    <div class="lesson-panel p-3">
                        <h3 class="h6 fw-bold mb-3">Resumo rapido</h3>
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Concluidas</span>
                            <strong>{{ $totalConcluidas }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Pendentes</span>
                            <strong>{{ max($totalAulas - $totalConcluidas, 0) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span>Tempo total</span>
                            <strong>{{ $tempoTotal }} min</strong>
                        </div>
                    </div>

                    <div class="lesson-panel p-3 mt-3">
                        @if($matricula->progresso >= 50)
                            @include('components.course-rating-form', ['curso' => $matricula->curso, 'minhaAvaliacao' => $minhaAvaliacao])
                        @else
                            <h3 class="h6 fw-bold mb-2">Avaliar curso</h3>
                            <p class="text-muted small mb-0">Conclua pelo menos 50% do curso para avaliar.</p>
                        @endif
                    </div>

                    <div class="lesson-panel p-3 mt-3">
                        <h3 class="h6 fw-bold mb-3">Comentarios do curso</h3>
                        <div class="student-comment-list">
                            @forelse($comentariosCurso as $comentario)
                                <article class="student-comment">
                                    <div class="d-flex justify-content-between gap-2 mb-2">
                                        <strong class="small">{{ $comentario->estudante?->pessoa?->primeironome ?? 'Aluno' }}</strong>
                                        <span class="text-muted small">{{ $comentario->created_at?->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="student-comment-stars" aria-label="{{ $comentario->nota }} de 5 estrelas">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $comentario->nota ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                        <span class="text-muted ms-1">{{ $comentario->nota }}/5</span>
                                    </div>
                                    <p class="small text-muted mt-2 mb-0">{{ $comentario->comentario }}</p>
                                    @if($comentario->resposta_instrutor)
                                        <div class="student-comment-reply">
                                            <strong class="d-block mb-1">Resposta do formador</strong>
                                            {{ $comentario->resposta_instrutor }}
                                        </div>
                                    @endif
                                </article>
                            @empty
                                <p class="text-muted small mb-0">Ainda nao ha comentarios neste curso.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('lesson-search');
    const items = Array.from(document.querySelectorAll('.lesson-item'));
    const empty = document.getElementById('lesson-empty');
    const filters = Array.from(document.querySelectorAll('[data-filter]'));
    let activeFilter = 'all';

    function applyLessonFilters() {
        const term = (search?.value || '').trim().toLowerCase();
        let visible = 0;

        items.forEach(function (item) {
            const matchesText = item.dataset.title.includes(term);
            const matchesFilter = activeFilter === 'all' || item.dataset.status === activeFilter;
            const shouldShow = matchesText && matchesFilter;

            item.classList.toggle('d-none', !shouldShow);
            if (shouldShow) {
                visible++;
            }
        });

        empty?.classList.toggle('d-none', visible > 0);
    }

    filters.forEach(function (button) {
        button.addEventListener('click', function () {
            filters.forEach((filter) => filter.classList.remove('active'));
            button.classList.add('active');
            activeFilter = button.dataset.filter;
            applyLessonFilters();
        });
    });

    search?.addEventListener('input', applyLessonFilters);
});
</script>
@endsection
