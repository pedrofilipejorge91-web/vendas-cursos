<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Paruana Comercial">
      <span class="d-none d-lg-block">Paruana Admin</span>
    </a>
    <button class="toggle-sidebar-btn border-0 bg-transparent" type="button" aria-label="Abrir menu">
      <i class="bi bi-list"></i>
    </button>
  </div>

  <div class="search-bar">
    <form class="search-form d-flex align-items-center" method="GET" action="{{ route('curso.index') }}">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Pesquisar cursos" title="Pesquisar cursos">
      <button type="submit" title="Pesquisar"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <button class="nav-link nav-icon search-bar-toggle border-0 bg-transparent" type="button" aria-label="Pesquisar">
          <i class="bi bi-search"></i>
        </button>
      </li>

      <li class="nav-item dropdown">
        <button class="nav-link nav-icon border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-label="Notificações">
          <i class="bi bi-bell"></i>
          <span class="badge bg-primary badge-number">{{ auth()->user()->notificacoes()->whereNull('lida_em')->count() }}</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header">
            Notificações recentes
            <a href="{{ route('admin.notificacoes') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver todas</span></a>
          </li>
          <li><hr class="dropdown-divider"></li>

          @forelse(auth()->user()->notificacoes()->latest()->take(5)->get() as $notificacao)
            <li class="notification-item">
              <i class="bi {{ $notificacao->lida_em ? 'bi-check-circle text-success' : 'bi-info-circle text-primary' }}"></i>
              <div>
                <h4>{{ $notificacao->titulo }}</h4>
                <p>{{ Str::limit($notificacao->mensagem, 70) }}</p>
                <p>{{ $notificacao->created_at->diffForHumans() }}</p>
              </div>
            </li>
            <li><hr class="dropdown-divider"></li>
          @empty
            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Tudo em dia</h4>
                <p>Não há notificações novas.</p>
              </div>
            </li>
          @endforelse
          <li><hr class="dropdown-divider"></li>
          <li class="dropdown-footer"><a href="{{ route('admin.notificacoes') }}">Abrir centro de notificações</a></li>
        </ul>
      </li>

      <li class="nav-item dropdown pe-3">
        <button class="nav-link nav-profile d-flex align-items-center pe-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
          <span class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white fw-bold" style="width: 36px; height: 36px;">
            {{ strtoupper(Str::substr(Auth::user()->name, 0, 1)) }}
          </span>
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ Auth::user()->name }}</h6>
            <span>Administrador</span>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.dashboard') }}">
              <i class="bi bi-speedometer2"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.administradores.index') }}">
              <i class="bi bi-shield-lock"></i>
              <span>Administradores</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.relatorios') }}">
              <i class="bi bi-graph-up"></i>
              <span>Relatórios</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="dropdown-item d-flex align-items-center" type="submit">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sair</span>
              </button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</header>
