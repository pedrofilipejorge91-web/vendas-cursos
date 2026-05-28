<!-- Details Modal -->
<div class="modal fade" id="details{{ $categoria->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            
            <!-- Header Customizado -->
            <div class="modal-header bg-primary text-white p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-folder2-open text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalTitle{{ $categoria->id }}">
                            {{ $categoria->nome }}
                        </h5>
                        <small class="text-white-50">Detalhes completos da categoria e cursos vinculados</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="container-fluid p-0">
                    <div class="row g-0">
                        
                        <!-- BARRA LATERAL: INFO DA CATEGORIA -->
                        <div class="col-lg-4 bg-light p-4 border-end">
                            <div class="text-center mb-4">
                                @php
                                    $imgUrl = $categoria->imagem ? asset('storage/categorias/' . $categoria->imagem) : asset('assets/img/logo.png');
                                @endphp
                                <img src="{{ $imgUrl }}" class="img-fluid rounded-3 shadow-sm mb-3" style="max-height: 180px; width: 100%; object-fit: cover;">
                                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill">
                                    Ref: CAT-{{ str_pad($categoria->id, 3, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>

                            <div class="info-group mb-4">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Descrição</label>
                                <p class="text-dark small lh-base bg-white p-3 rounded border">
                                    {{ $categoria->descricao ?? 'Esta categoria ainda não possui uma descrição detalhada cadastrada.' }}
                                </p>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="bg-white p-3 rounded border text-center shadow-sm">
                                        <i class="bi bi-calendar3 text-primary d-block mb-1"></i>
                                        <span class="text-muted x-small d-block">Criada em</span>
                                        <strong class="small">{{ $categoria->created_at->format('d/m/Y') }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white p-3 rounded border text-center shadow-sm">
                                        <i class="bi bi-journal-bookmark text-success d-block mb-1"></i>
                                        <span class="text-muted x-small d-block">Total Cursos</span>
                                        <strong class="small">{{ $categoria->cursos->count() }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONTEÚDO PRINCIPAL: LISTA DE CURSOS -->
                        <div class="col-lg-8 p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold m-0 text-secondary">
                                    <i class="bi bi-list-stars me-1"></i> Cursos vinculados a esta categoria
                                </h6>
                                @if($categoria->cursos->count() > 0)
                                    <span class="badge bg-primary-light text-primary rounded-pill px-3">
                                        {{ $categoria->cursos->count() }} curso(s)
                                    </span>
                                @endif
                            </div>

                            <div class="course-list h-100">
                                @forelse($categoria->cursos as $curso)
                                    <div class="course-item d-flex align-items-center p-3 mb-3 border rounded-3 transition-hover shadow-sm-hover">
                                        <!-- Ícone/Avatar do Curso -->
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-light rounded p-2 border text-primary">
                                                <i class="bi bi-play-circle-fill fs-4"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Info do Curso -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-0 fw-bold">{{ $curso->titulo ?? $curso->nome }}</h6>
                                                <span class="badge bg-light text-success border border-success-subtle rounded-pill small">Ativo</span>
                                            </div>
                                            <p class="text-muted mb-0 small mt-1">
                                                {{ Str::limit($curso->descricao ?? 'Sem descrição detalhada disponível.', 90) }}
                                            </p>
                                        </div>

                                        <!-- Ação (Link para o curso se necessário) -->
                                        <div class="ms-3">
                                            <a href="{{ route('curso.index') }}" class="btn btn-sm btn-outline-light text-primary border-0 rounded-circle" title="Ver Curso">
                                                <i class="bi bi-chevron-right fs-5"></i>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="bi bi-folder-x display-1 text-light"></i>
                                        </div>
                                        <h6 class="text-muted">Nenhum curso associado</h6>
                                        <p class="small text-muted px-5">Esta categoria está vazia no momento. Os cursos vinculados aparecerão aqui automaticamente.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Rodapé do Modal -->
            <div class="modal-footer bg-light border-top-0 px-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Fechar Detalhes</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos Adicionais para o Modal de Detalhes */
    .x-small {
        font-size: 0.75rem;
    }
    
    .bg-primary-light {
        background-color: rgba(65, 84, 241, 0.1);
    }

    .course-item {
        transition: all 0.2s ease-in-out;
        background: #fff;
    }

    .course-item:hover {
        border-color: #4154f1 !important;
        background-color: #f8f9ff;
        transform: translateX(5px);
    }

    .shadow-sm-hover:hover {
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05) !important;
    }

    /* Scrollbar personalizada para a lista de cursos */
    .course-list {
        max-height: 450px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .course-list::-webkit-scrollbar {
        width: 4px;
    }
    .course-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .course-list::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
</style>
