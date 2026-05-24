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
                        Escolha como deseja receber o link de recuperacao de senha.
                    </p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Canal</label>
                            <select name="canal" id="canal" class="form-select">
                                <option value="email" @selected(old('canal', 'email') === 'email')>Email</option>
                                <option value="sms" @selected(old('canal') === 'sms')>SMS</option>
                            </select>
                            @error('canal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="email-field">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="contacto-field">
                            <label for="contacto" class="form-label">Numero de telefone</label>
                            <input type="text" class="form-control @error('contacto') is-invalid @enderror"
                                   id="contacto" name="contacto" value="{{ old('contacto') }}" placeholder="+244...">
                            @error('contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="submit-button">
                            <i class="bi bi-envelope-check me-2" id="submit-icon"></i><span id="submit-text">Enviar Link</span>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const canal = document.getElementById('canal');
    const emailField = document.getElementById('email-field');
    const contactoField = document.getElementById('contacto-field');
    const emailInput = document.getElementById('email');
    const contactoInput = document.getElementById('contacto');
    const submitIcon = document.getElementById('submit-icon');
    const submitText = document.getElementById('submit-text');

    function actualizarCampos() {
        const usarSms = canal.value === 'sms';

        emailField.classList.toggle('d-none', usarSms);
        contactoField.classList.toggle('d-none', !usarSms);

        emailInput.disabled = usarSms;
        emailInput.required = !usarSms;
        contactoInput.disabled = !usarSms;
        contactoInput.required = usarSms;

        submitIcon.className = usarSms ? 'bi bi-phone me-2' : 'bi bi-envelope-check me-2';
        submitText.textContent = usarSms ? 'Enviar por SMS' : 'Enviar por Email';
    }

    canal.addEventListener('change', actualizarCampos);
    actualizarCampos();
});
</script>
@endsection
