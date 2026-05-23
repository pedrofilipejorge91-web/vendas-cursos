<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Pages / Login - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
body {
    overflow-x: hidden;
    background: #f4f7ff;
}

/* container das animações */
.background-widgets {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 0;
    overflow: hidden;
}

/* bolhas */
.background-widgets span {
    position: absolute;
    display: block;
    width: 40px;
    height: 40px;
    background: rgba(13,110,253,0.15);
    border-radius: 50%;
    animation: float 20s linear infinite;
    bottom: -150px;
    backdrop-filter: blur(4px);
}

/* posições diferentes */
.background-widgets span:nth-child(1) {
    left: 10%;
    width: 60px;
    height: 60px;
    animation-duration: 18s;
}

.background-widgets span:nth-child(2) {
    left: 20%;
    animation-duration: 12s;
}

.background-widgets span:nth-child(3) {
    left: 35%;
    width: 80px;
    height: 80px;
    animation-duration: 22s;
}

.background-widgets span:nth-child(4) {
    left: 70%;
    width: 50px;
    height: 50px;
    animation-duration: 16s;
}

.background-widgets span:nth-child(5) {
    left: 85%;
    width: 70px;
    height: 70px;
    animation-duration: 25s;
}

/* animação */
@keyframes float {
    0% {
        transform: translateY(0) scale(1);
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateY(-1000px) scale(1.2);
        opacity: 0;
    }
}

/* garantir que o card fique acima */
.card {
    position: relative;
    z-index: 2;
}
</style>
</head>

<body>
    <div class="background-widgets">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>

  <main>

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
          </div>
        </div>

      </section>

  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/forms.js"></script>

</body>

</html>
