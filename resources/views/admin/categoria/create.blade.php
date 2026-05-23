<!-- Basic Modal -->
<div class="modal fade" id="basicModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Categória</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Floating Labels Form -->
              <form class="row g-3" method="POST" action="{{route('categoria.store')}}" enctype="multipart/form-data" data-ajax="true" data-ajax-refresh="#categoria-table-wrapper" data-ajax-reset="true">
                @csrf  
              <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="nome" id="floatingName" placeholder="Nome da categoria" required>
                    <label for="floatingName">Nome</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating">
                    <textarea class="form-control" name="descricao" placeholder="Address" id="floatingTextarea" style="height: 100px;"></textarea>
                    <label for="floatingTextarea">Descrição</label>
                  </div>
                </div>
                <div class="col-12 mt-2">
                  <label for="imagem" class="form-label">Imagem da Categoria (opcional)</label>
                  <input class="form-control" type="file" id="imagem" name="imagem" accept="image/*">
                </div>
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Adicionar</button>
                  
                </div>
              </form><!-- End floating Labels Form -->
            </div>
            
        </div>
    </div>
</div><!-- End Basic Modal-->
