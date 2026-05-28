@extends('welcome.apps')

@section('content')
<section class="page-hero compact">
    <div class="site-container">
        <p class="eyebrow">Categoria</p>
        <h1>{{ $categoria->nome ?? 'Cursos' }}</h1>
        <p>Explore as formacoes disponiveis nesta area.</p>
    </div>
</section>

<section class="catalog-page">
    <div class="site-container">
        <div class="course-grid">
            @forelse($cursos as $curso)
                <article class="course-card">
                    <figure class="course-media {{ $curso->foto ? '' : 'is-placeholder' }}">
                        <img src="{{ $curso->foto ? Storage::url($curso->foto) : asset('assets/img/logo.png') }}" alt="{{ $curso->titulo }}">
                        <span>{{ $categoria->nome ?? 'Curso' }}</span>
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
                    <i class="bi bi-journal-x"></i>
                    <p>Ainda nao temos cursos disponiveis nesta categoria.</p>
                    <a href="{{ route('home.catalogo') }}">Voltar ao catalogo</a>
                </div>
            @endforelse
        </div>

        <div class="pagination-wrap">
            {{ $cursos->links() }}
        </div>
    </div>
</section>
@endsection
