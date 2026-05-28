@extends('welcome.apps')

@section('content')
<section class="page-hero compact">
    <div class="site-container">
        <p class="eyebrow">Pagamento</p>
        <h1>Finalizar compra</h1>
        <p>Escolha o metodo de pagamento e envie os dados necessarios para liberar o acesso.</p>
    </div>
<<<<<<< HEAD

    @if($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-lg mb-6 shadow">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid md:grid-cols-3 gap-8">

        <div class="md:col-span-2 bg-white p-8 rounded-lg shadow-2xl border border-white/30">

            <h2 class="text-xl font-bold mb-6 text-slate-800">
                Metodo de pagamento
            </h2>

            <form action="{{ route('pagamento.processar') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach($metodos as $valor => $label)
                        <label class="border border-slate-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="metodo_pagamento" value="{{ $valor }}" class="mr-2" @checked(old('metodo_pagamento') === $valor || $loop->first)>
                            <span class="font-semibold text-slate-800">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <div>
                    <label class="text-sm text-slate-600">Telefone para confirmacao</label>
                    <input type="text" name="telefone" value="{{ old('telefone') }}"
                        class="w-full mt-1 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="+244 900 000 000">
                </div>

                <div>
                    <label class="text-sm text-slate-600">Comprovativo</label>
                    <input type="file" name="comprovativo"
                        class="w-full mt-1 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        accept=".jpg,.jpeg,.png,.pdf">
                    <p class="text-xs text-slate-500 mt-1">Aceita JPG, PNG ou PDF ate 4MB.</p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-sm text-blue-900">
                    Multicaixa Express e MBWay Angola confirmam o acesso automaticamente nesta versao. Transferencia bancaria e pagamento presencial ficam pendentes ate confirmacao administrativa.
                </div>

                <button class="w-full bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700 transition shadow-md">
                    Gerar pagamento
                </button>

            </form>

        </div>

        <aside class="bg-white p-8 rounded-lg shadow-2xl h-fit border border-white/30">

            <h3 class="text-xl font-bold mb-6 text-slate-800">
                Resumo da Compra
            </h3>

            @foreach($carrinho as $item)
                <div class="flex justify-between gap-4 text-sm mb-3 text-slate-600">
                    <span>{{ $item['titulo'] }}</span>
                    <span class="font-semibold">{{ number_format($item['preco'] * ($item['quantidade'] ?? 1), 2, ',', '.') }} Kz</span>
                </div>
            @endforeach

            <hr class="my-4">

            <div class="mb-5">
                <h4 class="font-bold text-slate-800 mb-3">Cupom de desconto</h4>
                @if($cupom)
                    <div class="flex items-center justify-between gap-3 bg-green-50 border border-green-100 rounded-lg p-3 text-sm text-green-800">
                        <span>{{ $cupom->codigo }} aplicado</span>
                        <form action="{{ route('pagamento.cupom.remover') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-bold text-green-900 underline">Remover</button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('pagamento.cupom.aplicar') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="codigo" value="{{ old('codigo') }}" placeholder="Codigo do cupom"
                            class="w-full p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none">
                        <button type="submit" class="px-4 rounded-lg bg-slate-900 text-white font-bold hover:bg-slate-800 transition">
                            Aplicar
                        </button>
                    </form>
                @endif
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>{{ number_format($subtotal, 2, ',', '.') }} Kz</span>
                </div>
                <div class="flex justify-between text-green-700">
                    <span>Desconto:</span>
                    <span>-{{ number_format($desconto, 2, ',', '.') }} Kz</span>
                </div>
            </div>

            <div class="flex justify-between text-lg mt-4">
                <span>Total:</span>
                <span class="font-bold text-2xl text-blue-700">
                    {{ number_format($total, 2, ',', '.') }} Kz
                </span>
            </div>

        </aside>

    </div>

</div>
=======
>>>>>>> 02ed285 (Atualizacao do projeto)
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
