@extends('welcome.apps')

@section('content')
<section class="receipt-page">
    <div class="site-container">
        <article class="receipt-card">
            <header>
                <div>
                    <p class="eyebrow">Comprovante de pagamento</p>
                    <h1>Pedido {{ $pedido->referencia }}</h1>
                    <span>Centro de Formação Paruana Comercial</span>
                </div>
                <div class="receipt-status">
                    <strong class="{{ $pedido->status === 'pago' ? 'paid' : 'pending' }}">{{ ucfirst($pedido->status) }}</strong>
                    <span>{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </header>

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

            @if($gatewayDescription)
                <div class="info-box">
                    <strong>Instrucoes de pagamento:</strong>
                    <p>{{ $gatewayDescription }}</p>
                </div>
            @endif

            @if($pedido->status === 'pendente' && $pedido->user_id === Auth::id())
                <div class="receipt-upload">
                    <h2>Dados Bancários para Transferência</h2>
                    <div class="bank-details">
                        <div class="bank-detail">
                            <strong>Beneficiário:</strong>
                            <span>{{ config('services.sudopay.meu_nome_beneficiario') }}</span>
                        </div>
                        <div class="bank-detail">
                            <strong>IBAN:</strong>
                            <span class="iban">{{ config('services.sudopay.meu_iban') }}</span>
                        </div>
                        <div class="bank-detail">
                            <strong>Valor a pagar:</strong>
                            <span>{{ number_format($pedido->total, 2, ',', '.') }} Kz</span>
                        </div>
                    </div>

                    <h2 style="margin-top:30px;">Enviar comprovativo</h2>
                    <p>Depois de pagar pelo Multicaixa Express ou transferencia bancaria, envie o PDF original gerado pelo banco para validacao automatica.</p>

                    <form action="{{ route('pagamento.comprovativo.enviar', $pedido) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-box">
                            <label class="field-label">Comprovativo em PDF</label>
                            <input type="file" name="comprovativo" accept=".pdf,application/pdf" required>
                            <small>Formato aceite: PDF (Maximo 4MB). Apenas comprovativos originais gerados pelo banco.</small>
                        </div>
                        <button type="submit" class="btn-full primary">Validar comprovativo</button>
                    </form>
                </div>
            @endif

            <div class="receipt-grid">
                <div>
                    <h2>Dados do aluno</h2>
                    <p>{{ $pedido->user->nome_completo }}</p>
                    <span>{{ $pedido->user->email }}</span>
                </div>
                <div>
                    <h2>Referencia</h2>
                    <p class="reference">{{ $pedido->pagamento->referencia }}</p>
                    <span>{{ str_replace('_', ' ', ucfirst($pedido->pagamento->metodo)) }}</span>
                    @if($pedido->pagamento->comprovativo)
                        <a href="{{ Storage::url($pedido->pagamento->comprovativo) }}" target="_blank">Ver comprovativo enviado</a>
                    @endif
                    @if($pedido->expira_em)
                        <small>Confirmar ate {{ $pedido->expira_em->format('d/m/Y H:i') }}</small>
                    @endif
                </div>
            </div>

            <div class="receipt-table">
                @foreach($pedido->itens as $item)
                    <div>
                        <span>{{ $item->titulo }}</span>
                        <strong>{{ number_format($item->preco, 2, ',', '.') }} Kz</strong>
                    </div>
                @endforeach
            </div>

            <div class="receipt-total">
                <div><span>Subtotal</span><strong>{{ number_format($pedido->subtotal, 2, ',', '.') }} Kz</strong></div>
                <div><span>Desconto</span><strong class="green">-{{ number_format($pedido->desconto, 2, ',', '.') }} Kz</strong></div>
                <div><span>Total</span><strong>{{ number_format($pedido->total, 2, ',', '.') }} Kz</strong></div>
            </div>

            <div class="receipt-actions {{ $pedido->status === 'pago' ? '' : 'single' }}">
                @if($pedido->status === 'pago')
                    <a href="{{ route('dashboard') }}" class="btn-full primary">Ir para meus cursos</a>
                @endif
                <a href="{{ route('home.catalogo') }}" class="btn-full ghost">Voltar ao catalogo</a>
            </div>
        </article>
    </div>
</section>

<style>
.receipt-card{
    background:#fff;
    padding:40px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    max-width:800px;
    margin:30px auto;
}

.receipt-card header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:30px;
    padding-bottom:20px;
    border-bottom:2px solid #e5e7eb;
}

.receipt-status{
    text-align:right;
}

.receipt-status strong.paid{
    color:#16a34a;
    font-size:18px;
}

.receipt-status strong.pending{
    color:#f59e0b;
    font-size:18px;
}

.receipt-status span{
    display:block;
    color:#6b7280;
    margin-top:5px;
}

.receipt-upload{
    background:#f9fafb;
    padding:25px;
    border-radius:14px;
    margin-bottom:30px;
}

.receipt-upload h2{
    margin-bottom:15px;
}

.bank-details{
    background:#fff;
    padding:20px;
    border-radius:12px;
    margin-bottom:20px;
}

.bank-detail{
    display:flex;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px solid #e5e7eb;
}

.bank-detail:last-child{
    border-bottom:none;
}

.bank-detail strong{
    color:#374151;
}

.bank-detail span{
    color:#111827;
    font-weight:600;
}

.iban{
    font-family:monospace;
    font-size:16px;
    letter-spacing:1px;
}

.upload-box{
    margin-top:15px;
    padding:20px;
    border:2px dashed #d1d5db;
    border-radius:14px;
    background:#fff;
}

.upload-box small{
    display:block;
    margin-top:10px;
    color:#6b7280;
}

.receipt-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px;
    margin-bottom:30px;
}

.receipt-grid h2{
    margin-bottom:10px;
    font-size:16px;
}

.receipt-grid p{
    margin-bottom:5px;
}

.receipt-grid span{
    color:#6b7280;
}

.receipt-grid a{
    display:block;
    margin-top:10px;
    color:#2563eb;
    text-decoration:none;
}

.receipt-grid small{
    display:block;
    margin-top:10px;
    color:#f59e0b;
}

.reference{
    font-family:monospace;
    font-size:18px;
    font-weight:700;
    color:#111827;
}

.receipt-table{
    margin-bottom:20px;
}

.receipt-table div{
    display:flex;
    justify-content:space-between;
    padding:12px 0;
    border-bottom:1px solid #e5e7eb;
}

.receipt-total{
    margin-top:20px;
}

.receipt-total div{
    display:flex;
    justify-content:space-between;
    padding:10px 0;
}

.receipt-total div:last-child{
    font-size:20px;
    font-weight:700;
    border-top:2px solid #e5e7eb;
    padding-top:15px;
    margin-top:10px;
}

.receipt-actions{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
    margin-top:30px;
}

.receipt-actions.single{
    grid-template-columns:1fr;
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
}

.primary{
    background:#2563eb;
    color:#fff;
}

.primary:hover{
    opacity:.9;
}

.ghost{
    background:#f3f4f6;
    color:#374151;
}

.ghost:hover{
    background:#e5e7eb;
}

.info-box{
    margin-bottom:20px;
    background:#eff6ff;
    border-left:4px solid #2563eb;
    padding:16px;
    border-radius:12px;
}

.info-box strong{
    display:block;
    margin-bottom:8px;
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

.field-label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
}

input[type="file"]{
    width:100%;
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:10px;
}

.green{
    color:#16a34a;
}

@media(max-width:768px){
    .receipt-card{
        padding:20px;
    }

    .receipt-card header{
        flex-direction:column;
        gap:15px;
    }

    .receipt-status{
        text-align:left;
    }

    .receipt-grid{
        grid-template-columns:1fr;
    }

    .receipt-actions{
        grid-template-columns:1fr;
    }
}
</style>
@endsection