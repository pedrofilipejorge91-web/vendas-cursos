@extends('welcome.apps')

@section('content')

<section class="bg-slate-100 pt-28 pb-16 px-6 md:px-12">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <p class="text-blue-700 font-bold uppercase tracking-widest text-sm">Catalogo</p>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900">Encontre o curso certo</h1>
        </div>

        <form method="GET" action="{{ route('home.catalogo') }}" class="bg-white rounded-lg shadow p-5 grid md:grid-cols-6 gap-4 mb-10">
            <input name="q" value="{{ request('q') }}" class="md:col-span-2 rounded-lg border-slate-300" placeholder="Buscar por palavra-chave">

            <select name="categoria_id" class="rounded-lg border-slate-300">
                <option value="">Categoria</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>{{ $categoria->nome }}</option>
                @endforeach
            </select>

            <select name="idioma" class="rounded-lg border-slate-300">
                <option value="">Idioma</option>
                @foreach($idiomas as $idioma)
                    <option value="{{ $idioma }}" @selected(request('idioma') == $idioma)>{{ $idioma }}</option>
                @endforeach
            </select>

            <input name="preco_max" value="{{ request('preco_max') }}" type="number" min="0" class="rounded-lg border-slate-300" placeholder="Preco max.">
            <input name="duracao_max" value="{{ request('duracao_max') }}" type="number" min="1" class="rounded-lg border-slate-300" placeholder="Duracao max.">

            <div class="md:col-span-6 flex gap-3">
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700">Filtrar</button>
                <a href="{{ route('home.catalogo') }}" class="border border-slate-300 px-6 py-3 rounded-lg font-bold text-slate-700 hover:bg-slate-50">Limpar</a>
            </div>
        </form>

        <div class="grid md:grid-cols-3 gap-6">
            @forelse($cursos as $curso)
                <article class="bg-white rounded-lg overflow-hidden shadow">
                    <img src="{{ $curso->foto ? Storage::url($curso->foto) : asset('assets/img/paruana.png') }}" class="w-full h-48 object-cover" alt="{{ $curso->titulo }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-blue-700">{{ $curso->categoria->nome ?? 'Curso' }}</span>
                            <span class="text-xs text-slate-500">{{ $curso->idioma }}</span>
                        </div>
                        <h2 class="font-black text-xl text-slate-900">{{ $curso->titulo }}</h2>
                        <p class="text-slate-500 mt-2">{{ Str::limit($curso->descricao, 90) }}</p>
                        <div class="flex items-center justify-between mt-5">
                            <span class="font-black text-blue-700">{{ number_format($curso->preco, 2, ',', '.') }} Kz</span>
                            <span class="text-sm text-slate-500">{{ $curso->duracao_horas }}h</span>
                        </div>
                        <a href="{{ route('home.detalhe', $curso->id) }}" class="block text-center mt-5 bg-slate-900 text-white py-3 rounded-lg font-bold hover:bg-blue-700">Ver detalhes</a>
                    </div>
                </article>
            @empty
                <div class="md:col-span-3 bg-white rounded-lg p-10 text-center shadow">
                    <p class="text-slate-500">Nenhum curso encontrado com estes filtros.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $cursos->links() }}
        </div>
    </div>
</section>

@endsection
