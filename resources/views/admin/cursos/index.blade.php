@extends(Auth::user()?->tipo === 'formador' ? 'formador-layouts.apps' : 'admin-layouts.apps')

@section('content')

<div class="pagetitle d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Gestão de Cursos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ Auth::user()?->tipo === 'formador' ? route('formador.dashboard') : route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Cursos</li>
            </ol>
        </nav>
    </div>
    <!-- Botão Principal no Topo -->
    <button type="button" class="btn btn-primary px-4 py-2 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#basicModal">
        <i class="bi bi-plus-lg me-1"></i> Novo Curso
    </button>
</div>

@include('admin.cursos.create')

<section class="section dashboard">
    <div class="row">
        
        <!-- Cards de Resumo (Opcional - Melhora o visual do Dashboard) -->
        <div class="col-12 mb-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card info-card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted small uppercase">Total Cursos</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-mortarboard"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 class="mb-0">{{ count($cursos) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela Principal -->
        <div class="col-12">
            <div class="card border-0 shadow-sm" id="curso-table-wrapper">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title p-0 m-0 text-dark fw-bold">Catálogo de Cursos</h5>
                </div>
                
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">Capa</th>
                                    <th class="border-0">Curso</th>
                                    <th class="border-0">Categoria</th>
                                    <th class="border-0">Formador</th>
                                    <th class="border-0">Investimento</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 text-center rounded-end">Acções</th>
                                </tr>
                            </thead>
                            <tbody>     
                                @foreach($cursos as $curso)
                                    <tr>
                                        <!-- Capa -->
                                        <td>
                                            @if($curso->foto && Storage::disk('public')->exists($curso->foto))
                                                <img src="{{ Storage::url($curso->foto) }}" 
                                                     alt="{{ $curso->titulo }}" 
                                                     style="width: 50px; height: 50px; object-fit: cover;"
                                                     class="rounded-3 shadow-sm border">
                                            @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border shadow-sm" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Título e Duração -->
                                        <td>
                                            <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">{{ $curso->titulo }}</div>
                                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $curso->duracao_horas }}h • {{ strtoupper($curso->idioma) }}</small>
                                        </td>

                                        <!-- Categoria -->
                                        <td>
                                            <span class="badge bg-light text-secondary border">
                                                {{ $curso->categoria?->nome ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <!-- Formador -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-circle me-2 text-primary"></i>
                                                <span class="small fw-semibold text-muted">
                                                    {{ $curso->formador?->pessoa?->primeironome ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Preço -->
                                        <td>
                                            <span class="fw-bold text-primary">
                                                {{ number_format($curso->preco, 2, ',', '.') }} Kz
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td>
                                            @php
                                                $statusInfo = [
                                                    'publicado' => ['class' => 'bg-success', 'icon' => 'bi-check-circle'],
                                                    'rascunho'  => ['class' => 'bg-warning text-dark', 'icon' => 'bi-pencil'],
                                                    'inativo'   => ['class' => 'bg-danger', 'icon' => 'bi-x-circle'],
                                                ][$curso->status] ?? ['class' => 'bg-secondary', 'icon' => 'bi-question'];
                                            @endphp
                                            <span class="badge {{ $statusInfo['class'] }} bg-opacity-10 text-{{ str_replace(' text-dark', '', $statusInfo['class']) }} px-3 py-2 rounded-pill small">
                                                <i class="bi {{ $statusInfo['icon'] }} me-1"></i> {{ ucfirst($curso->status) }}
                                            </span>
                                        </td>

                                        <!-- Ações -->
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <!-- Detalhes -->
                                                <button class="btn btn-light btn-sm text-primary border shadow-sm" data-bs-toggle="modal" data-bs-target="#details-{{$curso->id}}" title="Ver Detalhes">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                <!-- Editar -->
                                                <button class="btn btn-light btn-sm text-warning border shadow-sm" data-bs-toggle="modal" data-bs-target="#edit-{{$curso->id}}" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                
                                                <!-- Eliminar -->
                                                <button class="btn btn-light btn-sm text-danger border shadow-sm" data-bs-toggle="modal" data-bs-target="#delete-{{$curso->id}}" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <!-- Aprovação Admin -->
                                                @if(Auth::user()?->tipo === 'admin')
                                                    @if($curso->status !== 'publicado')
                                                        <form action="{{ route('curso.publicar', $curso->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button class="btn btn-success btn-sm shadow-sm" title="Aprovar/Publicar">
                                                                <i class="bi bi-check2"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('curso.rejeitar', $curso->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button class="btn btn-warning btn-sm shadow-sm" title="Rejeitar/Ocultar">
                                                                <i class="bi bi-slash-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modais -->
                                    @include('admin.cursos.edit')
                                    @include('admin.cursos.delete')
                                    @include('admin.cursos.detalhes')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<style>
    /* Customização do Dashboard de Cursos */
    .datatable thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #555;
        padding: 15px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.02);
    }

    .card-icon {
        width: 42px;
        height: 42px;
        font-size: 1.2rem;
    }

    /* Ajuste fino nos botões de ação */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .btn-light {
        background-color: #fff;
    }

    .btn-light:hover {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
</style>

@endsection