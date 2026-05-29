@extends('formador-layouts.apps')

@section('content')
<div class="formador-edit-page">
    <div class="edit-page-header">
        <div>
            <span class="eyebrow">Ajuda</span>
            <h1>Suporte</h1>
            <p>Envie uma solicitação para a administração. A mensagem fica registada como notificação interna.</p>
        </div>
    </div>

    <form action="{{ route('formador.suporte.enviar') }}" method="POST" class="panel-card">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label" for="assunto">Assunto *</label>
                <input class="form-control @error('assunto') is-invalid @enderror" id="assunto" name="assunto" value="{{ old('assunto') }}" required>
                @error('assunto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="mensagem">Mensagem *</label>
                <textarea class="form-control @error('mensagem') is-invalid @enderror" id="mensagem" name="mensagem" rows="8" required>{{ old('mensagem') }}</textarea>
                @error('mensagem')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button class="btn btn-primary btn-lg" type="submit">
                    <i class="bi bi-send me-2"></i>Enviar pedido
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
