@extends('welcome.apps')

@section('content')
<section class="page-hero compact">
    <div class="site-container">
        <p class="eyebrow">Pagamento</p>
        <h1>Finalizar compra</h1>
        <p>Escolha o metodo de pagamento e envie os dados necessarios para liberar o acesso.</p>
    </div>
</section>

<section class="checkout-page">
    <div class="site-container">
        @if($errors->any())
            <div class="alert-card error">{{ $errors->first() }}</div>
        @endif

        @if(session('success'))
            <div class="alert-card success">{{ session('success') }}</div>
        @endif

        <div class="cart-grid">
            <div class="payment-panel">
                <h2>Metodo de pagamento</h2>
                <form action="{{ route('pagamento.processar') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="payment-methods">
                        @foreach($metodos as $valor => $label)
                            <label>
                                <input type="radio" name="metodo_pagamento" value="{{ $valor }}" @checked(old('metodo_pagamento') === $valor || $loop->first)>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <label class="field-label">Telefone para confirmacao</label>
                    <input type="text" name="telefone" value="{{ old('telefone') }}" placeholder="+244 900 000 000">

                    <label class="field-label">Comprovativo</label>
                    <input type="file" name="comprovativo" accept=".jpg,.jpeg,.png,.pdf">
                    <p class="field-help">Aceita JPG, PNG ou PDF ate 4MB.</p>

                    <div class="info-box">
                        Multicaixa Express e MBWay Angola confirmam o acesso automaticamente nesta versao. Transferencia bancaria e pagamento presencial ficam pendentes ate confirmacao administrativa.
                    </div>

                    <button type="submit" class="btn-full primary">Gerar pagamento</button>
                </form>
            </div>

            <aside class="summary-card">
                <h3>Resumo da compra</h3>
                @foreach($carrinho as $item)
                    <div><span>{{ $item['titulo'] }}</span><strong>{{ number_format($item['preco'] * ($item['quantidade'] ?? 1), 2, ',', '.') }} Kz</strong></div>
                @endforeach
                <hr>
                <div><span>Subtotal</span><strong>{{ number_format($subtotal, 2, ',', '.') }} Kz</strong></div>
                <div><span>Desconto</span><strong class="green">-{{ number_format($desconto, 2, ',', '.') }} Kz</strong></div>
                <div class="total"><span>Total</span><strong>{{ number_format($total, 2, ',', '.') }} Kz</strong></div>
            </aside>
        </div>
    </div>
</section>
@endsection
