@extends('aluno.apps')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 py-20 px-6 md:px-12">
<div class="max-w-6xl mx-auto">

    <h1 class="text-4xl font-bold mb-10 text-white">
        🛒 Seu Carrinho
    </h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif

    @if(count($carrinho) > 0)

    <div class="grid md:grid-cols-3 gap-8">

        <!-- LISTA -->
        <div class="md:col-span-2 space-y-6">

            @php $total = 0; @endphp

            @foreach($carrinho as $id => $item)
                @php $total += $item['preco']; @endphp

                <div class="bg-white/95 backdrop-blur-md p-6 rounded-2xl shadow-lg flex gap-6 items-center hover:scale-[1.01] transition">

                    <img src="{{ Storage::url($item['foto']) }}"
                         class="w-28 h-20 object-cover rounded-lg shadow">

                    <div class="flex-1">
                        <h2 class="font-bold text-lg text-slate-800">
                            {{ $item['titulo'] }}
                        </h2>

                        <p class="text-blue-600 font-semibold mt-2 text-lg">
                            {{ number_format($item['preco'], 2, ',', '.') }} Kz
                        </p>
                    </div>

                    <!-- REMOVER -->
                    <form action="{{ route('estudante.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="curso_id" value="{{ $id }}">
                        <button class="text-red-500 hover:text-red-700 font-semibold transition">
                            Remover
                        </button>
                    </form>

                </div>

            @endforeach

        </div>

        <!-- RESUMO -->
        <div class="bg-white/95 backdrop-blur-md p-8 rounded-2xl shadow-2xl h-fit border border-white/30">

            <h3 class="text-xl font-bold mb-6 text-slate-800">Resumo</h3>

            <div class="flex justify-between mb-6 text-lg">
                <span class="text-slate-600">Total:</span>
                <span class="font-bold text-2xl text-blue-600">
                    {{ number_format($total, 2, ',', '.') }} Kz
                </span>
            </div>

            <a href="{{ route('pagamento') }}"
   class="w-full block text-center bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700 transition">
    Finalizar Compra
</a>

        </div>

    </div>

    @else

    <!-- VAZIO -->
    <div class="text-center py-24 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-lg">
        <p class="text-gray-300 text-lg mb-4">
            Seu carrinho está vazio 😢
        </p>

        <a href="{{ url('/') }}"
           class="text-blue-400 font-semibold hover:underline">
            Ver cursos
        </a>
    </div>

    @endif

</div>
</section>

@endsection