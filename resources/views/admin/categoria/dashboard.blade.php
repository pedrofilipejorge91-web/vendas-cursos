@extends('admin-layouts.apps')

@section('content')
<style>
    /* Estilização Profissional Adicional */
    .category-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .img-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px 8px 0 0;
    }
    .category-img {
        transition: transform 0.5s ease;
        height: 160px;
        object-fit: cover;
    }
    .category-card:hover .category-img {
        transform: scale(1.1);
    }
    .badge-ref {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        font-weight: 600;
        backdrop-filter: blur(4px);
    }
    .search-box {
        position: relative;
    }
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
    }
    .search-box input {
        padding-left: 40px;
        border-radius: 20px;
        border: 1px solid #dee2e6;
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        background: #f8f9fa;
        border-radius: 15px;
        border: 2px dashed #dee2e6;
    }
</style>

<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Gestão de Categorias</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categorias</li>
                </ol>
            </nav>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#basicModal">
            <i class="bi bi-plus-lg me-1"></i> Nova Categoria
        </button>
    </div>
</div>

@include('admin.categoria.create')

<section class="section mt-4">
    <!-- Barra de Ferramentas -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchCategoria" class="form-control" placeholder="Procurar por nome da categoria...">
                    </div>
                </div>
                <div class="col-md-8 text-md-end">
                    <span class="text-muted small">Total: <strong>{{ $categorias->count() }}</strong> categorias registradas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Categorias -->
    <div class="row g-4" id="categoriesGrid">
        @forelse($categorias as $categoria)
            @php
                $imgUrl = $categoria->imagem ? asset('storage/categorias/' . $categoria->imagem) : asset('assets/img/logo.png');
                $cursoCount = $categoria->cursos->count();
                $ref = 'CAT-' . str_pad($categoria->id, 3, '0', STR_PAD_LEFT);
            @endphp

            <div class="col-xl-3 col-lg-4 col-md-6 category-item">
                <div class="card h-100 category-card">
                    <div class="img-container">
                        <img src="{{ $imgUrl }}" class="card-img-top category-img" alt="{{ $categoria->nome }}">
                        <span class="badge badge-ref shadow-sm">{{ $ref }}</span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <h5 class="card-title mb-0 pb-1 text-truncate" title="{{ $categoria->nome }}">
                                {{ $categoria->nome }}
                            </h5>
                            <span class="badge bg-light text-primary border">
                                <i class="bi bi-book me-1"></i> {{ $cursoCount }} {{ Str::plural('Curso', $cursoCount) }}
                            </span>
                        </div>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light flex-grow-1 border" 
                                        data-bs-toggle="modal" data-bs-target="#details{{ $categoria->id }}">
                                    <i class="bi bi-eye text-primary"></i> Detalhes
                                </button>
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" data-bs-target="#edit-{{ $categoria->id }}" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" data-bs-target="#delete-{{ $categoria->id }}" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modais permanecem dentro do loop para manter a lógica do ID --}}
                @include('admin.categoria.update')
                @include('admin.categoria.delete')
                @include('admin.categoria.show')
            </div>

        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-folder2-open display-4 text-muted"></i>
                    <h5 class="mt-3">Nenhuma categoria encontrada</h5>
                    <p class="text-muted">Comece criando uma nova categoria para organizar seus cursos.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Feedback Pesquisa Vazia -->
    <div id="noResults" class="col-12 d-none mt-4">
        <div class="alert alert-light text-center border">
            <i class="bi bi-search me-2"></i> Nenhum resultado encontrado para sua pesquisa.
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("searchCategoria");
    const items = document.querySelectorAll(".category-item");
    const noResults = document.getElementById("noResults");

    input.addEventListener("input", function () {
        let value = this.value.toLowerCase().trim();
        let hasResults = false;

        items.forEach(item => {
            let title = item.querySelector("h5").innerText.toLowerCase();
            
            if (title.includes(value)) {
                item.classList.remove('d-none');
                hasResults = true;
            } else {
                item.classList.add('d-none');
            }
        });

        // Exibe mensagem se nada for encontrado
        noResults.classList.toggle('d-none', hasResults || value === "");
    });
});
</script>
@endsection