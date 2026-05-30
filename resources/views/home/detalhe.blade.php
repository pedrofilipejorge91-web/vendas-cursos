@extends('welcome.apps')

@section('content')
@php
    $courseImage = $cursos->foto ? Storage::url($cursos->foto) : asset('assets/img/logo.png');
@endphp

<section class="course-detail-hero">
    <div class="site-container detail-hero-grid">
        <div>
            <span class="detail-badge">{{ $cursos->categoria->nome ?? 'Curso' }}</span>
            <h1>{{ $cursos->titulo }}</h1>
            <p>{{ $cursos->descricao }}</p>
            <div class="detail-stats">
                <span><strong>{{ $cursos->duracao_horas ?? 0 }}h</strong>Duração</span>
                <span><strong>Certificado</strong>Incluido</span>
                <span><strong>{{ $cursos->idioma ?? 'pt-AO' }}</strong>Idioma</span>
            </div>
        </div>

        <aside class="checkout-card">
            <figure class="checkout-media {{ $cursos->foto ? '' : 'is-placeholder' }}">
                <img src="{{ $courseImage }}" alt="{{ $cursos->titulo }}" loading="lazy">
            </figure>
            <div class="checkout-price">{{ number_format($cursos->preco, 2, ',', '.') }} Kz</div>
            <form action="{{ route('carrinho.add') }}" method="POST">
                @csrf
                <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                <button type="submit" class="btn-full primary">Adicionar ao carrinho</button>
            </form>
            <form action="{{ route('carrinho.buy-now') }}" method="POST">
                @csrf
                <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                <button type="submit" class="btn-full ghost">Comprar agora</button>
            </form>
            <ul>
                <li><i class="bi bi-check2-circle"></i> Acesso ao conteudo</li>
                <li><i class="bi bi-check2-circle"></i> Certificado apos aprovacao</li>
                <li><i class="bi bi-check2-circle"></i> Suporte do formador</li>
            </ul>
        </aside>
    </div>
</section>

<section class="detail-content">
    <div class="site-container detail-content-grid">
        <div class="content-panel">
            <h2>Programa do curso</h2>
            <div class="lesson-list">
                @forelse($aulas as $aula)
                    <article>
                        <span>{{ $aula->numero_aula }}</span>
                        <div>
                            <h3>{{ $aula->titulo }}</h3>
                            <p>{{ ucfirst($aula->tipo) }} · {{ $aula->duracao_minutos }} min</p>
                        </div>
                    </article>
                @empty
                    <p class="muted">Programa ainda nao cadastrado.</p>
                @endforelse
            </div>
        </div>

        <div class="content-panel">
            <h2>Avaliacoes</h2>
            <div class="review-list">
                @forelse($cursos->avaliacoes as $avaliacao)
                    <article>
                        <div class="review-stars" aria-label="{{ $avaliacao->nota }} de 5 estrelas">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $avaliacao->nota ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                            <strong>{{ $avaliacao->nota }}/5</strong>
                        </div>
                        <p>{{ $avaliacao->comentario }}</p>
                        <small>{{ $avaliacao->estudante->pessoa->primeironome ?? 'Aluno' }}</small>
                        @if($avaliacao->resposta_instrutor)
                            <div class="instructor-reply">
                                <b>Resposta do instrutor</b>
                                <p>{{ $avaliacao->resposta_instrutor }}</p>
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="muted">Ainda nao ha avaliacoes.</p>
                @endforelse
            </div>

            @auth
                @if($minhaMatricula && $minhaMatricula->progresso >= 50)
                    @include('components.course-rating-form', ['curso' => $cursos, 'minhaAvaliacao' => $minhaAvaliacao])
                @elseif($minhaMatricula)
                    <p class="muted">Conclua pelo menos 50% do curso para avaliar.</p>
                @endif
            @endauth
        </div>
    </div>
</section>
@endsection
