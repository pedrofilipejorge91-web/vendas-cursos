<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- DASHBOARD -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <!-- AUTENTICAÇÃO -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('admin.relatorios') }}">
        <i class="bi bi-card-list"></i>
        <span>Relatorio</span>
      </a>
    </li>

    <!-- PESSOAS -->
    <li class="nav-item">
      <p class="nav-link collapsed" data-bs-target="#pessoas-nav" data-bs-toggle="collapse">
        <i class="bi bi-person"></i>
        <span>Gestão de Usuarios</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </p>

      <ul id="pessoas-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

        <!-- FORMADORES -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('formador.index') }}">
            <i class="bi bi-person-badge"></i>
            <span>Formadores</span>
          </a>
        </li>

        <!-- ALUNOS -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('estudante.index') }}">
            <i class="bi bi-person"></i>
            <span>Alunos</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('admin.administradores.index') }}">
            <i class="bi bi-shield-lock"></i>
            <span>Administradores</span>
          </a>
        </li>

      </ul>
    </li>

    <!-- PAGINAS -->
    <li class="nav-heading">Páginas</li>

    <!-- CERTIFICADOS -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('admin.certificados.solicitacoes') }}">
        <i class="bi bi-award"></i>
        <span>Solicitações de Certificados</span>
      </a>
    </li>


    <!-- CATEGORIAS -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('categoria.index') }}">
        <i class="bi bi-tags"></i>
        <span> Gestão de Categorias</span>
      </a>
    </li>

    <!-- CURSOS -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('curso.index') }}">
        <i class="bi bi-mortarboard"></i>
        <span>Gestão de Cursos</span>
      </a>
    </li>

    <!-- AULAS -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('aula.index') }}">
        <i class="bi bi-journal-text"></i>
        <span>Gestão de Aulas</span>
      </a>
    </li>

    <!-- FAQ -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="#">
        <i class="bi bi-question-circle"></i>
        <span>Perguntas Frequentes</span>
      </a>
    </li>

    <!-- CONTACTOS -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="#">
        <i class="bi bi-envelope"></i>
        <span>Contactos</span>
      </a>
    </li>

    

    <!-- REDES -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="#">
        <i class="bi bi-facebook"></i>
        <span>Redes Sociais</span>
      </a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('logout') }}">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>Sair</span>
      </a>
    </li>

  </ul>

</aside>
