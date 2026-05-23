@extends('welcome.apps')

@section('content')
<section class="min-h-screen bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 py-24 px-6 md:px-12">
<div class="max-w-6xl mx-auto">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-10">
        <div>
            <p class="text-blue-200 text-sm font-semibold uppercase tracking-widest">Compras</p>
            <h1 class="text-4xl font-bold text-white">Seu Carrinho</h1>
        </div>

        <a href="{{ url('/') }}" class="inline-flex items-center justify-center border border-white/30 text-white px-5 py-3 rounded-lg font-semibold hover:bg-white/10 transition">
            Continuar a ver cursos
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-lg mb-6 shadow">
            {{ $errors->first() }}
        </div>
    @endif

    @if(count($carrinho) > 0)

    <div class="grid md:grid-cols-3 gap-8">

        <div class="md:col-span-2 space-y-5">
            @foreach($carrinho as $id => $item)
                <div class="bg-white p-5 rounded-lg shadow-lg flex flex-col sm:flex-row gap-5 sm:items-center">

                    <img src="{{ !empty($item['foto']) ? Storage::url($item['foto']) : asset('assets/img/paruana.png') }}"
                         alt="{{ $item['titulo'] }}"
                         class="w-full sm:w-32 h-24 object-cover rounded-lg bg-slate-100">

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-xs uppercase tracking-widest bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                                {{ $item['categoria'] ?? 'Curso' }}
                            </span>
                            <span class="text-sm text-slate-500">
                                {{ $item['duracao_horas'] ?? 0 }}h
                            </span>
                        </div>

                        <h2 class="font-bold text-lg text-slate-800">
                            {{ $item['titulo'] }}
                        </h2>

                        <p class="text-blue-700 font-bold mt-2 text-lg">
                            {{ number_format($item['preco'], 2, ',', '.') }} Kz
                        </p>
                    </div>

                    <form action="{{ route('carrinho.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="curso_id" value="{{ $id }}">
                        <button type="submit" class="w-full sm:w-auto border border-red-200 text-red-600 px-4 py-3 rounded-lg font-semibold hover:bg-red-50 transition">
                            Remover
                        </button>
                    </form>

                </div>
            @endforeach
        </div>

        <aside class="bg-white p-8 rounded-lg shadow-2xl h-fit border border-white/30">

            <h3 class="text-xl font-bold mb-6 text-slate-800">Resumo</h3>

            <div class="flex justify-between mb-3 text-slate-600">
                <span>Cursos:</span>
                <span class="font-semibold">{{ count($carrinho) }}</span>
            </div>

            <div class="flex justify-between mb-8 text-lg">
                <span class="text-slate-600">Total:</span>
                <span class="font-bold text-2xl text-blue-700">
                    {{ number_format($total, 2, ',', '.') }} Kz
                </span>
            </div>

            <a href="{{ route('pagamento') }}"
               class="w-full block text-center bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700 transition">
                Finalizar Compra
            </a>

            <form action="{{ route('carrinho.clear') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="w-full text-slate-500 py-3 rounded-lg font-semibold hover:bg-slate-100 transition">
                    Limpar carrinho
                </button>
            </form>

        </aside>

    </div>

    @else

    <div class="text-center py-24 bg-white/10 backdrop-blur-md rounded-lg border border-white/20 shadow-lg">
        <p class="text-gray-200 text-lg mb-4">
            Seu carrinho esta vazio.
        </p>

        <a href="{{ url('/') }}"
           class="inline-flex items-center justify-center bg-white text-blue-900 px-6 py-3 rounded-lg font-bold hover:bg-blue-50 transition">
            Ver cursos
        </a>
    </div>

    @endif

</div>
</section>

@endsection
