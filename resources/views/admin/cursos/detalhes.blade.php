 <!-- Basic Modal -->
<div class="modal fade" id="details-{{$curso->id}}" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalhes do curso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
    <div class="pagetitle">
      <h1>Detalhes</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

   
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="{{ Storage::url($curso->foto) }}"  class="rounded-circle" style="width: 50px;
               height: 50px; object-fit: cover; border-radius: 50%;">
          <li class="breadcrumb-item">Formador</li>
              <h5 class="text-center">{{ $curso->formador->pessoa->primeironome }}
                {{ $curso->formador->pessoa->segundonome }}
              </h5>
              <h3>{{ $curso->titulo }}</h3>
              
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Detalhes</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">descricao</h5>
                  <p class="small fst-italic">{{ $curso->descricao }}</p>

                  <h5 class="card-title">Detalhes do curso</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Titulo</div>
                    <div class="col-lg-9 col-md-8">{{ $curso->titulo }}</div>
                  </div>


                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Preço</div>
                    <div class="col-lg-9 col-md-8">{{ $curso->preco}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Duração da hora</div>
                    <div class="col-lg-9 col-md-8">{{ $curso->duracao_horas}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Status</div>
                    <div class="col-lg-9 col-md-8">{{ $curso->status}}</div>
                  </div>


                     <div class="row">
                    <div class="col-lg-3 col-md-4 label">Formador</div>
                    <div class="col-lg-9 col-md-8">{{ $curso->formador->pessoa->nome}}</div>
                  </div>

                     <div class="row">
                    <div class="col-lg-3 col-md-4 label">Categoria</div>
                    <div class="col-lg-9 col-md-8">{{ $curso->categoria->nome}}</div>
                  </div>

                     <div class="row">
                    <div class="col-lg-3 col-md-4 label">Data de cadastro</div>
                    <div class="col-lg-9 col-md-8">{{ $curso->created_at}}</div>
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