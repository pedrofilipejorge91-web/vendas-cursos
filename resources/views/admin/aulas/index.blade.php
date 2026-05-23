@extends(Auth::user()?->tipo === 'formador' ? 'formador-layouts.apps' : 'admin-layouts.apps')
@section('content')

<div class="pagetitle">
    <h1>Aulas</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Gestão de Aulas</a></li>
            <li class="breadcrumb-item active">Aulas</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
@include('admin.aulas.create')
<section class="section dashboard">
    <div class="row">
<div class="card">
            <div class="card-body">
              <h5 class="card-title">Adicionar Aula</h5>

              <button type="button" class="btn btn-primary bi bi-plus me-1" data-bs-toggle="modal" data-bs-target="#basicModal">
                Nova Aula
              </button>
            </div>
          </div>
        <div class="card" id="aula-table-wrapper">
            <div class="card-body">
                <h5 class="card-title">Tabela de Dados</h5>

                <!-- Table with stripped rows -->
                <table class="table datatable">

                    <thead>
                        <tr>
                            
                            <th scope="col">Título</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Curso</th>
                            <th scope="col">Acção</th>
                        </tr>                
                    </thead>
                    <tbody>     
                        @foreach($aulas as $aula)

                            <tr>
                                                                
                                <td>{{ $aula->titulo }}</td>
                                <td><span class="badge bg-info">{{ $aula->tipo }}</span></td>
                                <td>{{ $aula->curso->titulo ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        <!-- Botão Editar -->
                                        <button type="button" class="btn btn-info bi bi-pencil" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#edit-{{$aula->id }}"></button>
                                        
                                        <!-- Botão Eliminar -->
                                        <button type="button" class="btn btn-danger bi bi-trash" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#delete-{{$aula->id }}"></button>

                                                 <!-- Botão detalhes -->
                                        <button type="button" class="btn btn-primary bi bi-eye" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#details-{{$aula->id }}"></button>

                                    </div>
                                </td>
                            </tr>

                            <!-- Inclui os modais fora-->
                            @include('admin.aulas.edit')
                            @include('admin.aulas.delete')
                            @include('admin.aulas.detalhes')
                    @endforeach 
                    </tbody>
                </table>

                <!-- End Table with stripped rows -->

            </div>
        </div>

    </div>
</section>
@endsection
