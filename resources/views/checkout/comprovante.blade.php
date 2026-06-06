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
                    <h2>Enviar comprovativo</h2>
                    <p>Depois de pagar pelo Multicaixa Express ou transferencia bancaria, envie o PDF original gerado pelo banco para validacao automatica.</p>

                    <form action="{{ route('pagamento.comprovativo.enviar', $pedido) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="comprovativo" accept=".pdf,application/pdf" required>
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
@endsection
