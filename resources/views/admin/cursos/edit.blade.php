        <!-- Modal Create Curso -->
<div class="modal fade" id="edit-{{$curso->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            
            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Novo Curso
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form action="{{ Auth::user()?->tipo === 'formador' ? route('formador.cursos.update', $curso->id) : route('curso.update', $curso->id) }}" method="POST" enctype="multipart/form-data" data-ajax="true" data-ajax-refresh="#curso-table-wrapper">
                @csrf
                  @method('PUT')
                
                <div class="modal-body">
                    
                    <!-- Mensagens de Erro -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Atenção!</strong> Verifique os campos abaixo:
                            <ul class="mb-0 mt-2 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row g-3">
                        
                        <!-- Título do Curso -->
                        <div class="col-md-8">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control @error('titulo') is-invalid @enderror" 
                                       id="floatingTitulo" 
                                       name="titulo" 
                                       placeholder="Ex: Introdução ao Laravel"
                                       value="{{ old('titulo', $curso->titulo) }}">
                                <label for="floatingTitulo">Título do Curso *</label>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Duração (Horas) -->
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" 
                                       class="form-control @error('duracao_horas') is-invalid @enderror" 
                                       id="floatingDuracao" 
                                       name="duracao_horas" 
                                       placeholder="Ex: 20"
                                       value="{{ old('duracao_horas', $curso->duracao_horas) }}"
                                       min="1"
                                       required>
                                <label for="floatingDuracao">Duração (horas) *</label>
                                @error('duracao_horas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" name="idioma" required>
                                    <option value="pt-AO" {{ old('idioma', $curso->idioma) === 'pt-AO' ? 'selected' : '' }}>Portugues de Angola</option>
                                    <option value="pt-PT" {{ old('idioma', $curso->idioma) === 'pt-PT' ? 'selected' : '' }}>Portugues</option>
                                    <option value="en" {{ old('idioma', $curso->idioma) === 'en' ? 'selected' : '' }}>Ingles</option>
                                    <option value="fr" {{ old('idioma', $curso->idioma) === 'fr' ? 'selected' : '' }}>Frances</option>
                                </select>
                                <label>Idioma *</label>
                            </div>
                        </div>

                        <!-- Descrição (Textarea) -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          placeholder="Descreva o conteúdo do curso..." 
                                          id="floatingDescricao" 
                                          name="descricao" 
                                          style="height: 120px"
                                          required>{{ old('descricao', $curso->descricao) }}</textarea>
                                <label for="floatingDescricao">Descrição do Curso *</label>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Preço -->
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" 
                                       class="form-control @error('preco') is-invalid @enderror" 
                                       id="floatingPreco" 
                                       name="preco" 
                                       placeholder="0.00"
                                      value="{{ old('preco', $curso->preco) }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                <label for="floatingPreco">Preço (Kz) *</label>
                                @error('preco')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="floatingStatus" 
                                        name="status"
                                        required>
                                     <option value="rascunho" {{ old('status', $curso->status) == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                                        <option value="publicado" {{ old('status', $curso->status) == 'publicado'? 'selected' : '' }}>Publicado</option>
                                        <option value="inativo" {{ old('status', $curso->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                <label for="floatingStatus">Status *</label>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <!-- Categoria -->
<select class="form-select" name="categoria_id" required>
    <option value="" selected disabled>Selecione...</option>
    @foreach($categorias as $categoria)
        <!-- ✅ USAR $categoria->id (não categoria_id) -->
        <option value="{{ $categoria->id }}" 
 {{ old('categoria_id', $curso->categoria_id) == $categoria->id ? 'selected' : '' }}>            {{ $categoria->nome }}
        </option>
    @endforeach
</select>

@if(Auth::user()?->tipo !== 'formador')
<!-- Formador -->
<select class="form-select" name="formador_id" required>
    <option value="" selected disabled>Selecione o formador</option>
    @foreach($formadores as $formador)
        <!-- ✅ USAR $formador->id (não formador_id) -->
        <option value="{{ $formador->id }}" 
        {{ old('formador_id', $curso->formador_id) == $formador->id ? 'selected' : '' }}>
            {{ $formador->pessoa->primeironome ?? $formador->primeironome }}
            {{ $formador->pessoa->segundonome ?? $formador->segundonome }}
        </option>
    @endforeach
</select>
@endif

                        <!-- Capa do Curso (Opcional) -->
                        <div class="col-12">
                            <label for="floatingCapa" class="form-label fw-bold">
                                <i class="bi bi-image me-1"></i>Capa do Curso (Opcional)
                            </label>
                            <input class="form-control @error('capa') is-invalid @enderror" 
                                   type="file" 
                                   id="floatingCapa" 
                                   name="capa" 
                                   accept="image/*">
                            @error('capa')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                   <div class="text-center mb-4">
                      <button type="submit" class="btn btn-primary">Salvar Actualização</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
