@extends('welcome.apps')

@section('content')
<section class="page-hero compact">
    <div class="site-container">
        <p class="eyebrow">Compras</p>
        <h1>Seu carrinho</h1>
        <p>Revise os cursos selecionados antes de finalizar a inscricao.</p>
    </div>
</section>

<section class="cart-page">
    <div class="site-container">
        @if(session('success'))
            <div class="alert-card success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-card error">{{ $errors->first() }}</div>
        @endif

        @if(count($carrinho) > 0)
            <div class="cart-grid">
                <div class="cart-items">
                    @foreach($carrinho as $id => $item)
                        <article class="cart-item">
                            <img src="{{ !empty($item['foto']) ? Storage::url($item['foto']) : asset('assets/img/logo.png') }}" alt="{{ $item['titulo'] }}">
                            <div>
                                <span>{{ $item['categoria'] ?? 'Curso' }} · {{ $item['duracao_horas'] ?? 0 }}h</span>
                                <h2>{{ $item['titulo'] }}</h2>
                                <strong>{{ number_format($item['preco'], 2, ',', '.') }} Kz</strong>
                            </div>
                            <form action="{{ route('carrinho.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="curso_id" value="{{ $id }}">
                                <button type="submit"><i class="bi bi-trash"></i> Remover</button>
                            </form>
                        </article>
                    @endforeach
                </div>

                <aside class="summary-card">
                    <h3>Resumo</h3>
                    <div><span>Cursos</span><strong>{{ count($carrinho) }}</strong></div>
                    <div class="total"><span>Total</span><strong>{{ number_format($total, 2, ',', '.') }} Kz</strong></div>
                    <a href="{{ route('pagamento') }}" class="btn-full primary">Finalizar compra</a>
                    <form action="{{ route('carrinho.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-full light">Limpar carrinho</button>
                    </form>
                    <a href="{{ route('home.catalogo') }}" class="summary-link">Continuar a ver cursos</a>
                </aside>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-cart"></i>
                <p>Seu carrinho esta vazio.</p>
                <a href="{{ route('home.catalogo') }}">Ver cursos</a>
            </div>
        @endif
    </div>
</section>
@endsection
