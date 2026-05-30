<aside id="sidebar" class="sidebar formador-sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.dashboard') ? '' : 'collapsed' }}" href="{{ route('formador.dashboard') }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-heading">Gestão</li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.certificados.*') ? '' : 'collapsed' }}" href="{{ route('formador.certificados.solicitacoes') }}">
        <i class="bi bi-award"></i>
        <span>Solicitações de Certificados</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.cursos*') ? '' : 'collapsed' }}" href="{{ route('formador.cursos') }}">
        <i class="bi bi-mortarboard"></i>
        <span>Meus Cursos</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.aulas*') ? '' : 'collapsed' }}" href="{{ route('formador.aulas') }}">
        <i class="bi bi-play-btn"></i>
        <span>Minhas Aulas</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.relatorios') ? '' : 'collapsed' }}" href="{{ route('formador.relatorios') }}">
        <i class="bi bi-graph-up"></i>
        <span>Meus Relatórios</span>
      </a>
    </li>

    
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.notificacoes') ? '' : 'collapsed' }}" href="{{ route('formador.notificacoes') }}">
        <i class="bi bi-envelope"></i>
        <span>Notificação</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.comentarios') ? '' : 'collapsed' }}" href="{{ route('formador.comentarios') }}">
        <i class="bi bi-chat-dots"></i>
        <span>Comentários</span>
      </a>
    </li>

    <li class="nav-heading">Conta</li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.perfil') ? '' : 'collapsed' }}" href="{{ route('formador.perfil') }}">
        <i class="bi bi-person"></i>
        <span>Meu Perfil</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.suporte') ? '' : 'collapsed' }}" href="{{ route('formador.suporte') }}">
        <i class="bi bi-envelope"></i>
        <span>Suporte</span>
      </a>
    </li>

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
