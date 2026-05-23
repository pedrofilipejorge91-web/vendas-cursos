@extends('formador-layouts.apps')

@section('content')

<div class="container py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <small class="text-muted text-uppercase">Painel do Instrutor</small>
        <h2 class="fw-bold text-primary">
            Olá, {{ auth()->user()->name }}
        </h2>
    </div>

    <!-- BOTÃO CRIAR CURSO -->
    <div class="mb-4">
        <a href="{{ route('formador.cursos') }}" 
           class="btn btn-primary w-100 d-flex justify-content-between align-items-center py-3">
            
            <span>
                <i class="bi bi-plus-circle me-2"></i>
                Criar Novo Curso
            </span>

            <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <!-- ESTATÍSTICAS -->
    <div class="row g-3 mb-4">

        <!-- Cursos Ativos -->
        <div class="col-md-6">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h2 class="fw-bold text-primary">
                        {{ $cursosAtivos }}
                    </h2>
                    <p class="text-muted mb-0">Cursos Ativos</p>
                </div>
            </div>
        </div>

        <!-- Estudantes -->
        <div class="col-md-6">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h2 class="fw-bold text-primary">
                        {{ $totalAlunos }}
                    </h2>
                    <p class="text-muted mb-0">Estudantes</p>
                </div>
            </div>
        </div>

        <!-- Aproveitamento -->
        <div class="col-12">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase">Aproveitamento</small>
                        <h4 class="fw-bold">94.8%</h4>
                    </div>
                    <i class="bi bi-graph-up fs-3"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- CURSOS -->
    <div class="d-flex justify-content-between mb-3">
        <h4 class="fw-bold">Cursos Ativos</h4>
        <a href="#" class="text-decoration-none">Ver todos</a>
    </div>

    <div class="row g-3">

        @forelse($cursos as $curso)
        <div class="col-md-6">
            <div class="card shadow-sm h-100">

                <div class="row g-0 align-items-center">
                    
                    <!-- IMAGEM -->
                    <div class="col-4">
                        <img 
                            src="{{ $curso->foto ? asset('storage/'.$curso->foto) : asset('assets/img/paruana.png') }}" 
                            class="img-fluid rounded-start"
                            alt="Curso">
                    </div>

                    <!-- CONTEÚDO -->
                    <div class="col-8">
                        <div class="card-body">

                            <h6 class="fw-bold">
                                {{ $curso->titulo }}
                            </h6>

                            <p class="text-muted small mb-2">
                                {{ $curso->inscricoes->count() }} inscritos
                            </p>

                            <!-- PROGRESSO (EXEMPLO FIXO) -->
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-primary" style="width: 50%"></div>
                            </div>

                            <!-- AÇÕES -->
                            <div class="mt-2 d-flex gap-2">
                                <a href="{{ route('formador.cursos.edit', $curso->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                   Editar
                                </a>

                                <form action="{{ route('formador.cursos.destroy', $curso->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Excluir
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                Nenhum curso encontrado.
            </div>
        </div>
        @endforelse

    </div>

</div>

@endsection
