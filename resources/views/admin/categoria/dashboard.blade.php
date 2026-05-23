@extends('admin-layouts.apps')
@section('content')

<div class="pagetitle">
    <h1>Gestão de Categorias</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Categorias</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

@include('admin.categoria.create')

<section class="section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0">Categorias de Cursos</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
            <i class="bi bi-plus me-1"></i> Criar Nova Categoria
        </button>
    </div>

    <div class="row g-3">
        @forelse($categorias as $categoria)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100">
                    @php
                        $img = $categoria->imagem ?? null;
                        $imgUrl = $img ? asset('storage/categorias/' . $img) : asset('assets/img/logo.png');
                        $cursoCount = $categoria->cursos->count();
                        $ref = 'CAT-' . str_pad($categoria->id, 3, '0', STR_PAD_LEFT);
                    @endphp
                    <img src="{{ $imgUrl }}" class="card-img-top" style="height:140px; object-fit:cover;" alt="{{ $categoria->nome }}">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $categoria->nome }}</h6>
                        <p class="text-muted small mb-1">Ref: {{ $ref }}</p>
                        <p class="text-muted small mb-2">{{ $cursoCount }} Curso{{ $cursoCount !== 1 ? 's' : '' }}</p>

                        <div class="progress mb-2" style="height:5px;">
                            <div class="progress-bar bg-primary" style="width: 50%"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('home.categorias', $categoria->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">Visualizar</a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#edit-{{ $categoria->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-{{ $categoria->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>

                @include('admin.categoria.update')
                @include('admin.categoria.delete')
                @include('admin.categoria.show')
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Ainda não há categorias cadastradas.</p>
            </div>
        @endforelse
    </div>

</section>

@endsection
