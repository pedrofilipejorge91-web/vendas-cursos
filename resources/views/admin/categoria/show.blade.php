 <!-- Basic Modal -->
<div class="modal fade" id="details-{{$categoria->id}}" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalhes da categoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
           
      <div class="row">
        
        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
               <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <h5 class="card-title">Detalhes da categoria</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Titulo</div>
                    <div class="col-lg-9 col-md-8">{{ $categoria->nome }}</div>
                  </div>


                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Descrição</div>
                    <div class="col-lg-9 col-md-8">{{ $categoria->descricao }}</div>
                  </div>             

                 <div class="row">
                    <div class="col-lg-3 col-md-4 label">Data de cadastro</div>
                    <div class="col-lg-9 col-md-8">{{ $categoria->created_at->format('d/m/Y H:i') }}</div>
                  </div>  

                </div>
               

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
       
      </div>
    </div>
  </div>
</div>
