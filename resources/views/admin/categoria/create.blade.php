<!-- Modal Nova Categoria -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <!-- Header com gradiente sutil -->
            <div class="modal-header bg-light border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-secondary" id="modalTitle">
                    <i class="bi bi-folder-plus me-2 text-primary"></i>Nova Categoria
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form class="row g-3" method="POST" action="{{ route('categoria.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Campo Nome -->
                    <div class="col-md-12">
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" name="nome" id="floatingName" placeholder="Ex: Programação" required>
                            <label for="floatingName">Nome da Categoria</label>
                        </div>
                        <small class="text-muted ps-1">Use nomes curtos e objetivos.</small>
                    </div>

                    <!-- Campo Descrição -->
                    <div class="col-12">
                        <div class="form-floating mb-1">
                            <textarea class="form-control" name="descricao" placeholder="Descrição" id="floatingTextarea" style="height: 120px;"></textarea>
                            <label for="floatingTextarea">Descrição (Opcional)</label>
                        </div>
                    </div>

                    <!-- Upload de Imagem Personalizado -->
                    <div class="col-12 mt-3">
                        <label class="form-label fw-semibold text-secondary small">Imagem de Capa</label>
                        
                        <!-- Container de Preview -->
                        <div class="image-upload-wrapper border rounded-3 p-3 text-center bg-light">
                            <div id="imagePreviewContainer" class="mb-2 d-none">
                                <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded-2 shadow-sm" style="max-height: 150px; width: 100%; object-fit: cover;">
                            </div>
                            
                            <div id="uploadPlaceholder">
                                <i class="bi bi-image text-muted display-6"></i>
                                <p class="small text-muted mb-2">Arraste ou clique para selecionar</p>
                            </div>

                            <input class="form-control form-control-sm" type="file" id="imagem" name="imagem" accept="image/*" onchange="previewImage(this)">
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Criar Categoria
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para o Modal */
    .modal-content {
        border-radius: 15px;
    }
    
    .form-control:focus {
        border-color: #4154f1;
        box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.1);
    }

    .image-upload-wrapper {
        border: 2px dashed #dee2e6 !important;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .image-upload-wrapper:hover {
        border-color: #4154f1 !important;
        background-color: #f8f9ff !important;
    }

    .form-floating > label {
        color: #6c757d;
    }
</style>

<script>
    /**
     * Função para mostrar o preview da imagem selecionada
     */
    function previewImage(input) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.classList.add('d-none');
            placeholder.classList.remove('d-none');
        }
    }
</script>