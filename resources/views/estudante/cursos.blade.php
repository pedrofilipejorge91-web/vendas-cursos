@extends('estudant-layouts.apps')

@section('content')
<div class="pagetitle">
    <h1>Meus Cursos</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Painel</a></li>
            <li class="breadcrumb-item active">Cursos</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row g-4">
        @forelse($matriculas as $matricula)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ $matricula->curso->foto ? Storage::url($matricula->curso->foto) : asset('assets/img/paruana.png') }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $matricula->curso->titulo }}">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary align-self-start mb-3">{{ $matricula->curso->categoria->nome ?? 'Curso' }}</span>
                        <h5 class="card-title p-0 mb-2">{{ $matricula->curso->titulo }}</h5>
                        <p class="text-muted small flex-grow-1">{{ Str::limit($matricula->curso->descricao, 100) }}</p>
                        <div class="d-flex justify-content-between small mb-2">
                            <span>{{ $matricula->curso->aulas->count() }} aula(s)</span>
                            <strong>{{ number_format($matricula->progresso, 0) }}%</strong>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ min($matricula->progresso, 100) }}%"></div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('estudante.curso', $matricula) }}" class="btn btn-primary">Continuar</a>
                            @if($matricula->progresso >= 100)
                                <a href="{{ route('estudante.certificado', $matricula) }}" class="btn btn-outline-success">Ver certificado</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-journal-x display-4 text-muted"></i>
                        <h5 class="mt-3">Nenhum curso comprado ainda</h5>
                        <p class="text-muted">Quando o pagamento for confirmado, os cursos aparecem aqui automaticamente.</p>
                        <a href="{{ route('home.catalogo') }}" class="btn btn-primary">Explorar catálogo</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
