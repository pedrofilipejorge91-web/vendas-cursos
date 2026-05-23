<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- DASHBOARD -->
    <li class="nav-item">
      <a class="nav-link " 
         href="{{ route('formador.dashboard') }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- CURSOS -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('formador.certificados.solicitacoes') }}">
          <i class="bi bi-award"></i>
          <span>Solicitações de Certificados</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('formador.cursos')}}">
          <i class="bi bi-mortarboard"></i>
          <span>Meus Cursos</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('formador.aulas') }}">
          <i class="bi bi-play-btn"></i>
          <span>Minhas Aulas</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('formador.relatorios')}}">
          <i class="bi bi-graph-up"></i>
          <span>Meus Relatorios</span>
        </a>
      </li>

    <!-- COMENTÁRIOS -->
    <li class="nav-item">
      <a class="nav-link " 
         href="#">
        <i class="bi bi-chat-dots"></i>
        <span>Comentários</span>
      </a>
    </li>

    <!-- SEPARADOR -->
    <li class="nav-heading">Conta</li>

    <!-- PERFIL -->
    <li class="nav-item">
      <a class="nav-link " 
         href="#">
        <i class="bi bi-person"></i>
        <span>Meu Perfil</span>
      </a>
    </li>

    <!-- CONTACTO -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="#">
        <i class="bi bi-envelope"></i>
        <span>Suporte</span>
      </a>
    </li>

    <!-- LOGOUT -->
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

</aside>
