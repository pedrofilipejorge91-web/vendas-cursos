@extends('estudant-layouts.apps')

@section('content')

<section class="py-24 px-6 md:px-12 bg-gradient-to-br from-blue-950 to-slate-900 text-white">
<div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">

    <!-- INFO -->
    <div>

        <!-- Categoria -->
        <span class="bg-white/10 px-4 py-1 rounded-full text-xs uppercase">
            {{ $cursos->categoria->nome ?? 'Curso' }}
        </span>

        <!-- Título -->
        <h1 class="text-5xl font-bold mt-6 mb-6">
            {{ $cursos->titulo }}
        </h1>

        <!-- Descrição -->
        <p class="text-white/80 mb-8 leading-relaxed">
            {{ $cursos->descricao }}
        </p>

        <!-- Stats -->
        <div class="flex gap-8 mb-10">

            <div>
                <div class="text-2xl font-bold">{{ $cursos->duracao_horas }}h</div>
                <div class="text-sm text-white/60">Duração</div>
            </div>

            <div>
                <div class="text-2xl font-bold">Certificado</div>
                <div class="text-sm text-white/60">Incluído</div>
            </div>

        </div>

        <!-- Formador -->
        <div class="mb-10">
            <h3 class="font-semibold text-white/70">Instrutor</h3>
            <p class="text-xl font-bold">
                {{ $cursos->formador->pessoa->primeironome ?? 'Instrutor' }}
            </p>
        </div>

    </div>

    <!-- CARD COMPRA -->
    <div class="bg-white text-slate-900 rounded-2xl p-8 shadow-2xl">

        <!-- Imagem -->
        <img src="{{ Storage::url($cursos->foto) }}"
             class="rounded-xl mb-6 w-full h-56 object-cover">

        <!-- Preço -->
        <div class="mb-6">
            <span class="text-4xl font-black">
                {{ number_format($cursos->preco, 2, ',', '.') }} Kz
            </span>
        </div>

        <!-- FORM ADD CARRINHO--> 
        <form action="{{ route('estudante.add') }}" method="POST">
            @csrf
            <input type="hidden" name="curso_id" value="{{ $cursos->id }}">

            <button type="submit"
                class="w-full bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700 transition mb-4">
                Adicionar ao Carrinho
            </button>
        </form>

        <!-- Comprar direto -->
        <a href="#"
           class="block text-center w-full border border-blue-600 text-blue-600 py-4 rounded-lg font-bold hover:bg-blue-50 transition">
            Comprar Agora
        </a>

        <!-- Benefícios -->
        <ul class="mt-6 text-sm text-gray-600 space-y-2">
            <li>✔ Acesso vitalício</li>
            <li>✔ Certificado</li>
            <li>✔ Suporte</li>
        </ul>

    </div>

</div>
</section>

@endsection