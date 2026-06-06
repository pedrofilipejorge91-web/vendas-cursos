@extends('welcome.apps')

@section('content')
<section class="page-hero compact">
    <div class="site-container">
        <p class="eyebrow">Pagamento</p>
        <h1>Finalizar Compra</h1>
        <p>Escolha o método de pagamento e envie os dados necessários para concluir a sua inscrição.</p>
    </div>
</section>

<section class="checkout-page">
    <div class="site-container">

        @if($errors->any())
            <div class="alert-card error">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert-card success">
                {{ session('success') }}
            </div>
        @endif

        <div class="cart-grid">

            <!-- FORMULÁRIO -->
            <div class="payment-panel">

                <h2>Método de Pagamento</h2>

                <form action="{{ route('pagamento.processar') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="payment-methods">

                        @foreach($metodos as $valor => $label)
                            <label class="payment-option">

                                <input
                                    type="radio"
                                    name="metodo_pagamento"
                                    value="{{ $valor }}"
                                    @checked(old('metodo_pagamento') === $valor || $loop->first)
                                >

                                <div class="payment-card">

                                    <div class="payment-icon">
                                        @if($valor === 'multicaixa_express')
                                            📱
                                        @else
                                            🏦
                                        @endif
                                    </div>

                                    <div>
                                        <strong>{{ $label }}</strong>

                                        <small>
                                            @if($valor === 'multicaixa_express')
                                                Pagamento através do Multicaixa Express.
                                            @else
                                                Transferência bancária para a conta indicada.
                                            @endif
                                        </small>
                                    </div>

                                </div>

                            </label>
                        @endforeach

                    </div>

                    <div class="form-group">
                        <label class="field-label">
                            Número de Telefone
                        </label>

                        <input
                            type="text"
                            name="telefone"
                            value="{{ old('telefone') }}"
                            placeholder="+244 9XX XXX XXX"
                        >
                    </div>

                    <div class="upload-box">

                        <label class="field-label">
                            Comprovativo de Pagamento
                        </label>

                        <input
                            type="file"
                            name="comprovativo"
                            accept=".jpg,.jpeg,.png,.pdf"
                        >

                        <small>
                            Formatos aceites: PDF, JPG ou PNG (Máximo 4MB).
                        </small>

                    </div>

                    <div class="info-box">
                        <strong>Validação Automática</strong>

                        <p>
                            Após o envio do comprovativo, o sistema verifica
                            automaticamente os dados da transferência antes
                            da liberação do acesso ao curso.
                        </p>
                    </div>

                    <button type="submit" class="btn-full primary">
                        Confirmar Pagamento
                    </button>

                </form>

            </div>

            <!-- RESUMO -->
            <aside class="summary-card">

                <h3>Resumo da Compra</h3>

                @foreach($carrinho as $item)
                    <div class="summary-line">
                        <span>{{ $item['titulo'] }}</span>

                        <strong>
                            {{ number_format($item['preco'] * ($item['quantidade'] ?? 1), 2, ',', '.') }} Kz
                        </strong>
                    </div>
                @endforeach

                <hr>

                <div class="summary-line">
                    <span>Subtotal</span>
                    <strong>{{ number_format($subtotal, 2, ',', '.') }} Kz</strong>
                </div>

                <div class="summary-line">
                    <span>Desconto</span>
                    <strong class="green">
                        -{{ number_format($desconto, 2, ',', '.') }} Kz
                    </strong>
                </div>

                <div class="summary-line total">
                    <span>Total</span>

                    <strong>
                        {{ number_format($total, 2, ',', '.') }} Kz
                    </strong>
                </div>

            </aside>

        </div>
    </div>
</section>

<style>

.cart-grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:30px;
    margin-top:30px;
}

.payment-panel{
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.payment-panel h2{
    margin-bottom:25px;
}

.payment-methods{
    display:grid;
    gap:15px;
    margin-bottom:25px;
}

.payment-option input{
    display:none;
}

.payment-card{
    display:flex;
    align-items:center;
    gap:15px;
    padding:18px;
    border:2px solid #e5e7eb;
    border-radius:14px;
    cursor:pointer;
    transition:.3s;
}

.payment-card:hover{
    transform:translateY(-2px);
}

.payment-option input:checked + .payment-card{
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,.15);
}

.payment-icon{
    font-size:32px;
}

.payment-card strong{
    display:block;
    margin-bottom:4px;
}

.payment-card small{
    color:#6b7280;
}

.form-group{
    margin-bottom:20px;
}

.field-label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
}

input[type="text"],
input[type="file"]{
    width:100%;
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:10px;
}

.upload-box{
    margin-top:15px;
    padding:20px;
    border:2px dashed #d1d5db;
    border-radius:14px;
    background:#f9fafb;
}

.upload-box small{
    display:block;
    margin-top:10px;
    color:#6b7280;
}

.info-box{
    margin-top:20px;
    background:#eff6ff;
    border-left:4px solid #2563eb;
    padding:16px;
    border-radius:12px;
}

.info-box strong{
    display:block;
    margin-bottom:8px;
}

.btn-full{
    width:100%;
    margin-top:25px;
    padding:14px;
    border:none;
    border-radius:12px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
}

.primary{
    background:#2563eb;
    color:#fff;
}

.primary:hover{
    opacity:.9;
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

.summary-line{
    display:flex;
    justify-content:space-between;
    margin-bottom:12px;
}

.summary-card hr{
    margin:15px 0;
}

.green{
    color:#16a34a;
}

.total{
    font-size:18px;
    font-weight:700;
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

}

</style>
@endsection