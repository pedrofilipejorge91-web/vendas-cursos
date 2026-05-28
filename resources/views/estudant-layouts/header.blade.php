<header id="header" class="header student-header fixed-top d-flex align-items-center">
  <div class="student-header-brand d-flex align-items-center justify-content-between">
    <a href="{{ route('dashboard') }}" class="logo student-logo d-flex align-items-center">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Paruana Comercial">
      <span class="d-none d-lg-block">Paruana</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div>

  <div class="search-bar student-search">
    <form class="search-form d-flex align-items-center" method="GET" action="{{ route('home.catalogo') }}">
      <input type="text" name="q" placeholder="Pesquisar cursos" title="Pesquisar cursos">
      <button type="submit" title="Pesquisar"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle" href="#" aria-label="Pesquisar">
          <i class="bi bi-search"></i>
        </a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link nav-icon student-icon-btn" href="#" data-bs-toggle="dropdown" aria-label="Notificações">
          <i class="bi bi-bell"></i>
          <span class="badge bg-primary badge-number">{{ auth()->user()->notificacoes()->whereNull('lida_em')->count() }}</span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header">
            Notificações recentes
            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver</span></a>
          </li>
          <li><hr class="dropdown-divider"></li>

          @forelse(auth()->user()->notificacoes()->latest()->take(4)->get() as $notificacao)
            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>{{ $notificacao->titulo }}</h4>
                <p>{{ Str::limit($notificacao->mensagem, 60) }}</p>
                <p>{{ $notificacao->created_at->diffForHumans() }}</p>
              </div>
            </li>
            <li><hr class="dropdown-divider"></li>
          @empty
            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Tudo em dia</h4>
                <p>Não há novas notificações.</p>
              </div>
            </li>
          @endforelse

          <li><hr class="dropdown-divider"></li>
          <li class="dropdown-footer"><a href="#">Ver todas notificações</a></li>
        </ul>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link nav-icon student-icon-btn" href="#" data-bs-toggle="dropdown" aria-label="Carrinho">
          <i class="bi bi-cart3"></i>
          <span class="badge bg-success badge-number">{{ $cartCount ?? 0 }}</span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
          <li class="dropdown-header">
            Carrinho
            <a href="{{ route('home.carrinho') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">Abrir</span></a>
          </li>
          <li><hr class="dropdown-divider"></li>

          <li class="message-item">
            <a href="{{ route('home.carrinho') }}">
              <i class="bi bi-cart fs-4 px-3"></i>
              <div>
                <h4>{{ $cartCount ?? 0 }} item(ns)</h4>
                <p>Veja os cursos no carrinho e finalize a compra.</p>
              </div>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li class="dropdown-footer"><a href="{{ route('home.carrinho') }}">Abrir carrinho</a></li>
        </ul>
      </li>

      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <span class="student-avatar">{{ strtoupper(Str::substr(Auth::user()->name, 0, 1)) }}</span>
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ Auth::user()->name }}</h6>
            <span>Aluno</span>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}">
              <i class="bi bi-speedometer2"></i>
              <span>Meu painel</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('estudante.cursos') }}">
              <i class="bi bi-mortarboard"></i>
              <span>Meus cursos</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('home.catalogo') }}">
              <i class="bi bi-search"></i>
              <span>Catálogo</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                 onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sair</span>
              </a>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</header>
