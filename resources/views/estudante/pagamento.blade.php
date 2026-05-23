@extends('aluno.apps')

@section('content')

<section class="min-h-screen bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 py-20 px-6 md:px-12">
<div class="max-w-6xl mx-auto">

    <h1 class="text-4xl font-bold mb-10 text-white">
        💳 Finalizar Pagamento
    </h1>

    <div class="grid md:grid-cols-3 gap-8">

        <!-- FORMULÁRIO -->
        <div class="md:col-span-2 bg-white/95 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-white/30">

            <h2 class="text-xl font-bold mb-6 text-slate-800">
                Dados de Pagamento
            </h2>

            <form action="{{ route('pagamento.processar') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Nome -->
                <div>
                    <label class="text-sm text-slate-600">Nome no cartão</label>
                    <input type="text" name="nome"
                        class="w-full mt-1 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        required>
                </div>

                <!-- Número do cartão -->
                <div>
                    <label class="text-sm text-slate-600">Número do cartão</label>
                    <input type="text" name="numero"
                        placeholder="0000 0000 0000 0000"
                        class="w-full mt-1 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                        required>
                </div>

                <!-- Linha -->
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm text-slate-600">Validade</label>
                        <input type="text" name="validade"
                            placeholder="MM/AA"
                            class="w-full mt-1 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            required>
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">CVV</label>
                        <input type="text" name="cvv"
                            placeholder="123"
                            class="w-full mt-1 p-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none"
                            required>
                    </div>

                </div>

                <!-- Botão -->
                <button class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 transition shadow-md mt-4">
                    Pagar Agora
                </button>

            </form>

        </div>

        <!-- RESUMO -->
        <div class="bg-white/95 backdrop-blur-md p-8 rounded-2xl shadow-2xl h-fit border border-white/30">

            <h3 class="text-xl font-bold mb-6 text-slate-800">
                Resumo da Compra
            </h3>

            @php $total = 0; @endphp

            @foreach($carrinho as $item)
                @php $total += $item['preco']; @endphp

                <div class="flex justify-between text-sm mb-2 text-slate-600">
                    <span>{{ $item['titulo'] }}</span>
                    <span>{{ number_format($item['preco'], 2, ',', '.') }} Kz</span>
                </div>
            @endforeach

            <hr class="my-4">

            <div class="flex justify-between text-lg">
                <span>Total:</span>
                <span class="font-bold text-2xl text-blue-600">
                    {{ number_format($total, 2, ',', '.') }} Kz
                </span>
            </div>

        </div>

    </div>

</div>
</section>

@endsection