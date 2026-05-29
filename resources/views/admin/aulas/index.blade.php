@extends(Auth::user()?->tipo === 'formador' ? 'formador-layouts.apps' : 'admin-layouts.apps')

@section('content')

<div class="pagetitle d-flex justify-content-between align-items-center">
    <div>
        <h1>Gestão de Aulas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ Auth::user()?->tipo === 'formador' ? route('formador.dashboard') : route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Cursos</li>
                <li class="breadcrumb-item active">Aulas</li>
            </ol>
        </nav>
    </div>
    <!-- Botão de Ação Principal no Topo -->
    <button type="button" class="btn btn-primary px-4 py-2 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#basicModal">
        <i class="bi bi-plus-lg me-1"></i> Nova Aula
    </button>
</div><!-- End Page Title -->

@include('admin.aulas.create')

<section class="section dashboard">
    <div class="row">
        
        <!-- Cards de Resumo Rápido (Opcional - Melhora muito o visual) -->
        <div class="col-12 mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card info-card sales-card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted small uppercase">Total de Aulas</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ count($aulas) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Você pode adicionar mais cards aqui se desejar (ex: total de vídeos, total de PDFs) -->
            </div>
        </div>

        <!-- Tabela Principal -->
        <div class="col-12">
            <div class="card border-0 shadow-sm" id="aula-table-wrapper">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title p-0 m-0 text-dark fw-bold">Lista de Conteúdos</h5>
                </div>
                
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">Título da Aula</th>
                                    <th class="border-0">Tipo</th>
                                    <th class="border-0">Curso Relacionado</th>
                                    <th class="border-0">Duração</th>
                                    <th class="border-0 text-center rounded-end">Acções</th>
                                </tr>
                            </thead>
                            <tbody>     
                                @forelse($aulas as $aula)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-3">
                                                    @if($aula->tipo == 'video')
                                                        <i class="bi bi-play-circle-fill text-primary"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                    @endif
                                                </div>
                                                <span class="fw-semibold text-dark">{{ $aula->titulo }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($aula->tipo == 'video')
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                    <i class="bi bi-camera-video me-1"></i> VÍDEO
                                                </span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                                                    <i class="bi bi-file-pdf me-1"></i> PDF
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted small">
                                                <i class="bi bi-folder2-open me-1"></i> {{ $aula->curso->titulo ?? 'Sem Curso' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark fw-normal">
                                                <i class="bi bi-clock me-1"></i> {{ $aula->duracao_minutos }} min
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Detalhes -->
                                                <a href="{{ Auth::user()?->tipo === 'formador' ? route('formador.aulas.show', $aula) : route('aulas.show', $aula) }}"
                                                   class="btn btn-light btn-sm text-primary shadow-sm border"
                                                   title="Ver Detalhes">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <!-- Editar -->
                                                <button type="button" class="btn btn-light btn-sm text-warning shadow-sm border" 
                                                        data-bs-toggle="modal" data-bs-target="#edit-{{$aula->id}}" title="Editar Aula">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                
                                                <!-- Eliminar -->
                                                <button type="button" class="btn btn-light btn-sm text-danger shadow-sm border" 
                                                        data-bs-toggle="modal" data-bs-target="#delete-{{$aula->id}}" title="Eliminar Aula">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modais Organizados -->
                                    @include('admin.aulas.edit')
                                    @include('admin.aulas.delete')

                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Nenhuma aula cadastrada até o momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<style>
    /* Customização da Tabela Datatable */
    .datatable {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .datatable thead th {
        background-color: #f8f9fa;
        color: #444;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 15px;
    }

    .datatable tbody tr {
        transition: transform 0.2s ease;
    }

    .datatable tbody tr:hover {
        background-color: #fcfcfc;
        transform: scale(1.002);
    }

    /* Estilização dos ícones das ações */
    .btn-light:hover {
        background-color: #fff !important;
        border-color: currentColor !important;
    }

    .card-icon {
        width: 48px;
        height: 48px;
    }
</style>

@endsection
