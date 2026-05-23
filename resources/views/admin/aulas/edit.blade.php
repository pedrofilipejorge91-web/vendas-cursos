
<!-- Modal Create Curso -->
<div class="modal fade" id="edit-{{$aula->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">          
            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nova Aula
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>          
<form action="{{ Auth::user()?->tipo === 'formador' ? route('formador.aulas.update', $aula->id) : route('aulas.update', $aula->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    <div class="modal-body">
                        <!-- Área de Erros Gerais -->
                        <div id="modalErrors" class="alert alert-danger d-none"></div>
                                <select name="curso_id" id="curso_id" class="form-select" required>
                                <option value="">Selecione...</option>
                                @foreach($cursos as $curso)
                                    <option 
                                        value="{{ $curso->id }}" 
                                       {{ old('curso_id', $aula->curso_id) == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->titulo }} 
                                    </option>
                                @endforeach
                            </select>

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control"
                            value="{{ old('titulo', $aula->titulo) }}" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select name="tipo" class="form-select" required>
                                    <option value="video" {{ old('tipo', $aula->tipo) == 'video' ? 'selected' : '' }}>Vídeo</option>
                                    <option value="pdf" {{ old('tipo', $aula->tipo) == 'pdf' ? 'selected' : '' }}>PDF</option>
</select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="numero_aula" class="form-label">Nº Aula</label>
                                <input type="number" name="numero_aula" class="form-control" 
                                  value="{{ old('numero_aula', $aula->numero_aula) }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="duracao_minutos" class="form-label">Duração (min)</label>
                            <input type="number" name="duracao_minutos" class="form-control" 
                            value="{{ old('duracao_minutos', $aula->duracao_minutos) }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-2">
                            <label for="url_conteudo" class="form-label">URL Conteúdo</label>
                            <input type="url" name="url_conteudo" class="form-control" placeholder="https://..."
                              value="{{ old('url_conteudo', $aula->url_conteudo) }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-2">
                            <label for="arquivo_video" class="form-label">Substituir arquivo local</label>
                            <input type="file" name="arquivo_video" class="form-control" accept="video/*,application/pdf">
                        </div>

                        <div class="mb-2 form-check">
                            <input class="form-check-input" type="checkbox" name="permite_download" value="1" id="permite_download_{{ $aula->id }}" @checked(old('permite_download', $aula->permite_download ?? true))>
                            <label class="form-check-label" for="permite_download_{{ $aula->id }}">Permitir download offline deste material</label>
                        </div>

                        <div class="mb-2">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="3">
                                {{ old('descricao', $aula->descricao) }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
