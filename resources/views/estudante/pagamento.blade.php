@extends('estudant-layouts.apps')

@section('content')
<div class="pagetitle">
    <h1>Pagamento</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Painel</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home.carrinho') }}">Carrinho</a></li>
            <li class="breadcrumb-item active">Finalizar</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Dados de pagamento</h5>
                    <form action="{{ route('pagamento.processar') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">Método de pagamento</label>
                            <select name="metodo_pagamento" class="form-control" required>
                                <option value="">Selecione</option>
                                @foreach(($metodos ?? []) as $valor => $rotulo)
                                    <option value="{{ $valor }}">{{ $rotulo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" placeholder="+244 900 000 000">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Comprovativo</label>
                            <input type="file" name="comprovativo" class="form-control" accept="image/*,.pdf">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary">
                                <i class="bi bi-credit-card me-1"></i> Enviar pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Resumo da compra</h5>
                    @php $fallbackTotal = 0; @endphp
                    @foreach($carrinho as $item)
                        @php $fallbackTotal += $item['preco']; @endphp
                        <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
                            <span class="text-muted small">{{ $item['titulo'] }}</span>
                            <strong class="small">{{ number_format($item['preco'], 2, ',', '.') }} Kz</strong>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="text-muted">Total</span>
                        <strong class="fs-4 text-primary">{{ number_format($total ?? $fallbackTotal, 2, ',', '.') }} Kz</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
