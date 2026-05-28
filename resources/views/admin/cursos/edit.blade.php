<!-- Modal Editar Curso -->
<div class="modal fade" id="edit-{{$curso->id}}" tabindex="-1" aria-labelledby="editCursoLabel-{{$curso->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            
            <!-- Header Estilizado (Cor de Edição) -->
            <div class="modal-header bg-warning py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-pencil-square fs-4 text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="editCursoLabel-{{$curso->id}}">Editar Curso</h5>
                        <small class="text-white-50">ID do Registro: #{{ $curso->id }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form action="{{ Auth::user()?->tipo === 'formador' ? route('formador.cursos.update', $curso->id) : route('curso.update', $curso->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  data-ajax="true" 
                  data-ajax-refresh="#curso-table-wrapper">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-4">
                    
                    <!-- Erros de Validação -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-octagon me-2 fs-5"></i>
                                <strong class="small">Corrija os erros destacados nos campos.</strong>
                            </div>
                        </div>
                    @endif

                    <div class="row g-4">
                        
                        <!-- SEÇÃO 1: IDENTIFICAÇÃO -->
                        <div class="col-12">
                            <h6 class="text-warning fw-bold text-uppercase small mb-3"><i class="bi bi-tag me-2"></i>Identificação</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control border-0 bg-light" id="editTitulo-{{$curso->id}}" name="titulo" placeholder="Título" value="{{ old('titulo', $curso->titulo) }}" required>
                                        <label for="editTitulo-{{$curso->id}}">Título do Curso *</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select border-0 bg-light" name="categoria_id" required>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $curso->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label>Categoria</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select border-0 bg-light" name="idioma" required>
                                            <option value="pt-AO" {{ old('idioma', $curso->idioma) === 'pt-AO' ? 'selected' : '' }}>Português (Angola)</option>
                                            <option value="pt-PT" {{ old('idioma', $curso->idioma) === 'pt-PT' ? 'selected' : '' }}>Português (Portugal)</option>
                                            <option value="en" {{ old('idioma', $curso->idioma) === 'en' ? 'selected' : '' }}>Inglês</option>
                                            <option value="fr" {{ old('idioma', $curso->idioma) === 'fr' ? 'selected' : '' }}>Francês</option>
                                        </select>
                                        <label>Idioma</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEÇÃO 2: VALORES E RESPONSABILIDADE -->
                        <div class="col-12">
                            <h6 class="text-warning fw-bold text-uppercase small mb-3"><i class="bi bi-cash-coin me-2"></i>Configurações</h6>
                            <div class="row g-3 p-3 bg-light rounded-3 mx-0 shadow-sm border-start border-warning border-4">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Preço (AOA)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0 fw-bold">Kz</span>
                                        <input type="number" name="preco" class="form-control border-0" step="0.01" value="{{ old('preco', $curso->preco) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Duração (Horas)</label>
                                    <div class="input-group">
                                        <input type="number" name="duracao_horas" class="form-control border-0 text-center" value="{{ old('duracao_horas', $curso->duracao_horas) }}" required>
                                        <span class="input-group-text bg-white border-0 small">Hrs</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Status do Curso</label>
                                    <select class="form-select border-0" name="status" required>
                                        <option value="rascunho" {{ old('status', $curso->status) == 'rascunho' ? 'selected' : '' }}>📝 Rascunho</option>
                                        <option value="publicado" {{ old('status', $curso->status) == 'publicado' ? 'selected' : '' }}>🚀 Publicado</option>
                                        <option value="inativo" {{ old('status', $curso->status) == 'inativo' ? 'selected' : '' }}>🔒 Inativo</option>
                                    </select>
                                </div>

                                @if(Auth::user()?->tipo !== 'formador')
                                <div class="col-12 mt-3 border-top pt-2">
                                    <label class="form-label small fw-bold">Formador Designado</label>
                                    <select class="form-select border-0 bg-white shadow-sm" name="formador_id" required>
                                        @foreach($formadores as $formador)
                                            <option value="{{ $formador->id }}" {{ old('formador_id', $curso->formador_id) == $formador->id ? 'selected' : '' }}>
                                                {{ $formador->pessoa->primeironome ?? $formador->primeironome }} {{ $formador->pessoa->segundonome ?? $formador->segundonome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- SEÇÃO 3: DESCRIÇÃO E IMAGEM -->
                        <div class="col-12">
                            <h6 class="text-warning fw-bold text-uppercase small mb-3"><i class="bi bi-card-image me-2"></i>Apresentação Visual</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control border-0 bg-light" id="editDesc-{{$curso->id}}" name="descricao" style="height: 100px" required>{{ old('descricao', $curso->descricao) }}</textarea>
                                        <label for="editDesc-{{$curso->id}}">Descrição Completa do Curso *</label>
                                    </div>
                                </div>

                                <!-- Preview da Imagem Atual -->
                                <div class="col-md-4">
                                    <div class="p-2 border rounded text-center bg-white">
                                        <small class="text-muted d-block mb-2 small fw-bold">Capa Atual</small>
                                        <img src="{{ $curso->foto ? Storage::url($curso->foto) : asset('assets/img/course-placeholder.jpg') }}" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 100px; width: 100%; object-fit: cover;">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label fw-bold small">Alterar Foto de Capa (Opcional)</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control border-0 bg-light" name="capa" accept="image/*">
                                        <span class="input-group-text bg-warning text-white border-0"><i class="bi bi-upload"></i></span>
                                    </div>
                                    <div class="form-text mt-1 small">Deixe em branco para manter a imagem atual.</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-top-0 bg-light py-3">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Descartar</button>
                    <button type="submit" class="btn btn-warning px-5 py-2 fw-bold text-dark shadow-sm">
                        <i class="bi bi-cloud-arrow-up me-2"></i>Actualizar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Ajustes específicos para o Modal de Edição */
    #edit-{{$curso->id}} .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    #edit-{{$curso->id}} .form-control:focus, 
    #edit-{{$curso->id}} .form-select:focus {
        background-color: #fff !important;
        border: 1px solid #ffc107 !important;
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.1) !important;
    }

    #edit-{{$curso->id}} .input-group-text {
        border-radius: 8px 0 0 8px;
    }

    /* Animação suave ao abrir */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }
</style>