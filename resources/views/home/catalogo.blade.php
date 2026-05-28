@extends('welcome.apps')

@section('content')
<section class="page-hero compact">
    <div class="site-container">
        <p class="eyebrow">Catalogo</p>
        <h1>Encontre o curso certo para o seu proximo passo.</h1>
        <p>Filtre por area, idioma, preco e duracao para escolher a formação ideal.</p>
    </div>
</section>

<section class="catalog-page">
    <div class="site-container">
        <form method="GET" action="{{ route('home.catalogo') }}" class="filter-panel">
            <input name="q" value="{{ request('q') }}" placeholder="Buscar por palavra-chave">

            <select name="categoria_id">
                <option value="">Categoria</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>{{ $categoria->nome }}</option>
                @endforeach
            </select>

            <select name="idioma">
                <option value="">Idioma</option>
                @foreach($idiomas as $idioma)
                    <option value="{{ $idioma }}" @selected(request('idioma') == $idioma)>{{ $idioma }}</option>
                @endforeach
            </select>

            <input name="preco_max" value="{{ request('preco_max') }}" type="number" min="0" placeholder="Preco max.">
            <input name="duracao_max" value="{{ request('duracao_max') }}" type="number" min="1" placeholder="Duracao max.">

            <button type="submit"><i class="bi bi-search"></i> Filtrar</button>
            <a href="{{ route('home.catalogo') }}">Limpar</a>
        </form>

        <div class="course-grid">
            @forelse($cursos as $curso)
                <article class="course-card">
                    <figure class="course-media {{ $curso->foto ? '' : 'is-placeholder' }}">
                        <img src="{{ $curso->foto ? Storage::url($curso->foto) : asset('assets/img/logo.png') }}" alt="{{ $curso->titulo }}">
                        <span>{{ $curso->categoria->nome ?? 'Curso' }}</span>
                    </figure>
                    <div class="course-body">
                        <div class="course-meta">
                            <span><i class="bi bi-clock"></i> {{ $curso->duracao_horas ?? 0 }}h</span>
                            <span>{{ $curso->idioma ?? 'pt-AO' }}</span>
                        </div>
                        <h2>{{ $curso->titulo }}</h2>
                        <p>{{ Str::limit($curso->descricao, 105) }}</p>
                        <div class="course-bottom">
                            <strong>{{ number_format($curso->preco, 2, ',', '.') }} Kz</strong>
                            <a href="{{ route('home.detalhe', $curso->id) }}">Detalhes <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <i class="bi bi-search"></i>
                    <p>Nenhum curso encontrado com estes filtros.</p>
                    <a href="{{ route('home.catalogo') }}">Ver todos os cursos</a>
                </div>
            @endforelse
        </div>

        <div class="pagination-wrap">
            {{ $cursos->links() }}
        </div>
    </div>
</section>
@endsection
