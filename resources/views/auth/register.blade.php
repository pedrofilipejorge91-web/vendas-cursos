<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Inscrição de Estudante - PARUANA</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4fb;
      overflow-x: hidden;
    }

    /* Fundo Animado */
    .background-widgets {
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: -1;
      overflow: hidden;
      background: linear-gradient(135deg, #f0f4fb 0%, #dbeafe 100%);
    }

    .background-widgets span {
      position: absolute;
      display: block;
      width: 40px;
      height: 40px;
      background: rgba(13, 110, 253, 0.1);
      border-radius: 50%;
      animation: float 20s linear infinite;
      bottom: -150px;
    }

    @keyframes float {
      0% { transform: translateY(0) rotate(0deg); opacity: 0; }
      50% { opacity: 0.8; }
      100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
    }

    /* Card Customizado */
    .card-registration {
      border: none;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.05);
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.7);
    }

    .form-label {
      font-weight: 500;
      color: #444;
      font-size: 0.85rem;
      margin-bottom: 5px;
    }

    /* Estilização dos Inputs */
    .input-group-text {
      background-color: #f8f9fa;
      border-right: none;
      color: #0d6efd;
    }

    .form-control, .form-select {
      border-left: none;
      padding: 0.6rem 0.75rem;
      border-color: #dee2e6;
    }

    .form-control:focus, .form-select:focus {
      box-shadow: none;
      border-color: #dee2e6;
    }

    .input-group:focus-within {
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
      border-radius: 0.375rem;
    }

    .section-title {
      position: relative;
      padding-left: 15px;
      font-weight: 600;
      color: #0d6efd;
      margin-bottom: 20px;
    }

    .section-title::before {
      content: '';
      position: absolute;
      left: 0;
      top: 5px;
      height: 18px;
      width: 4px;
      background: #0d6efd;
      border-radius: 10px;
    }

    .btn-submit {
      padding: 12px;
      border-radius: 10px;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }

    .logo-container img {
      max-height: 50px;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>

  <div class="background-widgets">
    <span style="left: 10%; width: 80px; height: 80px; animation-duration: 25s;"></span>
    <span style="left: 25%; width: 40px; height: 40px; animation-duration: 15s;"></span>
    <span style="left: 50%; width: 60px; height: 60px; animation-duration: 20s;"></span>
    <span style="left: 75%; width: 90px; height: 90px; animation-duration: 22s;"></span>
    <span style="left: 90%; width: 50px; height: 50px; animation-duration: 18s;"></span>
  </div>


      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
<!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">PARUANA comercial</span>
                </a>
              </div>
                


<section class="vh-100 d-flex align-items-center justify-content-center bg-light">

    <div class="card shadow-lg border-0 rounded-4" style="width: 900px;">

        <div class="card-body p-4">

            <!-- HEADER -->
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Inscrição de Estudante</h3>
                <small class="text-muted">Crie sua conta e comece a aprender</small>
            </div>

        <form method="POST" action="{{ route('auth.register.estudante') }}">
    @csrf

    <!-- LOGIN -->
    <h6 class="text-primary mb-3">Acesso</h6>

    <div class="row g-3">

        <div class="col-md-4">
            <input type="text" class="form-control" name="name" placeholder="Nome de login" required>
        </div>

        <div class="col-md-4">
            <input type="email" class="form-control" name="email" placeholder="E-mail" required>
        </div>

        <div class="col-md-4">
            <input type="password" class="form-control" name="password" placeholder="Senha" required>
        </div>

    </div>

    <hr class="my-4">

    <!-- PESSOAL -->
    <h6 class="text-primary mb-3">Dados Pessoais</h6>

    <div class="row g-3">

        <div class="col-md-6">
            <input type="text" class="form-control" name="primeironome" placeholder="Primeiro nome" required>
        </div>

        <div class="col-md-6">
            <input type="text" class="form-control" name="segundonome" placeholder="Segundo nome" required>
        </div>

        <div class="col-md-4">
            <input type="text" class="form-control" name="BI" placeholder="BI" required>
        </div>

        <div class="col-md-4">
            <select class="form-select" name="genero" required>
                <option value="">Gênero</option>
                <option value="M">Masculino</option>
                <option value="F">Feminino</option>
            </select>
        </div>

        <div class="col-md-4">
            <input type="text" class="form-control" name="nacionalidade" placeholder="Nacionalidade" required>
        </div>

        <div class="col-md-6">
            <input type="date" class="form-control" name="data_nascimento" required>
        </div>

        <div class="col-md-3">
            <input type="text" class="form-control" name="rua" placeholder="Rua" required>
        </div>

        <div class="col-md-3">
            <input type="text" class="form-control" name="bairro" placeholder="Bairro" required>
        </div>

        <div class="col-12">
            <input type="text" class="form-control" name="contacto" placeholder="Contacto" required>
        </div>

    </div>

    <hr class="my-4">

    <!-- ACADÉMICO -->
    <h6 class="text-primary mb-3">Académico</h6>

    <div class="row">
        <div class="col-12">
            <input type="text" class="form-control" name="escola_actual" placeholder="Escola actual">
        </div>
    </div>

    <div class="form-check mt-4">
        <input class="form-check-input" type="checkbox" name="consentimento_dados" value="1" id="consentimento_dados" required>
        <label class="form-check-label small text-muted" for="consentimento_dados">
            Confirmo que autorizo a recolha e uso dos meus dados pessoais para criacao da conta, matricula, pagamentos, comunicacoes e emissao de certificados.
        </label>
    </div>

    <!-- BOTÃO -->
    <div class="mt-4">
        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
            Inscrever Estudante
        </button>
    </div>

</form>
        </div>
    </div>

</section>

                </div>
              </div>

            
            </div>

            <main class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-xl-8">
        
        <!-- Logo e Título -->
        <div class="text-center mb-4">
          <div class="logo-container">
            <img src="assets/img/logo.png" alt="Logo">
            <h4 class="fw-bold text-dark mt-2">PARUANA <span class="text-primary">COMERCIAL</span></h4>
          </div>
        </div>

        <div class="card card-registration">
          <div class="card-body p-4 p-md-5">
            
            <div class="text-center mb-5">
              <h2 class="fw-bold">Inscrição de Estudante</h2>
              <p class="text-muted">Preencha os campos abaixo para realizar o cadastro</p>
            </div>

            <form method="POST" action="{{ route('auth.register.estudante') }}">
              @csrf

              <!-- SEÇÃO: ACESSO -->
              <h5 class="section-title">Dados de Acesso</h5>
              <div class="row g-3 mb-4">
                <div class="col-md-4">
                  <label class="form-label">Usuário</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" name="name" placeholder="Nome de login" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">E-mail</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" name="email" placeholder="email@exemplo.com" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Senha</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="********" required>
                  </div>
                </div>
              </div>

              <!-- SEÇÃO: PESSOAL -->
              <h5 class="section-title">Informações Pessoais</h5>
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label">Primeiro Nome</label>
                  <input type="text" class="form-control" name="primeironome" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Segundo Nome</label>
                  <input type="text" class="form-control" name="segundonome" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label">BI / Documento</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                    <input type="text" class="form-control" name="BI" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Gênero</label>
                  <select class="form-select" name="genero" required>
                    <option value="" selected disabled>Selecione...</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Nacionalidade</label>
                  <input type="text" class="form-control" name="nacionalidade" value="Angolana" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Data de Nascimento</label>
                  <input type="date" class="form-control" name="data_nascimento" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Contacto Telefônico</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input type="text" class="form-control" name="contacto" placeholder="+244" required>
                  </div>
                </div>
              </div>

              <!-- SEÇÃO: ENDEREÇO -->
              <h5 class="section-title">Endereço de Residência</h5>
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label">Rua</label>
                  <input type="text" class="form-control" name="rua" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Bairro</label>
                  <input type="text" class="form-control" name="bairro" required>
                </div>
              </div>

              <!-- SEÇÃO: ACADÉMICO -->
              <h5 class="section-title">Dados Acadêmicos</h5>
              <div class="row g-3 mb-5">
                <div class="col-12">
                  <label class="form-label">Escola de Origem / Atual</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                    <input type="text" class="form-control" name="escola_actual" placeholder="Nome da instituição">
                  </div>
                </div>
              </div>

              <!-- BOTÃO DE AÇÃO -->
              <div class="row">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-submit w-100 shadow-sm">
                    <i class="bi bi-check-circle me-2"></i> Finalizar Inscrição
                  </button>
                </div>
                <div class="col-12 text-center mt-3">
                  <p class="small text-muted">Já possui uma conta? <a href="login.html" class="text-decoration-none fw-bold">Faça login</a></p>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>