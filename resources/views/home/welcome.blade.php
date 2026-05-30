@extends('welcome.apps')

@section('content')
@php
    $heroCurso = $cursosDestaque->first();
    $heroImagem = $heroCurso?->foto ? Storage::url($heroCurso->foto) : asset('assets/img/logo.png');
    $formatarNumero = fn ($valor) => $valor > 999 ? number_format($valor / 1000, 1, ',', '.') . 'k+' : $valor;
    $paruanaImages = [
        ['src' => asset('assets/img/paruana/electricidade-alunos-paruana.png'), 'title' => 'Electricidade pratica', 'copy' => 'Alunos aplicam energia, tecnologia e instalacao em projectos reais'],
        ['src' => asset('assets/img/paruana/decoracao-alunas-paruana.png'), 'title' => 'Decoracao e criatividade', 'copy' => 'Formacao profissional com producao manual e acompanhamento'],
        ['src' => asset('assets/img/paruana/atendimento-tecnico-paruana.png'), 'title' => 'Orientacao e suporte', 'copy' => 'Atendimento preparado para guiar o aluno em cada etapa'],
        ['src' => asset('assets/img/paruana/director-paruana.png'), 'title' => 'Lideranca Paruana', 'copy' => 'Uma equipa comprometida com educacao, disciplina e resultados'],
    ];

    $servicos = $categorias->map(function ($categoria) {
        return [
            'titulo' => $categoria->nome,
            'descricao' => 'Programas praticos para desenvolver competencias valorizadas no mercado.',
            'icon' => 'bi-mortarboard',
            'link' => route('home.categorias', $categoria->id),
            'items' => ['Aulas orientadas', 'Certificado', 'Suporte do formador'],
        ];
    });

    if ($servicos->isEmpty()) {
        $servicos = collect([
            ['titulo' => 'Consultoria e formação', 'descricao' => 'Percursos de aprendizagem para empresas e profissionais.', 'icon' => 'bi-building', 'link' => route('home.catalogo'), 'items' => ['Gestao', 'Tecnologia', 'Financas']],
            ['titulo' => 'Treinamento profissional', 'descricao' => 'Conteudos objectivos para aplicacao directa no trabalho.', 'icon' => 'bi-layers', 'link' => route('home.catalogo'), 'items' => ['Estudo guiado', 'Exercicios', 'Mentoria']],
            ['titulo' => 'Cursos online', 'descricao' => 'Acesso flexivel a aulas, materiais e certificado.', 'icon' => 'bi-laptop', 'link' => route('home.catalogo'), 'items' => ['Video aulas', 'Materiais', 'Certificado']],
        ]);
    }

    $blogPosts = [
        ['tag' => 'Destaque', 'data' => '21 Marco 2025', 'titulo' => 'Formação profissional que aproxima alunos do mercado', 'texto' => 'Programas focados em competencias praticas ajudam os alunos a ganhar confianca e preparar melhor a sua entrada no mercado.'],
        ['tag' => 'Feira', 'data' => '13 Junho 2025', 'titulo' => 'Paruana em exposicao de cursos e tecnologia', 'texto' => 'Participamos em encontros de inovacao para apresentar solucoes educacionais e novas oportunidades de aprendizagem.'],
        ['tag' => 'Exposicao', 'data' => '03 Maio 2025', 'titulo' => 'Cursos em destaque para novos profissionais', 'texto' => 'Novas turmas combinam aulas praticas, suporte e certificacao para acelerar o crescimento profissional.'],
    ];
@endphp

<section id="inicio" class="hero-section" style="--hero-bg: url('{{ asset('assets/img/paruana/electricidade-alunos-paruana.png') }}')">
    <div class="hero-visual" aria-hidden="true">
        <div class="hero-grid-lines"></div>
        <div class="chart chart-one"></div>
        <div class="chart chart-two"></div>
        <div class="chart chart-three"></div>
        <div class="hero-card-mini">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Aprendizagem em crescimento</span>
        </div>
    </div>

    <div class="site-container hero-content">
        <p class="eyebrow">Centro de formação Paruana Comercial</p>
        <h1>Formação profissional para transformar a sua carreira.</h1>
        <p class="hero-copy">Cursos praticos, formadores experientes, acompanhamento e certificado para alunos e empresas em Angola.</p>
        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn-hero-primary">Inscrever-se</a>
            <a href="{{ route('login') }}" class="btn-hero-secondary">Login</a>
            <a href="{{ route('home.catalogo') }}" class="btn-hero-secondary">Ver cursos</a>
        </div>
    </div>
</section>

<section id="sobre" class="about-section">
    <div class="site-container about-grid">
        <div class="about-media">
            <div class="about-image-card photo">
                <img src="{{ asset('assets/img/paruana/decoracao-alunas-paruana.png') }}" alt="Alunas em formacao pratica de decoracao">
            </div>
        </div>
        <div class="about-copy">
            <p class="section-kicker">Paruana Comercial</p>
            <h2>Optima solucao para quem quer aprender e crescer.</h2>
            <p>A Paruana Comercial oferece cursos e formacoes pensadas para a realidade do mercado. Ajudamos alunos e profissionais a desenvolver competencias praticas, com acompanhamento de formadores e foco em resultados.</p>
            <div class="about-features">
                <span><i class="bi bi-check-lg"></i> Certificado</span>
                <span><i class="bi bi-check-lg"></i> Formadores experientes</span>
                <span><i class="bi bi-check-lg"></i> Cursos actualizados</span>
                <span><i class="bi bi-check-lg"></i> Suporte ao aluno</span>
            </div>
            <a href="{{ route('home.catalogo') }}" class="text-link">Ver cursos <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</section>

<section id="servicos" class="section-panel services-section">
    <div class="site-container">
        <div class="section-heading">
            <p class="section-kicker">O que oferecemos?</p>
            <h2>Nossos servicos</h2>
        </div>

        <div class="service-grid">
            @foreach($servicos->take(5) as $servico)
                <article class="service-card">
                    <i class="bi {{ $servico['icon'] }}"></i>
                    <h3>{{ $servico['titulo'] }}</h3>
                    <p>{{ $servico['descricao'] }}</p>
                    <ul>
                        @foreach($servico['items'] as $item)
                            <li><i class="bi bi-check2"></i>{{ $item }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ $servico['link'] }}">Saiba mais <i class="bi bi-chevron-right"></i></a>
                </article>
            @endforeach
        </div>

        <div class="section-more">
            <a href="{{ route('home.catalogo') }}">Descubra nossos cursos <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</section>

<section id="produtos" class="products-section">
    <div class="site-container">
        <div class="section-heading">
            <p class="section-kicker">Nossas solucoes</p>
            <h2>Cursos Paruana</h2>
            <p>Conheca os programas em destaque para impulsionar a sua carreira.</p>
        </div>

        <div class="product-grid">
            @forelse($cursosDestaque as $curso)
                <article class="product-card">
                    <div class="product-media">
                        <img src="{{ $curso->foto ? Storage::url($curso->foto) : asset('assets/img/logo.png') }}" alt="{{ $curso->titulo }}">
                    </div>
                    <div class="product-body">
                        <span>{{ $curso->categoria->nome ?? 'Curso' }}</span>
                        <h3>{{ $curso->titulo }}</h3>
                        <p>{{ Str::limit($curso->descricao ?? 'Curso completo com foco pratico e certificado.', 95) }}</p>
                        <a href="{{ route('home.detalhe', $curso->id) }}">Saiba mais <i class="bi bi-arrow-right"></i></a>
                    </div>
                </article>
            @empty
                <article class="product-card">
                    <div class="product-media"><img src="{{ asset('assets/img/logo.png') }}" alt="Paruana"></div>
                    <div class="product-body">
                        <span>Em breve</span>
                        <h3>Novos cursos</h3>
                        <p>Estamos a preparar novas formacoes para o catalogo.</p>
                        <a href="{{ route('home.catalogo') }}">Ver catalogo <i class="bi bi-arrow-right"></i></a>
                    </div>
                </article>
            @endforelse
        </div>
    </div>
</section>

<section id="formadores" class="team-section">
    <div class="site-container">
        <div class="section-heading">
            <h2>Nossa Equipa</h2>
        </div>

        <div class="team-grid">
            @forelse($formadores as $formador)
                <article class="team-card">
                    <div class="team-top"></div>
                    <div class="team-avatar">
                    <img src="{{ Storage::url($formador->foto_perfil) }} ">
                        {{ strtoupper(substr($formador->pessoa?->primeironome ?? 'F', 0, 1)) }}
                    </div>
                    <h3>{{ trim(($formador->pessoa?->primeironome ?? 'Formador').' '.($formador->pessoa?->segundonome ?? '')) }}</h3>
                    <p class="role">{{ $formador->especialidade ?? 'Formador' }}</p>
                    <p>{{ $formador->cursos_count }} curso(s) publicado(s) na plataforma.</p>
                </article>
            @empty
                <article class="team-card">
                    <div class="team-top"></div>
                    <div class="team-avatar">P</div>
                    <h3>Equipa Paruana</h3>
                    <p class="role">Formadores</p>
                    <p>Profissionais preparados para acompanhar a sua aprendizagem.</p>
                </article>
            @endforelse
        </div>
    </div>
</section>

<section id="blog" class="blog-section">
    <div class="site-container">
        <div class="section-heading">
            <h2>Blog</h2>
        </div>

        <div class="blog-feature">
            <div class="blog-image">
                <img src="{{ asset('assets/img/paruana/atendimento-tecnico-paruana.png') }}" alt="Atendimento e orientacao Paruana">
            </div>
            <div>
                <span>{{ $blogPosts[0]['tag'] }} - {{ $blogPosts[0]['data'] }}</span>
                <h3>{{ $blogPosts[0]['titulo'] }}</h3>
                <p>{{ $blogPosts[0]['texto'] }}</p>
                <a href="#">Ler mais <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        <div class="blog-list">
            @foreach(array_slice($blogPosts, 1) as $post)
                <article class="blog-row">
                    <img src="{{ $loop->first ? asset('assets/img/paruana/director-paruana.png') : asset('assets/img/paruana/electricidade-alunos-paruana.png') }}" alt="{{ $post['titulo'] }}">
                    <span>{{ $post['tag'] }} - {{ $post['data'] }}</span>
                    <h3>{{ $post['titulo'] }}</h3>
                    <p>{{ $post['texto'] }}</p>
                    <a href="#">Visitar pagina <i class="bi bi-arrow-right"></i></a>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section id="galeria" class="gallery-section">
    <div class="site-container">
        <div class="section-heading">
            <h2>Galeria</h2>
        </div>

        <div class="gallery-slider" data-gallery>
            <button type="button" class="gallery-btn gallery-prev" data-gallery-prev aria-label="Imagem anterior">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="gallery-stage">
                <img src="{{ $paruanaImages[0]['src'] }}" alt="Galeria Paruana">
                <div>
                    <h3>{{ $paruanaImages[0]['title'] }}</h3>
                    <p>{{ $paruanaImages[0]['copy'] }}</p>
                </div>
            </div>
            <button type="button" class="gallery-btn gallery-next" data-gallery-next aria-label="Proxima imagem">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="gallery-thumbs">
            @foreach($paruanaImages as $image)
                <button type="button" class="gallery-thumb {{ $loop->first ? 'active' : '' }}" data-gallery-thumb="{{ $loop->index }}" data-title="{{ $image['title'] }}" data-copy="{{ $image['copy'] }}">
                    <img src="{{ $image['src'] }}" alt="{{ $image['title'] }}">
                </button>
            @endforeach
        </div>
    </div>
</section>
@endsection
