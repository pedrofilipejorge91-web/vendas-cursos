@extends('admin-layouts.apps')

@section('content')
<div class="pagetitle">
    <h1>Administradores</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Administradores</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Novo administrador</h5>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.administradores.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Senha</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Lista de administradores</h5>

                    @forelse($administradores as $administrador)
                        <div class="border rounded p-3 mb-3">
                            <form method="POST" action="{{ route('admin.administradores.update', $administrador) }}" class="row g-2 align-items-end">
                                @csrf
                                @method('PUT')
                                <div class="col-md-4">
                                    <label class="form-label">Nome</label>
                                    <input type="text" name="name" class="form-control" value="{{ $administrador->name }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $administrador->email }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Nova senha</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-outline-primary w-100">Salvar</button>
                                </div>
                            </form>

                            @if($administrador->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.administradores.destroy', $administrador) }}" class="mt-2 text-end">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">Nenhum administrador cadastrado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
