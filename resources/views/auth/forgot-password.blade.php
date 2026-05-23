@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h2 class="mb-4 text-center fw-bold">Recuperacao de Senha</h2>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <p class="text-muted text-center mb-4">
                        Insira seu email ou contacto para receber um link de recuperacao de senha.
                    </p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Canal</label>
                            <select name="canal" class="form-select">
                                <option value="email" @selected(old('canal', 'email') === 'email')>Email</option>
                                <option value="sms" @selected(old('canal') === 'sms')>SMS</option>
                            </select>
                            @error('canal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contacto" class="form-label">Contacto para SMS</label>
                            <input type="text" class="form-control @error('contacto') is-invalid @enderror"
                                   id="contacto" name="contacto" value="{{ old('contacto') }}" placeholder="+244...">
                            @error('contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-envelope-check me-2"></i>Enviar Link
                        </button>
                    </form>

                    <hr class="my-4">

                    <p class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">Voltar ao Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
