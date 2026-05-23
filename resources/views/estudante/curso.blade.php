@extends('estudant-layouts.apps')

@section('content')
@php
    $aulas = $matricula->curso->aulas->sortBy('numero_aula');
    $aulasConcluidas = $matricula->progressos->whereNotNull('concluido_em')->pluck('aula_id')->all();
@endphp

<div class="pagetitle">
    <h1>{{ $matricula->curso->titulo }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('estudante.cursos') }}">Meus cursos</a></li>
            <li class="breadcrumb-item active">Aulas</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($aulaAtual)
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <span class="badge bg-primary mb-2">{{ ucfirst($aulaAtual->tipo) }}</span>
                                <h4 class="fw-bold">{{ $aulaAtual->titulo }}</h4>
                                <p class="text-muted mb-0">{{ $aulaAtual->descricao }}</p>
                            </div>
                            <span class="text-muted small">{{ $aulaAtual->duracao_minutos }} min</span>
                        </div>

                        <div class="ratio ratio-16x9 bg-light rounded overflow-hidden mb-4">
                            @if($aulaAtual->tipo === 'video' && $aulaAtual->url_conteudo)
                                <video src="{{ $aulaAtual->url_conteudo }}" controls class="w-100 h-100"></video>
                            @elseif($aulaAtual->tipo === 'pdf' && $aulaAtual->url_conteudo)
                                <iframe src="{{ $aulaAtual->url_conteudo }}" title="{{ $aulaAtual->titulo }}"></iframe>
                            @else
                                <div class="d-flex align-items-center justify-content-center text-muted">
                                    Conteúdo da aula ainda não disponível.
                                </div>
                            @endif
                        </div>

                        @if($aulaAtual->arquivo_video && $aulaAtual->permite_download)
                            <div class="mb-3 text-end">
                                <a href="{{ route('estudante.aula.download', [$matricula, $aulaAtual]) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-download me-1"></i> Download do material
                                </a>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('estudante.aula.concluir', [$matricula, $aulaAtual]) }}">
                            @csrf
                            <button class="btn btn-success" @disabled(in_array($aulaAtual->id, $aulasConcluidas))>
                                <i class="bi bi-check2-circle me-1"></i>
                                {{ in_array($aulaAtual->id, $aulasConcluidas) ? 'Aula concluída' : 'Marcar como concluída' }}
                            </button>
                        </form>
                    @else
                        <p class="text-muted">Este curso ainda não tem aulas cadastradas.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Progresso</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ number_format($matricula->progresso, 0) }}%</span>
                        <span>{{ count($aulasConcluidas) }}/{{ $aulas->count() }} aulas</span>
                    </div>
                    <div class="progress mb-4" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: {{ min($matricula->progresso, 100) }}%"></div>
                    </div>

                    @if($matricula->progresso >= 100)
                        <a href="{{ route('estudante.certificado', $matricula) }}" class="btn btn-outline-success w-100 mb-3">
                            Emitir certificado
                        </a>
                    @endif

                    <div class="list-group">
                        @foreach($aulas as $aula)
                            <a href="{{ route('estudante.aula', [$matricula, $aula]) }}" class="list-group-item list-group-item-action {{ $aulaAtual && $aulaAtual->id === $aula->id ? 'active' : '' }}">
                                <div class="d-flex justify-content-between gap-2">
                                    <span>{{ $aula->numero_aula }}. {{ $aula->titulo }}</span>
                                    @if(in_array($aula->id, $aulasConcluidas))
                                        <i class="bi bi-check-circle-fill"></i>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
