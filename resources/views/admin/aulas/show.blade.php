@extends(Auth::user()?->tipo === 'formador' ? 'formador-layouts.apps' : 'admin-layouts.apps')

@section('content')
@php
    $voltarRoute = Auth::user()?->tipo === 'formador' ? route('formador.aulas') : route('aula.index');
    $conteudoUrl = $aula->url_conteudo ?: ($aula->arquivo_video ? Storage::url($aula->arquivo_video) : null);
    $tipoLabel = [
        'video' => 'Vídeo',
        'pdf' => 'PDF',
        'quiz' => 'Quiz',
        'texto' => 'Texto',
    ][$aula->tipo] ?? ucfirst($aula->tipo);
    $tipoIcone = [
        'video' => 'bi-play-circle-fill',
        'pdf' => 'bi-file-earmark-pdf-fill',
        'quiz' => 'bi-ui-checks',
        'texto' => 'bi-card-text',
    ][$aula->tipo] ?? 'bi-journal-text';
@endphp

<div class="pagetitle d-flex justify-content-between align-items-center">
    <div>
        <h1>Detalhes da Aula</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ Auth::user()?->tipo === 'formador' ? route('formador.dashboard') : route('admin.dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ $voltarRoute }}">Aulas</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($aula->titulo, 38) }}</li>
            </ol>
        </nav>
    </div>

    <a href="{{ $voltarRoute }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Voltar
    </a>
</div>

<section class="section">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">
                                <i class="bi {{ $tipoIcone }} me-1"></i> {{ $tipoLabel }}
                            </span>
                            <h2 class="h4 fw-bold mb-2">{{ $aula->titulo }}</h2>
                            <p class="text-muted mb-0">{{ $aula->descricao ?: 'Esta aula ainda não tem descrição.' }}</p>
                        </div>

                        <span class="badge bg-light text-dark">
                            Aula nº {{ $aula->numero_aula }}
                        </span>
                    </div>

                    <div class="ratio ratio-16x9 rounded overflow-hidden bg-dark">
                        @if($aula->tipo === 'video' && $conteudoUrl)
                            <video src="{{ $conteudoUrl }}" controls class="w-100 h-100"></video>
                        @elseif($aula->tipo === 'pdf' && $conteudoUrl)
                            <iframe src="{{ $conteudoUrl }}" title="{{ $aula->titulo }}" class="border-0"></iframe>
                        @elseif($aula->tipo === 'texto')
                            <div class="d-flex align-items-center justify-content-center bg-light p-4">
                                <div class="text-center text-muted">
                                    <i class="bi bi-card-text display-5 d-block mb-3"></i>
                                    <p class="mb-0">{{ $aula->descricao ?: 'Conteúdo textual ainda não disponível.' }}</p>
                                </div>
                            </div>
                        @elseif($aula->tipo === 'quiz')
                            <div class="d-flex align-items-center justify-content-center bg-light p-4">
                                <div class="text-center text-muted">
                                    <i class="bi bi-ui-checks display-5 d-block mb-3"></i>
                                    <p class="mb-0">Quiz ainda não configurado para esta aula.</p>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center text-white-50">
                                Conteúdo da aula ainda não disponível.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h6 fw-bold mb-3">Informações</h3>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Curso</span>
                        <strong class="text-end">{{ $aula->curso->titulo ?? 'Sem curso' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Tipo</span>
                        <strong>{{ $tipoLabel }}</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Duração</span>
                        <strong>{{ $aula->duracao_minutos }} min</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Download</span>
                        <strong>{{ $aula->permite_download ? 'Permitido' : 'Bloqueado' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Arquivo</span>
                        <strong>{{ $aula->arquivo_video ? 'Local' : ($aula->url_conteudo ? 'Link externo' : 'Não definido') }}</strong>
                    </div>

                    @if($conteudoUrl)
                        <a href="{{ $conteudoUrl }}" target="_blank" class="btn btn-outline-primary w-100 mt-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Abrir conteúdo
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
