@extends('estudant-layouts.apps')

@section('content')
<div class="pagetitle">
    <h1>Meu Perfil</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Painel</a></li>
            <li class="breadcrumb-item active">Perfil</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Dados pessoais</h5>

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

                    <form method="POST" action="{{ route('estudante.perfil.update') }}" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-12">
                            <label class="form-label">Nome de acesso</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Primeiro nome</label>
                            <input type="text" name="primeironome" value="{{ old('primeironome', $pessoa->primeironome ?? '') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Segundo nome</label>
                            <input type="text" name="segundonome" value="{{ old('segundonome', $pessoa->segundonome ?? '') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contacto</label>
                            <input type="text" name="contacto" value="{{ old('contacto', $pessoa->contacto ?? '') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Localizacao</label>
                            <input type="text" name="localizacao" value="{{ old('localizacao', $estudante->localizacao ?? '') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rua</label>
                            <input type="text" name="rua" value="{{ old('rua', $pessoa->rua ?? '') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Bairro</label>
                            <input type="text" name="bairro" value="{{ old('bairro', $pessoa->bairro ?? '') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Area de interesse</label>
                            <input type="text" name="area_interesse" value="{{ old('area_interesse', $estudante->area_interesse ?? '') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Formacao</label>
                            <input type="text" name="formacao" value="{{ old('formacao', $estudante->formacao ?? '') }}" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Escola actual</label>
                            <input type="text" name="escola_actual" value="{{ old('escola_actual', $estudante->escola_actual ?? '') }}" class="form-control">
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Guardar perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
