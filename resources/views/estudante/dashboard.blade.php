@extends('estudant-layouts.apps')

@section('content')
<div class="pagetitle">
    <h1>Painel do Aluno</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Página inicial</a></li>
            <li class="breadcrumb-item active">Meus estudos</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row g-4">
        <div class="col-12">
            <div class="card student-hero border-0 shadow-sm overflow-hidden">
                <div class="card-body p-4 p-lg-5 bg-primary text-white">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-8">
                            <p class="text-uppercase small fw-bold opacity-75 mb-2">Bem-vindo de volta</p>
                            <h2 class="fw-bold mb-3">{{ Auth::user()->name }}</h2>
                            <p class="mb-4 opacity-75">Continue os seus cursos, acompanhe o progresso e emita certificados quando concluir 100% do conteúdo.</p>
                            <a href="{{ route('home.catalogo') }}" class="btn btn-light fw-bold">
                                <i class="bi bi-search me-1"></i> Procurar novos cursos
                            </a>
                        </div>
                        <div class="col-lg-4">
                            @if($matriculaActual)
                                <div class="student-current-course rounded p-4">
                                    <p class="small text-uppercase fw-bold opacity-75 mb-2">Curso actual</p>
                                    <h5 class="fw-bold">{{ $matriculaActual->curso->titulo }}</h5>
                                    <div class="progress mt-3" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: {{ min($matriculaActual->progresso, 100) }}%"></div>
                                    </div>
                                    <p class="mt-2 mb-0 small">{{ number_format($matriculaActual->progresso, 0) }}% concluído</p>
                                </div>
                            @else
                                <div class="student-current-course rounded p-4">
                                    <h5 class="fw-bold">Ainda sem cursos</h5>
                                    <p class="mb-0 small opacity-75">Escolha um curso no catálogo para começar.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card info-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Cursos</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-mortarboard"></i></div>
                        <div class="ps-3"><h6>{{ $metricas['cursos'] }}</h6><span class="text-muted small">matriculados</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card info-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Andamento</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-play-circle"></i></div>
                        <div class="ps-3"><h6>{{ $metricas['em_andamento'] }}</h6><span class="text-muted small">em curso</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card info-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Concluídos</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-check2-circle"></i></div>
                        <div class="ps-3"><h6>{{ $metricas['concluidos'] }}</h6><span class="text-muted small">finalizados</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card info-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Certificados</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-award"></i></div>
                        <div class="ps-3"><h6>{{ $metricas['certificados'] }}</h6><span class="text-muted small">emitidos</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Meus cursos</h5>
                        <a href="{{ route('estudante.cursos') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse($matriculas->take(5) as $matricula)
                            <a href="{{ route('estudante.curso', $matricula) }}" class="list-group-item list-group-item-action student-list-item px-3">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $matricula->curso->titulo }}</h6>
                                        <p class="mb-2 small text-muted">{{ $matricula->curso->categoria->nome ?? 'Curso' }} · {{ $matricula->curso->aulas->count() }} aula(s)</p>
                                        <div class="progress" style="height: 7px;">
                                            <div class="progress-bar" style="width: {{ min($matricula->progresso, 100) }}%"></div>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-primary">{{ number_format($matricula->progresso, 0) }}%</span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-journal-bookmark display-5 text-muted"></i>
                                <p class="text-muted mt-3">Você ainda não tem cursos activos.</p>
                                <a href="{{ route('home.catalogo') }}" class="btn btn-primary">Explorar catálogo</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Próxima aula</h5>
                    @if($matriculaActual && $proximaAula)
                        <h6 class="fw-bold">{{ $proximaAula->titulo }}</h6>
                        <p class="text-muted small">{{ $matriculaActual->curso->titulo }}</p>
                        <p class="small mb-4">{{ $proximaAula->tipo }} · {{ $proximaAula->duracao_minutos }} min</p>
                        <a href="{{ route('estudante.aula', [$matriculaActual, $proximaAula]) }}" class="btn btn-primary w-100">
                            Continuar aula
                        </a>
                    @else
                        <p class="text-muted">Nenhuma aula disponível no momento.</p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Recomendados</h5>
                    @forelse($cursosRecomendados as $curso)
                        <a href="{{ route('home.detalhe', $curso->id) }}" class="d-block border-bottom py-3 text-decoration-none">
                            <strong>{{ $curso->titulo }}</strong>
                            <div class="small text-muted">{{ number_format($curso->preco, 2, ',', '.') }} Kz · {{ $curso->duracao_horas }}h</div>
                        </a>
                    @empty
                        <p class="text-muted">Sem recomendações por agora.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
