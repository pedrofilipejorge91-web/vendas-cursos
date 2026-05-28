<!-- Modal de Edição -->
<div class="modal fade" id="edit-{{ $categoria->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $categoria->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold text-secondary" id="editModalLabel-{{ $categoria->id }}">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Editar Categoria
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('categoria.update', $categoria->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body p-4">
                    <div class="row g-3">
                        
                        <!-- Campo Nome -->
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="nome" id="floatingName-{{ $categoria->id }}" placeholder="Nome da categoria" value="{{ $categoria->nome }}" required>
                                <label for="floatingName-{{ $categoria->id }}">Nome</label>
                            </div>
                        </div>

                        <!-- Campo Descrição -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="descricao" placeholder="Descrição" id="floatingTextarea-{{ $categoria->id }}" style="height: 120px;">{{ $categoria->descricao }}</textarea>
                                <label for="floatingTextarea-{{ $categoria->id }}">Descrição</label>
                            </div>
                        </div>

                        <!-- Upload de Imagem Interativo -->
                        <div class="col-12 mt-3">
                            <label class="form-label fw-semibold text-secondary small">Imagem de Capa</label>
                            <div class="image-update-wrapper position-relative text-center rounded border bg-light">
                                @php
                                    $imgUrl = $categoria->imagem ? asset('storage/categorias/' . $categoria->imagem) : asset('assets/img/placeholder.png'); // Use um placeholder
                                @endphp

                                <img id="imagePreview-{{ $categoria->id }}" src="{{ $imgUrl }}" alt="Preview" 
                                     class="img-fluid rounded" style="max-height: 180px; width: 100%; object-fit: cover;">
                                
                                <div class="image-actions position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                                    <label for="imagem-{{ $categoria->id }}" class="btn btn-sm btn-light shadow-sm">
                                        <i class="bi bi-arrow-up-circle me-1"></i> Alterar
                                    </label>
                                    @if($categoria->imagem)
                                    <button type="button" class="btn btn-sm btn-danger shadow-sm" onclick="removeImage('{{ $categoria->id }}')">
                                        <i class="bi bi-trash me-1"></i> Remover
                                    </button>
                                    @endif
                                </div>
                                
                                <input class="form-control d-none" type="file" id="imagem-{{ $categoria->id }}" name="imagem" accept="image/*" onchange="previewEditImage(this, '{{ $categoria->id }}')">
                                <input type="hidden" name="remove_imagem" id="removeImageInput-{{ $categoria->id }}" value="0">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Salvar Alterações
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    .image-update-wrapper .image-actions {
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        border-radius: 0.375rem; /* igual ao .rounded */
    }
    .image-update-wrapper:hover .image-actions {
        opacity: 1;
    }
</style>

<script>
    /**
     * Atualiza o preview da imagem no modal de edição.
     */
    function previewEditImage(input, categoryId) {
        const preview = document.getElementById(`imagePreview-${categoryId}`);
        const removeInput = document.getElementById(`removeImageInput-${categoryId}`);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
            
            // Se o usuário carregar uma nova imagem, cancelamos a remoção
            if (removeInput) {
                removeInput.value = '0'; 
            }
        }
    }

    /**
     * Marca uma imagem para remoção e atualiza a UI.
     */
    function removeImage(categoryId) {
        const preview = document.getElementById(`imagePreview-${categoryId}`);
        const removeInput = document.getElementById(`removeImageInput-${categoryId}`);
        const fileInput = document.getElementById(`imagem-${categoryId}`);

        // Define um placeholder visual
        preview.src = "{{ asset('assets/img/placeholder.png') }}"; // Certifique-se que este placeholder existe
        
        // Informa o backend para remover a imagem
        if (removeInput) {
            removeInput.value = '1';
        }
        
        // Limpa qualquer arquivo selecionado para não ser enviado
        if (fileInput) {
            fileInput.value = "";
        }
    }
</script>