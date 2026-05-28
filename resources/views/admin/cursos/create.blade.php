<!-- Modal Criar Curso -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="modalCursoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <!-- Header Estilizado -->
            <div class="modal-header bg-primary text-white py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 45px; height: 45px;">
                        <i class="bi bi-mortarboard-fill fs-4"></i>
                    </div>

                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalCursoLabel">
                            Criar Novo Curso
                        </h5>
                        <small class="text-white-50">
                            Preencha os detalhes para publicar um novo conteúdo
                        </small>
                    </div>
                </div>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <!-- Form -->
            <form action="{{ Auth::user()?->tipo === 'formador' ? route('formador.cursos.store') : route('curso.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  data-ajax="true"
                  data-ajax-refresh="#curso-table-wrapper"
                  data-ajax-reset="true">

                @csrf

                <div class="modal-body p-4">

                    <!-- Mensagens de Erro -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>

                                <div>
                                    <strong>Ops! Algo deu errado:</strong>

                                    <ul class="mb-0 small mt-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row g-4">

                        <!-- SEÇÃO 1 -->
                        <div class="col-12">

                            <h6 class="text-primary fw-bold text-uppercase small mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Informações Gerais
                            </h6>

                            <div class="row g-3">

                                <!-- Título -->
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text"
                                               class="form-control border-0 bg-light"
                                               id="floatingTitulo"
                                               name="titulo"
                                               placeholder="Título"
                                               value="{{ old('titulo') }}"
                                               required>

                                        <label for="floatingTitulo">
                                            Título do Curso *
                                        </label>
                                    </div>
                                </div>

                                <!-- Categoria -->
                                <div class="col-md-6">
                                    <div class="form-floating">

                                        <select class="form-select border-0 bg-light"
                                                name="categoria_id"
                                                required>

                                            <option value="" selected disabled>
                                                Escolha uma categoria
                                            </option>

                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}"
                                                    {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>

                                                    {{ $categoria->nome }}

                                                </option>
                                            @endforeach
                                        </select>

                                        <label>Categoria *</label>
                                    </div>
                                </div>

                                <!-- Idioma -->
                                <div class="col-md-6">
                                    <div class="form-floating">

                                        <select class="form-select border-0 bg-light"
                                                name="idioma"
                                                required>

                                            <option value="pt-AO"
                                                {{ old('idioma') === 'pt-AO' ? 'selected' : '' }}>
                                                Português (Angola)
                                            </option>

                                            <option value="pt-PT"
                                                {{ old('idioma') === 'pt-PT' ? 'selected' : '' }}>
                                                Português (Portugal)
                                            </option>

                                            <option value="en"
                                                {{ old('idioma') === 'en' ? 'selected' : '' }}>
                                                Inglês
                                            </option>

                                            <option value="fr"
                                                {{ old('idioma') === 'fr' ? 'selected' : '' }}>
                                                Francês
                                            </option>
                                        </select>

                                        <label>Idioma do Curso *</label>
                                    </div>
                                </div>

                                <!-- Formador -->
                                @if(Auth::user()?->tipo !== 'formador')
                                    <div class="col-md-12">
                                        <div class="form-floating">

                                            <select class="form-select border-0 bg-light"
                                                    name="formador_id"
                                                    required>

                                                <option value="" selected disabled>
                                                    Atribuir a um formador
                                                </option>

                                                @foreach($formadores as $formador)
                                                    <option value="{{ $formador->id }}"
                                                        {{ old('formador_id') == $formador->id ? 'selected' : '' }}>

                                                        {{ $formador->pessoa->primeironome ?? $formador->primeironome }}
                                                        {{ $formador->pessoa->segundonome ?? $formador->segundonome }}

                                                    </option>
                                                @endforeach
                                            </select>

                                            <label>Formador Responsável *</label>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <!-- SEÇÃO 2 -->
                        <div class="col-12">

                            <h6 class="text-primary fw-bold text-uppercase small mb-3">
                                <i class="bi bi-gear me-2"></i>
                                Configurações e Preço
                            </h6>

                            <div class="row g-3 p-3 bg-light rounded-3 mx-0">

                                <!-- Duração -->
                                <div class="col-md-4">

                                    <label class="form-label small fw-bold">
                                        Duração (Horas)
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-clock text-primary"></i>
                                        </span>

                                        <input type="number"
                                               name="duracao_horas"
                                               class="form-control border-0"
                                               placeholder="Ex: 40"
                                               value="{{ old('duracao_horas') }}"
                                               required>
                                    </div>
                                </div>

                                <!-- Preço -->
                                <div class="col-md-4">

                                    <label class="form-label small fw-bold">
                                        Preço (AOA)
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-0 fw-bold text-primary">
                                            Kz
                                        </span>

                                        <input type="number"
                                               name="preco"
                                               class="form-control border-0"
                                               placeholder="0.00"
                                               step="0.01"
                                               value="{{ old('preco') }}"
                                               required>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-4">

                                    <label class="form-label small fw-bold">
                                        Status Inicial
                                    </label>

                                    <select class="form-select border-0"
                                            name="status"
                                            required>

                                        <option value="rascunho"
                                            {{ old('status') == 'rascunho' ? 'selected' : '' }}>
                                            📝 Rascunho
                                        </option>

                                        <option value="publicado"
                                            {{ old('status') == 'publicado' ? 'selected' : '' }}>
                                            🚀 Publicado
                                        </option>

                                        <option value="inativo"
                                            {{ old('status') == 'inativo' ? 'selected' : '' }}>
                                            🔒 Inativo
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SEÇÃO 3 -->
                        <div class="col-12">

                            <h6 class="text-primary fw-bold text-uppercase small mb-3">
                                <i class="bi bi-card-image me-2"></i>
                                Apresentação
                            </h6>

                            <div class="row g-3">

                                <!-- Descrição -->
                                <div class="col-12">
                                    <div class="form-floating">

                                        <textarea class="form-control border-0 bg-light"
                                                  placeholder="Descrição"
                                                  id="floatingDesc"
                                                  name="descricao"
                                                  style="height: 120px"
                                                  required>{{ old('descricao') }}</textarea>

                                        <label for="floatingDesc">
                                            Resumo do Curso (O que o aluno vai aprender?) *
                                        </label>
                                    </div>
                                </div>

                                <!-- Foto -->
                                <div class="col-12">

                                    <label class="form-label fw-bold small">
                                        Capa do Curso
                                    </label>

                                    <div class="input-group">

                                        <input type="file"
                                               class="form-control border-0 bg-light"
                                               name="foto"
                                               id="foto"
                                               accept="image/*">

                                        <label class="input-group-text bg-primary text-white border-0"
                                               for="foto">
                                            <i class="bi bi-upload"></i>
                                        </label>
                                    </div>

                                    <div class="form-text">
                                        Formatos aceitos: JPG, PNG. Tamanho ideal: 800x600px.
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer bg-light py-3">

                    <button type="button"
                            class="btn btn-outline-secondary border-0 fw-bold"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn btn-primary px-5 py-2 fw-bold shadow">

                        <i class="bi bi-cloud-check me-2"></i>
                        Salvar e Criar Curso
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    /* MODAL */
    #basicModal .modal-content {
        border-radius: 20px;
        overflow: hidden;
        max-height: 95vh;
    }

    /* BODY */
    #basicModal .modal-body {
        overflow-y: auto;
        max-height: calc(95vh - 140px);
    }

    /* INPUTS */
    #basicModal .form-control,
    #basicModal .form-select {
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    #basicModal .form-control:focus,
    #basicModal .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.12);
        border: 1px solid #0d6efd;
    }

    /* LABELS */
    #basicModal .form-floating > label {
        padding-left: 1.25rem;
        color: #6c757d;
    }

    /* INPUT GROUP */
    #basicModal .input-group-text {
        border-radius: 10px 0 0 10px;
    }

    /* FOOTER FIXO */
    #basicModal .modal-footer {
        position: sticky;
        bottom: 0;
        z-index: 10;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    /* BOTÃO */
    #basicModal .btn-primary {
        border-radius: 10px;
        transition: 0.3s;
    }

    #basicModal .btn-primary:hover {
        transform: translateY(-1px);
    }

    /* SCROLL */
    #basicModal .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    #basicModal .modal-body::-webkit-scrollbar-thumb {
        background: rgba(13, 110, 253, 0.4);
        border-radius: 20px;
    }
</style>