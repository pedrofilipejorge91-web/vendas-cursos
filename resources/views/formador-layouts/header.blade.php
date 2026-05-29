<header id="header" class="header fixed-top d-flex align-items-center formador-header">
  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('formador.dashboard') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Paruana Comercial">
      <span class="d-none d-lg-block">PARUANA Comercial</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div>

  <div class="search-bar">
    <form class="search-form d-flex align-items-center" method="GET" action="{{ route('formador.pesquisa') }}">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Pesquisar cursos, aulas..." title="Pesquisar">
      <button type="submit" title="Pesquisar"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <button class="nav-link nav-icon search-bar-toggle border-0 bg-transparent" type="button" aria-label="Abrir pesquisa">
          <i class="bi bi-search"></i>
        </button>
      </li>

      <li class="nav-item dropdown">
        <button class="nav-link nav-icon border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-label="Abrir notificações">
          <i class="bi bi-bell"></i>
          <span class="badge bg-primary badge-number">{{ auth()->user()->notificacoes()->whereNull('lida_em')->count() }}</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header">
            {{ auth()->user()->notificacoes()->whereNull('lida_em')->count() }} notificações por ler
            <a href="{{ route('formador.notificacoes') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver todas</span></a>
          </li>
          <li><hr class="dropdown-divider"></li>
          @forelse(auth()->user()->notificacoes()->latest()->take(4)->get() as $notificacao)
            <li class="notification-item">
              <i class="bi {{ $notificacao->lida_em ? 'bi-check-circle text-success' : 'bi-info-circle text-primary' }}"></i>
              <div>
                <h4>{{ $notificacao->titulo }}</h4>
                <p>{{ \Illuminate\Support\Str::limit($notificacao->mensagem, 80) }}</p>
                <p>{{ $notificacao->created_at?->diffForHumans() }}</p>
              </div>
            </li>
            <li><hr class="dropdown-divider"></li>
          @empty
            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sem notificações</h4>
                <p>Quando houver novidades, elas aparecem aqui.</p>
              </div>
            </li>
          @endforelse
        </ul>
      </li>

      <li class="nav-item dropdown pe-3">
        <button class="nav-link nav-profile d-flex align-items-center pe-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-label="Abrir menu do perfil">
          <span class="profile-avatar rounded-circle">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ Auth::user()->name }}</h6>
            <span>Formador</span>
          </li>
          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('formador.perfil') }}">
              <i class="bi bi-person"></i>
              <span>Meu Perfil</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('formador.perfil') }}#definicoes">
              <i class="bi bi-gear"></i>
              <span>Definições da Conta</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('formador.suporte') }}">
              <i class="bi bi-question-circle"></i>
              <span>Ajuda</span>
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
