

@extends('welcome.apps')

@section('content')

@forelse($cursos as $curso)

<section class="relative py-28 px-6 md:px-12 overflow-hidden bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 text-white">

    <!-- Glow background -->
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-blue-500/20 blur-3xl rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-indigo-500/20 blur-3xl rounded-full"></div>

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-16 items-center relative z-10">

        <!-- TEXTO -->
        <div class="w-full md:w-3/5">

            <!-- Badge + rating -->
            <div class="flex items-center gap-4 mb-6">

                <span class="bg-white/10 backdrop-blur px-4 py-1 rounded-full text-xs uppercase tracking-widest font-semibold border border-white/10">
                    {{ $curso->categoria->nome ?? 'Curso' }}
                </span>

                <div class="flex items-center gap-1 text-yellow-400">
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="font-bold text-white">4.9</span>
                    <span class="text-white/60 text-sm">(1.2k)</span>
                </div>

            </div>

            <!-- Título -->
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6 tracking-tight">
                {{ $curso->titulo }}
            </h1>

            <!-- Descrição -->
            <p class="text-lg text-white/80 mb-10 max-w-xl leading-relaxed">
                {{ Str::limit($curso->descricao, 120) }}
            </p>

            <!-- Stats -->
            <div class="flex flex-wrap gap-10 mb-10">

                <div class="flex items-center gap-3">
                    <div class="bg-white/10 p-3 rounded-lg">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold">8.450+</div>
                        <div class="text-sm text-white/60">Alunos</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-white/10 p-3 rounded-lg">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold">{{ $curso->duracao_horas }}h</div>
                        <div class="text-sm text-white/60">Duração</div>
                    </div>
                </div>

            </div>

            <!-- CTA -->
            <div class="flex gap-4 flex-wrap">

                <a href="{{route('register')}}"
                   class="bg-white text-blue-900 font-bold px-8 py-4 rounded-lg hover:scale-105 hover:shadow-xl transition-all">
                    @php
    $inscrito = auth()->user()
        ->pessoa
        ->estudante
        ->cursos
        ->contains($curso->id);
@endphp

@if($inscrito)

<button class="btn btn-success" disabled>

    <i class="bi bi-check-circle"></i>
    Inscrito

</button>

@else

<form method="POST"
      action="{{ route('curso.inscrever', $curso->id) }}">

    @csrf

    <button type="submit"
            class="btn btn-primary">

        <i class="bi bi-bookmark-plus"></i>
        Inscrever-se

    </button>

</form>

@endif
                </a>

                <a href="{{ route('home.detalhe',$curso->id) }}"
                   class="border border-white/30 px-8 py-4 rounded-lg hover:bg-white/10 transition">
                    Ver detalhes
                </a>

            </div>

        </div>

        <!-- IMAGEM -->
        <div class="w-full md:w-2/5">
            <div class="relative group">

                <!-- glow -->
                <div class="absolute inset-0 bg-blue-500/20 blur-2xl rounded-xl group-hover:bg-blue-500/30 transition"></div>

                <div class="relative rounded-xl overflow-hidden shadow-2xl border border-white/10">
                    <img src="{{ Storage::url($curso->foto) }}"
                         class="w-full h-full object-cover transition duration-700 group-hover:scale-105 group-hover:grayscale-0 grayscale">

                    <!-- overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                </div>

            </div>
        </div>

    </div>

</section>

@empty

<div class="text-center py-24">
    <p class="text-gray-500 text-lg mb-4">
        Ainda não temos cursos disponíveis.
    </p>

    <a href="{{ url('/') }}"
       class="text-blue-600 font-semibold hover:underline">
        Voltar ao início
    </a>
</div>

@endforelse

@endsection