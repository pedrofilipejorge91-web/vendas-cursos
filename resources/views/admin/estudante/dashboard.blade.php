@extends('admin-layouts.apps')

@section('content')

<div class="pagetitle">
    <h1>Estudantes</h1>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Gestão de Estudantes</a></li>
            <li class="breadcrumb-item active">Estudantes</li>
        </ol>
    </nav>
</div>

@include('admin.estudante.create')

<section class="section dashboard">
    <div class="row">

        <div class="card">
            <div class="card-body">

                <h5 class="card-title">Cadastrar Estudante</h5>

                <button type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#basicModal">
                    <i class="bi bi-plus me-1"></i>
                    Novo Estudante
                </button>

            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <h5 class="card-title">Tabela de Estudantes</h5>

                <div class="table-responsive">
                    <table class="table datatable">

                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Nome Completo</th>
                                <th>Contacto</th>
                                <th>Email</th>
                                <th>Escola</th>
                                <th>Status</th>
                                <th>Acções</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($estudantes as $estudante)

                            <tr>
                                <td>
                                    {{ $estudante->pessoa->user->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $estudante->pessoa->primeironome }}
                                    {{ $estudante->pessoa->segundonome }}
                                </td>

                                <td>
                                    {{ $estudante->pessoa->contacto }}
                                </td>

                                <td>
                                    {{ $estudante->pessoa->user->email ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $estudante->escola_actual ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-{{ $estudante->status === 'ativo' ? 'success' : 'danger' }}">
                                        {{ $estudante->status }}
                                    </span>
                                </td>

                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit-{{ $estudante->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#details-{{ $estudante->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button class="btn btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delete-{{ $estudante->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        <form method="POST"
                                              action="{{ route('estudante.status', $estudante->id) }}">
                                            @csrf
                                            @method('PUT')

                                            <button type="submit" class="btn btn-warning">
                                                @if($estudante->status == 'ativo')
                                                    <i class="bi bi-toggle-on"></i>
                                                @else
                                                    <i class="bi bi-toggle-off"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @include('admin.estudante.update')
                            @include('admin.estudante.delete')
                            @include('admin.estudante.show')

                            @endforeach

                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
</section>

@endsection
