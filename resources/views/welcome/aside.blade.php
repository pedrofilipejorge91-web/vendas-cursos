
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="{{route('dashboard')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
        

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-person"></i><span>Pessoas</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          
<li class="nav-item">
        <a class="nav-link collapsed" href="{{route('formador.index')}}">
          <i class="bi bi-person"></i>
          <span>Formadores</span>
        </a> 
      </li>
            <!-- End Profile Page Nav --><li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Alunos/Clientes</span>
        </a>
      </li><!-- End Profile Page Nav -->
          
        </ul>
      </li><!-- End Icons Nav -->

      <li class="nav-heading">Paginas</li>


      <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('categoria.indedx')}}">
          <i class="bi bi-mortarboard"></i>
          <span>Categórias</span>
        </a> 
      </li><li class="nav-item">
        <a class="nav-link collapsed" href="{{route('curso.index')}}">
          <i class="bi bi-mortarboard"></i> 
          <span>Cursos</span>
        </a> 
      </li>
      
      <!-- End Profile Page Nav --><li class="nav-item">
        <a class="nav-link collapsed" href="{{route('aula.index')}}">
        <i class="bi bi-journal-text"></i>  
          <span>Aulas</span>
        </a>
      </li><!-- End Profile Page Nav -->
    

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-faq.html">
          <i class="bi bi-question-circle"></i>
          <span>Perguntas<br>Frequentes</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-contact.html">
          <i class="bi bi-envelope"></i>
          <span>Contactos</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('register')}}">
          <i class="bi bi-card-list"></i>
          <span>Register</span>
        </a>
      </li><!-- End Register Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-login.html">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Login</span>
        </a>
      </li><!-- End Login Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-error-404.html">
          <i class="bi bi-facebook"></i>
          <span>Redes-Socias</span>
        </a>
      </li><!-- End Error 404 Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->