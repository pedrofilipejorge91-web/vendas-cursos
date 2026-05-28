@extends('estudant-layouts.apps')

@section('content')
<div class="pagetitle">
    <h1>Cursos disponíveis</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Painel</a></li>
            <li class="breadcrumb-item active">Catálogo</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row g-4">
        @forelse($cursos as $curso)
            <div class="col-md-6 col-xl-4">
                <div class="card student-course-card h-100 border-0 shadow-sm">
                    <img src="{{ $curso->foto ? Storage::url($curso->foto) : asset('assets/img/logo.png') }}" class="card-img-top" alt="{{ $curso->titulo }}">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary align-self-start mb-3">{{ $curso->categoria->nome ?? 'Curso' }}</span>
                        <h5 class="card-title p-0 mb-2">{{ $curso->titulo }}</h5>
                        <p class="text-muted small flex-grow-1">{{ Str::limit($curso->descricao, 110) }}</p>
                        <div class="d-flex justify-content-between align-items-center small mb-3">
                            <span><i class="bi bi-clock me-1"></i>{{ $curso->duracao_horas ?? 0 }}h</span>
                            <strong>{{ number_format($curso->preco, 2, ',', '.') }} Kz</strong>
                        </div>
                        <a href="{{ route('home.detalhe', $curso->id) }}" class="btn btn-primary">
                            Ver detalhes
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-journal-x display-5 text-muted"></i>
                        <h5 class="mt-3">Ainda não temos cursos disponíveis.</h5>
                        <p class="text-muted">Explore o catálogo principal para ver novas formações.</p>
                        <a href="{{ route('home.catalogo') }}" class="btn btn-primary">Abrir catálogo</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
