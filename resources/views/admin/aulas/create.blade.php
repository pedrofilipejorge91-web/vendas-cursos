<!-- Modal Nova Aula -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="modalAulaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <!-- Header Estilizado -->
            <div class="modal-header bg-light border-bottom-0 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="bi bi-play-btn-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="modalAulaLabel">Nova Aula</h5>
                        <small class="text-muted">Adicione conteúdos ao seu curso</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ Auth::user()?->tipo === 'formador' ? route('formador.aulas.store') : route('aula.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  data-ajax="true" 
                  data-ajax-refresh="#aula-table-wrapper" 
                  data-ajax-reset="true">
                @csrf
                
                <div class="modal-body p-4">
                    <div class="row g-4">
                        
                        <!-- SEÇÃO: IDENTIFICAÇÃO -->
                        <div class="col-12">
                            <h6 class="text-primary fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Informações Básicas</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Curso de Destino</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-journal-bookmark text-primary"></i></span>
                                        <select name="curso_id" class="form-select border-start-0" required>
                                            <option value="">Selecione o curso...</option>
                                            @foreach($cursos as $curso)
                                                <option value="{{ $curso->id }}">{{ $curso->titulo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Título da Aula</label>
                                    <input type="text" name="titulo" class="form-control form-control-lg" placeholder="Ex: Introdução à Logística" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tipo de Conteúdo</label>
                                    <select name="tipo" id="tipo_create" class="form-select">
                                        <option value="video">🎥 Vídeo</option>
                                        <option value="pdf">📄 PDF / Documento</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Ordem (Nº Aula)</label>
                                    <input type="number" name="numero_aula" class="form-control" value="1" min="1">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Duração (min)</label>
                                    <div class="input-group">
                                        <input type="number" name="duracao_minutos" class="form-control" value="0">
                                        <span class="input-group-text bg-light"><i class="bi bi-clock"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-2 opacity-50">

                        <!-- SEÇÃO: CONTEÚDO -->
                        <div class="col-12">
                            <h6 class="text-primary fw-bold mb-3"><i class="bi bi-cloud-arrow-up me-2"></i>Upload de Conteúdo</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Link Externo</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-link-45deg"></i></span>
                                        <input type="text" name="url_conteudo" class="form-control border-start-0" placeholder="YouTube, Vimeo, Drive...">
                                    </div>
                                    <small class="text-muted mt-1 d-block">Cole a URL do vídeo ou documento externo.</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Arquivo Local</label>
                                    <input type="file" name="arquivo_video" class="form-control" accept="video/*,application/pdf">
                                    <small class="text-muted mt-1 d-block">Máximo recomendado: 50MB.</small>
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch p-3 bg-light rounded-3">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="permite_download" value="1" id="permite_download_create" checked>
                                        <label class="form-check-label fw-bold text-dark" for="permite_download_create">
                                            Permitir que os alunos façam download deste material
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEÇÃO: DESCRIÇÃO -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Descrição da Aula</label>
                            <textarea name="descricao" class="form-control" rows="3" placeholder="O que será abordado nesta aula?"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer com Botões Destacados -->
                <div class="modal-footer bg-light border-top-0 p-3">
                    <button type="button" class="btn btn-link text-muted fw-semibold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Salvar Aula
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Ajustes Finos */
    #basicModal .form-control:focus, 
    #basicModal .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }

    #basicModal .input-group-text {
        color: #6c757d;
        border-right: none;
    }

    #basicModal .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    #basicModal .form-switch .form-check-input {
        cursor: pointer;
        width: 2.5em;
    }
</style>