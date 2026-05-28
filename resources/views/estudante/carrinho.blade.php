@extends('estudant-layouts.apps')

@section('content')
<div class="pagetitle">
    <h1>Carrinho</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Painel</a></li>
            <li class="breadcrumb-item active">Carrinho</li>
        </ol>
    </nav>
</div>

<section class="section">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($carrinho) > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @php $total = 0; @endphp
                        <div class="list-group list-group-flush">
                            @foreach($carrinho as $id => $item)
                                @php $total += $item['preco']; @endphp
                                <div class="list-group-item student-list-item px-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ !empty($item['foto']) ? Storage::url($item['foto']) : asset('assets/img/logo.png') }}" alt="{{ $item['titulo'] }}" style="width: 92px; height: 68px; object-fit: cover; border-radius: 8px;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $item['titulo'] }}</h6>
                                            <strong class="text-primary">{{ number_format($item['preco'], 2, ',', '.') }} Kz</strong>
                                        </div>
                                        <form action="{{ route('carrinho.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="curso_id" value="{{ $id }}">
                                            <button class="btn btn-outline-danger btn-sm" type="submit">
                                                Remover
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Resumo</h5>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted">Total</span>
                            <strong class="fs-4 text-primary">{{ number_format($total, 2, ',', '.') }} Kz</strong>
                        </div>
                        <a href="{{ route('pagamento') }}" class="btn btn-primary w-100">
                            Finalizar compra
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x display-5 text-muted"></i>
                <h5 class="mt-3">Seu carrinho está vazio</h5>
                <p class="text-muted">Adicione cursos para continuar a inscrição.</p>
                <a href="{{ route('home.catalogo') }}" class="btn btn-primary">Ver cursos</a>
            </div>
        </div>
    @endif
</section>
@endsection
