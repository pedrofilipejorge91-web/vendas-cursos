<aside id="sidebar" class="sidebar student-sidebar">
  <div class="student-sidebar-card">
    <span class="student-avatar large">{{ strtoupper(Str::substr(Auth::user()->name, 0, 1)) }}</span>
    <div>
      <strong>{{ Auth::user()->name }}</strong>
      <small>Área do aluno</small>
    </div>
  </div>

  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Aprendizagem</li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-speedometer2"></i>
        <span>Painel</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('estudante.cursos') || request()->routeIs('estudante.curso') || request()->routeIs('estudante.aula') ? '' : 'collapsed' }}" href="{{ route('estudante.cursos') }}">
        <i class="bi bi-mortarboard"></i>
        <span>Meus Cursos</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('estudante.perfil') ? '' : 'collapsed' }}" href="{{ route('estudante.perfil') }}">
        <i class="bi bi-person"></i>
        <span>Perfil</span>
      </a>
    </li>

    <li class="nav-heading">Explorar</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('home.catalogo') }}">
        <i class="bi bi-search"></i>
        <span>Catálogo</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('home.carrinho') }}">
        <i class="bi bi-cart3"></i>
        <span>Carrinho</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('home') }}">
        <i class="bi bi-house"></i>
        <span>Página Inicial</span>
      </a>
    </li>

    <li class="nav-heading">Conta</li>

    <li class="nav-item">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="nav-link collapsed border-0 bg-transparent w-100 text-start" type="submit">
          <i class="bi bi-box-arrow-right"></i>
          <span>Sair</span>
        </button>
      </form>
    </li>
  </ul>
</aside>
