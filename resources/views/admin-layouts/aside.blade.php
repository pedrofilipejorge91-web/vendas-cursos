<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('admin.relatorios') ? '' : 'collapsed' }}" href="{{ route('admin.relatorios') }}">
        <i class="bi bi-graph-up"></i>
        <span>Relatórios</span>
      </a>
    </li>

    <li class="nav-heading">Utilizadores</li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('formador.index') ? '' : 'collapsed' }}" href="{{ route('formador.index') }}">
        <i class="bi bi-person-badge"></i>
        <span>Formadores</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('estudante.index') ? '' : 'collapsed' }}" href="{{ route('estudante.index') }}">
        <i class="bi bi-people"></i>
        <span>Alunos</span>
      </a>
    </li>

<<<<<<< HEAD
    <!-- CUPONS -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('admin.cupons.index') }}">
        <i class="bi bi-ticket-perforated"></i>
        <span>Gestao de Cupons</span>
      </a>
    </li>

    <!-- CURSOS -->
=======
>>>>>>> 02ed285 (Atualizacao do projeto)
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('admin.administradores.*') ? '' : 'collapsed' }}" href="{{ route('admin.administradores.index') }}">
        <i class="bi bi-shield-lock"></i>
        <span>Administradores</span>
      </a>
    </li>

    <li class="nav-heading">Acadêmico</li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('curso.index') ? '' : 'collapsed' }}" href="{{ route('curso.index') }}">
        <i class="bi bi-mortarboard"></i>
        <span>Cursos</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('aula.index') ? '' : 'collapsed' }}" href="{{ route('aula.index') }}">
        <i class="bi bi-journal-text"></i>
        <span>Aulas</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('categoria.index') ? '' : 'collapsed' }}" href="{{ route('categoria.index') }}">
        <i class="bi bi-tags"></i>
        <span>Categorias</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('admin.certificados.*') ? '' : 'collapsed' }}" href="{{ route('admin.certificados.solicitacoes') }}">
        <i class="bi bi-award"></i>
        <span>Certificados</span>
      </a>
    </li>

    <li class="nav-heading">Plataforma</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('home') }}">
        <i class="bi bi-house"></i>
        <span>Ver site</span>
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
