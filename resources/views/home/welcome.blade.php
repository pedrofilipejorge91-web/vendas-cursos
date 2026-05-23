@extends('welcome.apps')

@section('content')
@php
    $heroCurso = $cursosDestaque->first();
    $heroImagem = $heroCurso?->foto ? Storage::url($heroCurso->foto) : asset('assets/img/logo.png');
    $formatarNumero = fn ($valor) => $valor > 999 ? number_format($valor / 1000, 1, ',', '.') . 'k+' : $valor;
@endphp

<section class="relative min-h-[720px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img alt="Centro de Formação Paruana Comercial" class="w-full h-full object-cover" src="{{ $heroImagem }}">
        <div class="absolute inset-0 bg-gradient-to-r from-primary via-primary/85 to-primary/20"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-8 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 py-28">
        <div class="space-y-8 max-w-2xl">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-widest backdrop-blur">
                Formação profissional em Angola
            </span>

            <h1 class="text-4xl md:text-7xl font-bold text-white leading-tight tracking-tight">
                Aprenda competências práticas para crescer no mercado angolano
            </h1>

            <p class="text-lg md:text-xl text-slate-200/90 font-light leading-relaxed max-w-xl">
                Cursos com instrutores locais, certificado digital e pagamentos adaptados à realidade de Angola.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ route('home.catalogo') }}" class="bg-surface-container-lowest text-primary px-8 py-4 rounded-md font-bold text-lg hover:bg-surface-bright transition-all academic-monolith-shadow text-center">
                    Explorar cursos
                </a>
                @if($heroCurso)
                    <a href="{{ route('home.detalhe', $heroCurso->id) }}" class="border border-white/30 text-white px-8 py-4 rounded-md font-semibold text-lg backdrop-blur-md hover:bg-white/10 transition-all text-center">
                        Curso em destaque
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="bg-surface-container-low py-16">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-extrabold text-primary tracking-tighter">{{ $formatarNumero($metricas['alunos']) }}</div>
                <div class="text-sm font-semibold uppercase tracking-widest text-on-surface-variant">Alunos matriculados</div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-extrabold text-primary tracking-tighter">{{ $metricas['cursos'] }}</div>
                <div class="text-sm font-semibold uppercase tracking-widest text-on-surface-variant">Cursos publicados</div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-extrabold text-primary tracking-tighter">{{ $metricas['formadores'] }}</div>
                <div class="text-sm font-semibold uppercase tracking-widest text-on-surface-variant">Instrutores</div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-extrabold text-primary tracking-tighter">{{ $metricas['avaliacao'] ?: '0' }}/5</div>
                <div class="text-sm font-semibold uppercase tracking-widest text-on-surface-variant">Avaliação média</div>
            </div>
        </div>
    </div>
</section>

<section id="categorias" class="py-28 bg-surface">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="mb-14 space-y-4">
            <span class="inline-block px-4 py-1 rounded-full bg-secondary-container text-on-secondary-fixed text-xs font-bold uppercase tracking-widest">Áreas de formação</span>
            <h2 class="text-4xl md:text-5xl font-bold text-primary tracking-tight">Explore por categoria</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($categorias as $categoria)
                <a href="{{ route('home.categorias', $categoria->id) }}" class="group bg-surface-container-lowest rounded-xl p-8 academic-monolith-shadow transition-transform hover:-translate-y-1">
                    <span class="material-symbols-outlined text-primary text-4xl mb-8 block">school</span>
                    <h3 class="text-2xl font-bold text-primary mb-3">{{ $categoria->nome }}</h3>
                    <p class="text-on-surface-variant text-sm min-h-12">
                        {{ $categoria->descricao ?: 'Cursos práticos preparados para evolução profissional.' }}
                    </p>
                    <div class="mt-8 flex items-center justify-between">
                        <span class="text-sm font-bold text-on-surface-variant">{{ $categoria->cursos_count }} curso(s)</span>
                        <span class="material-symbols-outlined text-primary group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </a>
            @empty
                <div class="md:col-span-3 bg-white rounded-xl p-10 text-center academic-monolith-shadow">
                    <p class="text-on-surface-variant">Ainda não há categorias cadastradas.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section id="cursos" class="py-28 bg-surface-container-low">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-14 gap-6">
            <div class="space-y-4">
                <h2 class="text-4xl md:text-5xl font-bold text-primary tracking-tight">Cursos em destaque</h2>
                <p class="text-on-surface-variant max-w-xl">Programas publicados e prontos para inscrição, ordenados pela procura dos alunos.</p>
            </div>
            <a href="{{ route('home.catalogo') }}" class="text-primary font-bold flex items-center gap-2 hover:translate-x-1 transition-transform">
                Ver catálogo completo
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($cursosDestaque as $curso)
                <article class="bg-surface-container-lowest rounded-xl overflow-hidden group">
                    <div class="relative h-48 overflow-hidden">
                        <img alt="{{ $curso->titulo }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $curso->foto ? Storage::url($curso->foto) : asset('assets/img/paruana.png') }}">
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">{{ $curso->categoria->nome ?? 'Curso' }}</span>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <h3 class="text-xl font-bold text-primary group-hover:text-on-primary-fixed-variant transition-colors">{{ $curso->titulo }}</h3>
                        <p class="text-sm text-on-surface-variant">{{ Illuminate\Support\Str::limit($curso->descricao, 100) }}</p>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-on-surface-variant">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">schedule</span> {{ $curso->duracao_horas }}h</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">groups</span> {{ $curso->matriculas_count }} aluno(s)</span>
                            <span>{{ $curso->idioma ?? 'pt-AO' }}</span>
                        </div>
                        <div class="flex justify-between items-center gap-4 pt-2">
                            <div class="text-2xl font-extrabold text-primary">{{ number_format($curso->preco, 2, ',', '.') }} Kz</div>
                            <a href="{{ route('home.detalhe', $curso->id) }}" class="text-primary font-bold flex items-center gap-2 hover:translate-x-1 transition-transform whitespace-nowrap">
                                Detalhes <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="md:col-span-3 bg-white rounded-xl p-10 text-center academic-monolith-shadow">
                    <p class="text-on-surface-variant">Ainda não há cursos publicados. Publique cursos no painel admin para aparecerem aqui.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section id="formadores" class="py-28 bg-surface">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            <div class="space-y-6">
                <span class="inline-block px-4 py-1 rounded-full bg-secondary-container text-on-secondary-fixed text-xs font-bold uppercase tracking-widest">Instrutores</span>
                <h2 class="text-4xl md:text-5xl font-bold text-primary tracking-tight leading-tight">Aprenda com formadores ligados ao mercado</h2>
                <p class="text-on-surface-variant text-lg">Cada curso publicado fica associado a um instrutor, permitindo ao aluno conhecer quem conduz a formação.</p>
            </div>

            <div class="space-y-4">
                @forelse($formadores as $formador)
                    <div class="bg-surface-container-lowest p-6 rounded-xl academic-monolith-shadow flex items-center gap-5">
                        <div class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center font-black text-xl">
                            {{ strtoupper(substr($formador->pessoa->primeironome ?? 'F', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-primary">{{ trim(($formador->pessoa->primeironome ?? '') . ' ' . ($formador->pessoa->segundonome ?? '')) ?: 'Formador' }}</h3>
                            <p class="text-sm text-on-surface-variant">{{ $formador->especialidade ?: 'Formação profissional' }}</p>
                        </div>
                        <span class="text-sm font-bold text-primary">{{ $formador->cursos_count }} curso(s)</span>
                    </div>
                @empty
                    <div class="bg-white rounded-xl p-10 text-center academic-monolith-shadow">
                        <p class="text-on-surface-variant">Ainda não há formadores cadastrados.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="py-24 bg-primary">
    <div class="max-w-5xl mx-auto px-6 md:px-8 text-center space-y-8">
        <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight">Comece pelo curso certo para o seu objectivo</h2>
        <p class="text-slate-300 text-lg">Filtre por categoria, preço, duração e idioma no catálogo.</p>
        <a href="{{ route('home.catalogo') }}" class="inline-flex bg-white text-primary px-10 py-4 rounded-md font-bold hover:bg-slate-100 transition-all">
            Abrir catálogo
        </a>
    </div>
</section>
@endsection
