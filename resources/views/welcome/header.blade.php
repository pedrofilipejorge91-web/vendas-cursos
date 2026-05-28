<header class="site-header" data-site-header>
    <div class="site-container nav-wrap">
        <a href="{{ route('home') }}" class="brand-link" aria-label="Paruana Comercial">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Paruana Comercial" class="brand-logo">
            <span>Paruana</span>
        </a>

        <button class="nav-toggle" type="button" data-nav-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>

        <nav class="main-nav" data-main-nav>
            <a href="{{ route('home') }}#inicio">Inicio</a>
            <a href="{{ route('home') }}#sobre">Sobre</a>
            <a href="{{ route('home') }}#servicos">Servicos</a>
            <a href="{{ route('home.catalogo') }}">Cursos</a>
            <a href="{{ route('home') }}#formadores">Formadores</a>
            <a href="{{ route('home') }}#blog">Blog</a>
            <a href="{{ route('home') }}#galeria">Galeria</a>
        </nav>

        <div class="nav-actions">
            @auth
                @php
                    $dashboardRoute = Auth::user()->tipo === 'admin'
                        ? 'admin.dashboard'
                        : (Auth::user()->tipo === 'formador' ? 'formador.dashboard' : 'dashboard');
                @endphp
                <a href="{{ route($dashboardRoute) }}" class="btn-nav btn-nav-outline">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-nav btn-nav-outline">
                    <i class="bi bi-person"></i>
                    Login
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-nav btn-nav-primary">Inscrever-se</a>
                @endif
            @endauth
        </div>
    </div>
</header>
