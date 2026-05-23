@extends('admin-layouts.apps')
@section('content')

<div class="pagetitle">
    <h1>Formadores</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Gestão de Formadores</a></li>
            <li class="breadcrumb-item active">Formadores</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

@include('admin.formador.create')

<section class="section dashboard">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Cadastrar Formador</h5>
                <button type="button" class="btn btn-primary bi bi-plus me-1" data-bs-toggle="modal" data-bs-target="#basicModal">
                    Novo Formador
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
                            <th scope="col">Foto</th> <!-- Nova Coluna -->
                            <th scope="col">Nome</th>
                            <th scope="col">Nacionalidade</th>
                            <th scope="col">Contacto</th>
                            <th scope="col">Especialidade</th>
                            <th scope="col">Anos de Experiencia</th>
                            <th scope="col">Acção</th>
                        </tr>
                    </thead>
                    <tbody>     
                        @foreach($formadors as $formador)
                            <tr>
                                <!-- Coluna da Foto -->
                                <td>
                                    @if($formador->foto_perfil)
                                        <img src="{{ Storage::url($formador->foto_perfil) }}" 
                                             alt="Foto de {{ $formador->pessoa->primeironome }}" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <img src="{{ asset('assets/img/logo.png') }}" 
                                             alt="Sem Foto" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    @endif
                                </td>
                                
                                                                        <td>
                                                {{ $formador->pessoa->primeironome }}
                                                {{ $formador->pessoa->segundonome }}
                                            </td>

                                            <td>{{ $formador->pessoa->nacionalidade }}</td>
                                            <td>{{ $formador->pessoa->contacto }}</td>
                                            <td>{{ $formador->especialidade }}</td>
                                            <td>{{ $formador->anos_experiencia }}</td>                           
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        <!-- Botão Editar -->
                                        <button type="button" class="btn btn-info bi bi-pencil" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#edit-{{$formador->id}}"></button>
                                        
                                        <!-- Botão Eliminar -->
                                        <button type="button" class="btn btn-danger bi bi-trash" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#delete-{{ $formador->id }}"></button>

                                                 <!-- Botão detalhes -->
                                        <button type="button" class="btn btn-primary bi bi-eye" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#details-{{$formador->id}}"></button>

                                    </div>
                                </td>
                            </tr>
                            <!-- Inclui os modais fora da tr, mas dentro do loop -->
                            @include('admin.formador.update')
                            @include('admin.formador.delete')
                            @include('admin.formador.show')
                        @endforeach
                    </tbody>
                </table>
                <!-- End Table with stripped rows -->

            </div>
        </div>

    </div>
</section>

@endsection
