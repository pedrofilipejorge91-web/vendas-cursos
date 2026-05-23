<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ Auth::user()?->tipo === 'formador' ? route('formador.aulas.store') : route('aula.store') }}" method="POST" enctype="multipart/form-data" data-ajax="true" data-ajax-refresh="#aula-table-wrapper" data-ajax-reset="true">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Nova Aula</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Curso</label>
                            <select name="curso_id" class="form-select" required>
                                <option value="">Selecione o curso</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->titulo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Título da Aula</label>
                            <input type="text" name="titulo" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" id="tipo_create" class="form-select">
                                <option value="video">Vídeo</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nº Aula</label>
                            <input type="number" name="numero_aula" class="form-control" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duração (min)</label>
                            <input type="number" name="duracao_minutos" class="form-control" value="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Link Externo (YouTube/Vimeo)</label>
                            <input type="text" name="url_conteudo" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ou Enviar Arquivo Local</label>
                            <input type="file" name="arquivo_video" class="form-control" accept="video/*,application/pdf">
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permite_download" value="1" id="permite_download_create" checked>
                                <label class="form-check-label" for="permite_download_create">Permitir download offline deste material</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Aula</button>
                </div>
            </form>
        </div>
    </div>
</div>
