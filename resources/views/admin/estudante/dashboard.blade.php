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
                        class="btn btn-primary bi bi-plus me-1"
                        data-bs-toggle="modal"
                        data-bs-target="#basicModal">

                    Novo Estudante
                </button>

            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <h5 class="card-title">Tabela de Estudantes</h5>

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

                            {{-- USER --}}
                            <td>
                                {{ $estudante->pessoa->user->name ?? 'N/A' }}
                            </td>

                            {{-- NOME COMPLETO --}}
                            <td>
                                {{ $estudante->pessoa->primeironome }}
                                {{ $estudante->pessoa->segundonome }}
                            </td>

                            {{-- CONTACTO --}}
                            <td>
                                {{ $estudante->pessoa->contacto }}
                            </td>

                            {{-- EMAIL --}}
                            <td>
                                {{ $estudante->pessoa->user->email ?? 'N/A' }}
                            </td>

                            {{-- ESCOLA --}}
                            <td>
                                {{ $estudante->escola_actual ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="badge bg-{{ $estudante->status === 'ativo' ? 'success' : 'danger' }}">
                                    {{ $estudante->status }}
                                </span>
                            </td>

                            {{-- AÇÕES --}}
                            <td>

                                <div class="btn-group">

                                    {{-- EDITAR --}}
                                    <button class="btn btn-info bi bi-pencil"
                                            data-bs-toggle="modal"
                                            data-bs-target="#edit-{{ $estudante->id }}">
                                    </button>

                                    {{-- DETALHES --}}
                                    <button class="btn btn-primary bi bi-eye"
                                            data-bs-toggle="modal"
                                            data-bs-target="#details-{{ $estudante->id }}">
                                    </button>

                                    {{-- DELETE --}}
                                    <button class="btn btn-danger bi bi-trash"
                                            data-bs-toggle="modal"
                                            data-bs-target="#delete-{{ $estudante->id }}">
                                    </button>

                                                                            {{-- TOGGLE STATUS --}}
                                                                        <form method="POST"
                                            action="{{ route('estudante.status', $estudante->id) }}">

                                            @csrf
                                            @method('PUT')

                                            <button type="submit"
                                                    class="btn btn-warning">

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
</section>

@endsection
