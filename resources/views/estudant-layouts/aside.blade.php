
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Painel Principal</span>
        </a>
      </li><!-- End Dashboard Nav -->
        
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('home') }}">
          <i class="bi bi-house"></i>
          <span>Página Inicial</span>
        </a>
      </li><!-- End Dashboard Nav -->
        
      <li class="nav-heading">Paginas</li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('estudante.cursos') || request()->routeIs('estudante.curso') ? '' : 'collapsed' }}" href="{{ route('estudante.cursos') }}">
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
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('home.catalogo') }}">
          <i class="bi bi-search"></i>
          <span>Catálogo</span>
        </a> 
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('home.carrinho') }}">
          <i class="bi bi-cart"></i>
          <span>Carrinho</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('pagamento') }}">
          <i class="bi bi-credit-card"></i>
          <span>Pagamentos</span>
        </a>
      </li>

        <li class="nav-item">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="nav-link collapsed border-0 bg-transparent w-100 text-start">
          <i class="bi bi-box-arrow-right"></i>
          <span>Sair</span>
        </button>
      </form>
    </li>

    </ul>

  </aside><!-- End Sidebar-->
