@extends('welcome.apps')

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

        <div class="mb-10">
            <h3 class="font-semibold text-white/70">Idioma</h3>
            <p class="text-xl font-bold">{{ $cursos->idioma ?? 'pt-AO' }}</p>
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
        <form action="{{ route('carrinho.add') }}" method="POST">
            @csrf
            <input type="hidden" name="curso_id" value="{{ $cursos->id }}">

            <button type="submit"
                class="w-full bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700 transition mb-4">
                Adicionar ao Carrinho
            </button>
        </form>

        <!-- Comprar direto -->
        <form action="{{ route('carrinho.buy-now') }}" method="POST">
            @csrf
            <input type="hidden" name="curso_id" value="{{ $cursos->id }}">

            <button type="submit"
                class="w-full border border-blue-600 text-blue-600 py-4 rounded-lg font-bold hover:bg-blue-50 transition">
                Comprar Agora
            </button>
        </form>

        <!-- Benefícios -->
        <ul class="mt-6 text-sm text-gray-600 space-y-2">
            <li>✔ Acesso vitalício</li>
            <li>✔ Certificado</li>
            <li>✔ Suporte</li>
        </ul>

    </div>

</div>
</section>

<section class="bg-white py-16 px-6 md:px-12">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-10">
        <div>
            <h2 class="text-2xl font-black text-slate-900 mb-6">Programa do curso</h2>
            <div class="space-y-3">
                @forelse($aulas as $aula)
                    <div class="border border-slate-200 rounded-lg p-4">
                        <p class="font-bold">{{ $aula->numero_aula }}. {{ $aula->titulo }}</p>
                        <p class="text-sm text-slate-500">{{ $aula->tipo }} · {{ $aula->duracao_minutos }} min</p>
                    </div>
                @empty
                    <p class="text-slate-500">Programa ainda nao cadastrado.</p>
                @endforelse
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-black text-slate-900 mb-6">Avaliacoes</h2>
            <div class="space-y-4 mb-8">
                @forelse($cursos->avaliacoes as $avaliacao)
                    <div class="border border-slate-200 rounded-lg p-4">
                        <p class="font-bold">{{ str_repeat('*', $avaliacao->nota) }} <span class="text-slate-500">({{ $avaliacao->nota }}/5)</span></p>
                        <p class="text-slate-600 mt-2">{{ $avaliacao->comentario }}</p>
                        <p class="text-xs text-slate-400 mt-2">{{ $avaliacao->estudante->pessoa->primeironome ?? 'Aluno' }}</p>
                        @if($avaliacao->resposta_instrutor)
                            <div class="mt-4 rounded-lg bg-slate-50 p-3">
                                <p class="text-xs font-bold text-slate-500">Resposta do instrutor</p>
                                <p class="text-sm text-slate-700 mt-1">{{ $avaliacao->resposta_instrutor }}</p>
                            </div>
                        @endif
                        @auth
                            @php
                                $podeResponder = Auth::user()->tipo === 'admin' || $cursos->formador_id === Auth::user()->pessoa?->formador?->id;
                            @endphp
                            @if($podeResponder)
                                <form action="{{ route('avaliacoes.responder', $avaliacao) }}" method="POST" class="mt-4">
                                    @csrf
                                    <textarea name="resposta_instrutor" class="w-full rounded-lg border-slate-300 mb-2" rows="2" placeholder="Responder comentario">{{ old('resposta_instrutor', $avaliacao->resposta_instrutor) }}</textarea>
                                    <button class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Responder</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @empty
                    <p class="text-slate-500">Ainda nao ha avaliacoes.</p>
                @endforelse
            </div>

            @auth
                @if($minhaMatricula && $minhaMatricula->progresso >= 70)
                    <form action="{{ route('avaliacoes.store', $cursos) }}" method="POST" class="border border-slate-200 rounded-lg p-5">
                        @csrf
                        <h3 class="font-bold mb-4">Avaliar este curso</h3>
                        <select name="nota" class="w-full rounded-lg border-slate-300 mb-3" required>
                            <option value="">Nota</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} estrela(s)</option>
                            @endfor
                        </select>
                        <textarea name="comentario" class="w-full rounded-lg border-slate-300 mb-3" rows="4" placeholder="Comentario"></textarea>
                        <button class="bg-blue-600 text-white px-5 py-3 rounded-lg font-bold">Enviar avaliacao</button>
                    </form>
                @elseif($minhaMatricula)
                    <p class="text-sm text-slate-500">Conclua pelo menos 70% do curso para avaliar.</p>
                @endif
            @endauth
        </div>
    </div>
</section>

@endsection
