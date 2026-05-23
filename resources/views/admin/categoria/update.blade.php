<!-- Basic Modal -->
<div class="modal fade" id="edit-{{$categoria->id}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atualizar Categória</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Floating Labels Form -->
              <form class="row g-3" method="POST" action="{{route('categoria.update',$categoria->id)}}" enctype="multipart/form-data" data-ajax="true" data-ajax-refresh="#categoria-table-wrapper">
                @csrf
                @method('PUT')
              <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="nome" id="floatingName-{{ $categoria->id }}" placeholder="Nome da categoria" value="{{$categoria->nome}}" required>
                    <label for="floatingName-{{ $categoria->id }}">Nome</label>
                  </div>
                </div>
                <div class="col-12 mt-3">
                  <div class="form-floating">
                    <textarea class="form-control" name="descricao" placeholder="Descricao" id="floatingTextarea-{{ $categoria->id }}" style="height: 100px;" required>{{$categoria->descricao}}</textarea>
                    <label for="floatingTextarea-{{ $categoria->id }}">Descricao</label>
                  </div>
                </div>
                <div class="col-12 mt-2">
                  <label for="imagem-{{ $categoria->id }}" class="form-label">Imagem da Categoria (opcional)</label>
                  <input class="form-control" type="file" id="imagem-{{ $categoria->id }}" name="imagem" accept="image/*">
                  @if($categoria->imagem)
                    <div class="mt-2"><img src="{{ asset('storage/categorias/' . $categoria->imagem) }}" alt="imagem" style="max-width:120px;"></div>
                  @endif
                </div>
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary mt-4">Atualizar</button>
                  
                </div>
              </form><!-- End floating Labels Form -->
            </div>
            
        </div>
    </div>
</div><!-- End Basic Modal-->
