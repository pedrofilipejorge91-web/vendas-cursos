
@extends(Auth::user()?->tipo === 'formador' ? 'formador-layouts.apps' : 'admin-layouts.apps')
@section('content')

<div class="pagetitle">
    <h1>Cursos</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ Auth::user()?->tipo === 'formador' ? route('formador.dashboard') : route('admin.dashboard') }}">Gestão de Cursos</a></li>
            <li class="breadcrumb-item active">cursos</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

@include('admin.cursos.create')

<section class="section dashboard">
    <div class="row">
        <div class="card" id="curso-table-wrapper">
            <div class="card-body">
                <h5 class="card-title">Cadastrar Curso</h5>
                <button type="button" class="btn btn-primary bi bi-plus me-1" data-bs-toggle="modal" data-bs-target="#basicModal">
                    Novo Curso
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tabela de Dados</h5>

                <!-- Table with stripped rows -->
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th scope="col">Capa</th>
                            <th scope="col">Título</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Formador</th>
                            <th scope="col">Preço (Kz)</th>
                            <th scope="col">Duração (h)</th>
                            <th scope="col">Idioma</th>
                            <th scope="col">Status</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>     
                        @foreach($cursos as $curso)
                            <tr>
                                <!-- Coluna da Foto -->
                                <!-- Coluna da Foto/Capa -->                                                 <td>
                                                        @if($curso->foto && Storage::disk('public')->exists($curso->foto))
                                                            <img src="{{ Storage::url($curso->foto) }}" 
                                                                alt="{{ $curso->titulo }}" 
                                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;"
                                                                class="border">
                                                        @else
                                                            <!-- Imagem placeholder quando não há foto -->
                                                            <div style="width: 50px; height: 50px; border-radius: 50%; background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                                                <i class="bi bi-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                <td>{{ Str::limit($curso->titulo, 40) }}</td>
                                <td>{{ $curso->categoria?->nome ?? 'Sem categoria' }}</td>
                                <td>{{ $curso->formador?->pessoa?->primeironome ?? 'Não atribuído' }}
                                    {{ $curso->formador?->pessoa?->segundonome ?? 'Não atribuído' }}
                                </td>
                                <td>{{ number_format($curso->preco, 2, ',', '.') }} Kz</td>
                                <td>{{ $curso->duracao_horas }}h</td>
                                <td>{{ $curso->idioma }}</td>
                                <td>  {{ ucfirst($curso->status) }}</td>                            
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        <!-- Botão Editar -->
                                        <button type="button" class="btn btn-info bi bi-pencil" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#edit-{{$curso->id}}"></button>
                                        
                                        <!-- Botão Eliminar -->
                                        <button type="button" class="btn btn-danger bi bi-trash" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#delete-{{$curso->id}}"></button>

                                                 <!-- Botão detalhes -->
                                        <button type="button" class="btn btn-primary bi bi-eye" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#details-{{$curso->id}}"></button>

                                        @if(Auth::user()?->tipo === 'admin' && $curso->status !== 'publicado')
                                            <form action="{{ route('curso.publicar', $curso->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-success bi bi-check" title="Aprovar"></button>
                                            </form>
                                        @elseif(Auth::user()?->tipo === 'admin')
                                            <form action="{{ route('curso.rejeitar', $curso->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-warning bi bi-x" title="Rejeitar"></button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            <!-- Inclui os modais fora da tr, mas dentro do loop -->
                           @include('admin.cursos.edit')
                           @include('admin.cursos.delete')
                           @include('admin.cursos.detalhes')
                        @endforeach
                        
                    </tbody>
                    
                </table>
                <!-- End Table with stripped rows -->

            </div>
        </div>

    </div>
</section>

@endsection
