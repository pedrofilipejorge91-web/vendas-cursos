<!-- Modal Editar Aula -->
<div class="modal fade" id="edit-{{$aula->id}}" tabindex="-1" aria-labelledby="editLabel-{{$aula->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            
            <!-- Header -->
            <div class="modal-header bg-light border-bottom-0 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="editLabel-{{$aula->id}}">Editar Aula</h5>
                        <small class="text-muted">ID da Aula: #{{ $aula->id }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ Auth::user()?->tipo === 'formador' ? route('formador.aulas.update', $aula->id) : route('aulas.update', $aula->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="needs-validation">
                @csrf
                @method('PUT')

                <div class="modal-body p-4">
                    <!-- Área de Erros -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        <!-- SEÇÃO 1: VÍNCULO E TÍTULO -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Curso Relacionado</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-bookmark-star"></i></span>
                                <select name="curso_id" class="form-select border-start-0" required>
                                    <option value="">Selecione o curso...</option>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ old('curso_id', $aula->curso_id) == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Título da Aula</label>
                            <input type="text" name="titulo" class="form-control form-control-lg shadow-sm" 
                                   value="{{ old('titulo', $aula->titulo) }}" required>
                        </div>

                        <!-- SEÇÃO 2: CONFIGURAÇÕES -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Tipo</label>
                            <select name="tipo" class="form-select">
                                <option value="video" {{ old('tipo', $aula->tipo) == 'video' ? 'selected' : '' }}>🎥 Vídeo</option>
                                <option value="pdf" {{ old('tipo', $aula->tipo) == 'pdf' ? 'selected' : '' }}>📄 PDF</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Ordem (Nº)</label>
                            <input type="number" name="numero_aula" class="form-control" 
                                   value="{{ old('numero_aula', $aula->numero_aula) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Duração (min)</label>
                            <div class="input-group">
                                <input type="number" name="duracao_minutos" class="form-control" 
                                       value="{{ old('duracao_minutos', $aula->duracao_minutos) }}">
                                <span class="input-group-text bg-light"><i class="bi bi-clock"></i></span>
                            </div>
                        </div>

                        <!-- SEÇÃO 3: CONTEÚDO (URL / ARQUIVO) -->
                        <div class="col-12 mt-4">
                            <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                                <h6 class="fw-bold mb-3 small text-uppercase text-primary">Conteúdo da Aula</h6>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small">URL do Conteúdo (YouTube/Vimeo/Drive)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="bi bi-link-45deg"></i></span>
                                            <input type="url" name="url_conteudo" class="form-control border-start-0" 
                                                   placeholder="https://..." value="{{ old('url_conteudo', $aula->url_conteudo) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">Substituir Arquivo Local</label>
                                        <input type="file" name="arquivo_video" class="form-control" accept="video/*,application/pdf">
                                        <div class="form-text mt-1">Deixe vazio para manter o arquivo atual.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEÇÃO 4: EXIBIÇÃO E DESCRIÇÃO -->
                        <div class="col-12">
                            <div class="form-check form-switch p-3 border rounded-3">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="permite_download" value="1" 
                                       id="permite_download_{{ $aula->id }}" @checked(old('permite_download', $aula->permite_download ?? true))>
                                <label class="form-check-label fw-bold" for="permite_download_{{ $aula->id }}">
                                    Permitir download offline deste material
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Descrição da Aula</label>
                            <textarea name="descricao" class="form-control" rows="3" 
                                      placeholder="Descreva o que será ensinado...">{{ old('descricao', $aula->descricao) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer bg-light border-top-0 p-3">
                    <button type="button" class="btn btn-link text-muted fw-semibold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-arrow-repeat me-1"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Estilos específicos para o Modal de Edição */
    #edit-{{$aula->id}} .form-control:focus, 
    #edit-{{$aula->id}} .form-select:focus {
        border-color: #ffc107; /* Cor de foco amber/amarela para indicar edição */
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.15);
    }

    #edit-{{$aula->id}} .input-group-text {
        border-right: none;
        color: #858796;
    }

    #edit-{{$aula->id}} .modal-content {
        border-radius: 12px;
    }

    #edit-{{$aula->id}} .form-check-input:checked {
        background-color: #198754; /* Verde para indicar que algo está ativo */
        border-color: #198754;
    }
</style>