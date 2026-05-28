@extends('estudant-layouts.apps')

@section('content')
@php
    $courseImage = $cursos->foto ? Storage::url($cursos->foto) : asset('assets/img/logo.png');
@endphp

<div class="pagetitle">
    <h1>{{ $cursos->titulo }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Painel</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home.catalogo') }}">Catálogo</a></li>
            <li class="breadcrumb-item active">Detalhes</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card student-hero border-0 shadow-sm overflow-hidden">
                <div class="card-body p-4 p-lg-5 text-white">
                    <span class="badge bg-light text-primary mb-3">{{ $cursos->categoria->nome ?? 'Curso' }}</span>
                    <h2 class="fw-bold mb-3">{{ $cursos->titulo }}</h2>
                    <p class="opacity-75 mb-4">{{ $cursos->descricao }}</p>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="student-current-course rounded p-3">
                                <strong>{{ $cursos->duracao_horas ?? 0 }}h</strong>
                                <small class="d-block opacity-75">Duração</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="student-current-course rounded p-3">
                                <strong>Certificado</strong>
                                <small class="d-block opacity-75">Incluído</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="student-current-course rounded p-3">
                                <strong>{{ $cursos->idioma ?? 'pt-AO' }}</strong>
                                <small class="d-block opacity-75">Idioma</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card student-course-card border-0 shadow-sm">
                <img src="{{ $courseImage }}" class="card-img-top" alt="{{ $cursos->titulo }}">
                <div class="card-body">
                    <h3 class="fw-bold text-primary mb-3">{{ number_format($cursos->preco, 2, ',', '.') }} Kz</h3>
                    <form action="{{ route('carrinho.add') }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-cart-plus me-1"></i> Adicionar ao carrinho
                        </button>
                    </form>
                    <form action="{{ route('carrinho.buy-now') }}" method="POST">
                        @csrf
                        <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            Comprar agora
                        </button>
                    </form>
                    <ul class="list-unstyled text-muted small mt-4 mb-0">
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-1"></i> Acesso ao conteúdo</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-1"></i> Certificado após aprovação</li>
                        <li><i class="bi bi-check2-circle text-success me-1"></i> Suporte do formador</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
