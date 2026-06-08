@extends('welcome.apps')

@section('content')
<section class="page-hero compact">
    <div class="site-container">
        <p class="eyebrow">Compras</p>
        <h1>Carrinho</h1>
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

<style>
.cart-grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:30px;
    margin-top:30px;
}

.cart-item{
    display:flex;
    gap:20px;
    padding:20px;
    background:#fff;
    border-radius:14px;
    margin-bottom:15px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
}

.cart-item img{
    width:120px;
    height:90px;
    object-fit:cover;
    border-radius:10px;
}

.cart-item div{
    flex:1;
}

.cart-item span{
    color:#6b7280;
    font-size:14px;
}

.cart-item h2{
    margin:8px 0;
    font-size:18px;
}

.cart-item strong{
    color:#2563eb;
    font-size:16px;
}

.cart-item button{
    background:#fee2e2;
    color:#991b1b;
    border:none;
    padding:8px 15px;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
}

.cart-item button:hover{
    background:#fecaca;
}

.summary-card{
    background:#fff;
    padding:25px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    position:sticky;
    top:20px;
    height:fit-content;
}

.summary-card h3{
    margin-bottom:20px;
}

.summary-card div{
    display:flex;
    justify-content:space-between;
    margin-bottom:12px;
}

.summary-card .total{
    font-size:18px;
    font-weight:700;
    border-top:2px solid #e5e7eb;
    padding-top:15px;
    margin-top:15px;
}

.btn-full{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    text-align:center;
    text-decoration:none;
    display:inline-block;
    margin-top:15px;
}

.primary{
    background:#2563eb;
    color:#fff;
}

.primary:hover{
    opacity:.9;
}

.light{
    background:#f3f4f6;
    color:#374151;
}

.light:hover{
    background:#e5e7eb;
}

.summary-link{
    display:block;
    text-align:center;
    margin-top:15px;
    color:#6b7280;
    text-decoration:none;
}

.summary-link:hover{
    color:#2563eb;
}

.empty-state{
    text-align:center;
    padding:60px 20px;
    background:#fff;
    border-radius:18px;
}

.empty-state i{
    font-size:64px;
    color:#d1d5db;
    margin-bottom:20px;
}

.empty-state p{
    color:#6b7280;
    margin-bottom:20px;
}

.empty-state a{
    color:#2563eb;
    text-decoration:none;
    font-weight:600;
}

.alert-card{
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
}

.alert-card.error{
    background:#fee2e2;
    color:#991b1b;
}

.alert-card.success{
    background:#dcfce7;
    color:#166534;
}

@media(max-width:768px){
    .cart-grid{
        grid-template-columns:1fr;
    }

    .summary-card{
        position:relative;
        top:auto;
    }

    .cart-item{
        flex-direction:column;
    }

    .cart-item img{
        width:100%;
        height:150px;
    }
}
</style>
@endsection