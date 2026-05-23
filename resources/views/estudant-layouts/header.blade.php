<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
   <img src="{{ asset('assets/img/logo.png') }}"
     alt="Paruana Comercial"
     class="w-10 h-10 object-contain">        <span class="d-none d-lg-block">PARUANA comercial</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="GET" action="{{ route('home.catalogo') }}">
        <input type="text" name="q" placeholder="Pesquisar cursos" title="Pesquisar cursos">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">{{ auth()->user()->notificacoes()->whereNull('lida_em')->count() }}</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              Notificações recentes
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

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

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Ver todas notificações</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">{{ $cartCount ?? 0 }}</span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              Carrinho
              <a href="{{ route('home.carrinho') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">Abrir</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="{{ route('home.carrinho') }}">
                <i class="bi bi-cart fs-4 px-3"></i>
                <div>
                  <h4>{{ $cartCount ?? 0 }} item(ns)</h4>
                  <p>Veja os cursos no carrinho e finalize a compra.</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="{{ route('home.carrinho') }}">Abrir carrinho</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{asset('assets/img/pedro.jpg ')}}" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"> <div><div>{{ Auth::user()->name }}</div></div></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6> <div>{{ Auth::user()->name }}</div></h6>
              <span>Aluno</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="bi bi-person"></i>
                <span>Meu painel</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('estudante.cursos') }}">
                <i class="bi bi-mortarboard"></i>
                <span>Meus cursos</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('home.catalogo') }}">
                <i class="bi bi-question-circle"></i>
                <span>Catálogo</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
               <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            this.closest('form').submit();">
                 <i class="bi bi-box-arrow-right"></i>
                <span>Sair</span>
              </a>
                </form>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
