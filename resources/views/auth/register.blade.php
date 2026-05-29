<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Inscrição de Estudante - PARUANA</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f3f4f6;
      overflow-x: hidden;
    }

    .background-widgets {
      position: fixed;
      inset: 0;
      z-index: -1;
      overflow: hidden;
      background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    }

    .background-widgets span {
      position: absolute;
      display: block;
      background: rgba(31, 41, 55, 0.08);
      border-radius: 50%;
      animation: float 20s linear infinite;
      bottom: -150px;
    }

    @keyframes float {
      0% { transform: translateY(0) rotate(0deg); opacity: 0; }
      50% { opacity: 0.8; }
      100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
    }

    .card-registration {
      border: 1px solid rgba(255, 255, 255, 0.7);
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
      background: rgba(248, 249, 251, 0.94);
      backdrop-filter: blur(10px);
    }

    .form-label {
      font-weight: 500;
      color: #444;
      font-size: 0.85rem;
      margin-bottom: 5px;
    }

    .input-group-text {
      background-color: #f8f9fa;
      border-right: none;
      color: #0d6efd;
    }

    .form-control,
    .form-select {
      padding: 0.6rem 0.75rem;
      border-color: #dee2e6;
    }

    .input-group .form-control {
      border-left: none;
    }

    .form-control:focus,
    .form-select:focus {
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

  <main class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-xl-8">
        <div class="text-center mb-4">
          <div class="logo-container">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
            <h4 class="fw-bold text-dark mt-2">PARUANA <span class="text-primary">COMERCIAL</span></h4>
          </div>
        </div>

        <div class="card card-registration">
          <div class="card-body p-4 p-md-5">
            <div class="text-center mb-5">
              <h2 class="fw-bold">Inscrição de Estudante</h2>
              <p class="text-muted">Preencha os campos abaixo para realizar o cadastro</p>
            </div>

            @if (session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
              <div class="alert alert-danger">
                <strong>Corrija os dados informados.</strong>
                <ul class="mb-0 mt-2">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form method="POST" action="{{ route('auth.register.estudante') }}">
              @csrf

              <h5 class="section-title">Dados de Acesso</h5>
              <div class="row g-3 mb-4">
                <div class="col-md-4">
                  <label class="form-label" for="name">Usuário</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Nome de login" required>
                  </div>
                </div>

                <div class="col-md-4">
                  <label class="form-label" for="email">E-mail</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="email@exemplo.com" required>
                  </div>
                </div>

                <div class="col-md-4">
                  <label class="form-label" for="password">Senha</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">
                  </div>
                </div>
              </div>

              <h5 class="section-title">Informações Pessoais</h5>
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label" for="primeironome">Primeiro Nome</label>
                  <input id="primeironome" type="text" class="form-control @error('primeironome') is-invalid @enderror" name="primeironome" value="{{ old('primeironome') }}" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="segundonome">Segundo Nome</label>
                  <input id="segundonome" type="text" class="form-control @error('segundonome') is-invalid @enderror" name="segundonome" value="{{ old('segundonome') }}" required>
                </div>

                <div class="col-md-4">
                  <label class="form-label" for="BI">BI / Documento</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                    <input id="BI" type="text" class="form-control @error('BI') is-invalid @enderror" name="BI" value="{{ old('BI') }}" required>
                  </div>
                </div>

                <div class="col-md-4">
                  <label class="form-label" for="genero">Gênero</label>
                  <select id="genero" class="form-select @error('genero') is-invalid @enderror" name="genero" required>
                    <option value="" disabled @selected(! old('genero'))>Selecione...</option>
                    <option value="M" @selected(old('genero') === 'M')>Masculino</option>
                    <option value="F" @selected(old('genero') === 'F')>Feminino</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label class="form-label" for="nacionalidade">Nacionalidade</label>
                  <input id="nacionalidade" type="text" class="form-control @error('nacionalidade') is-invalid @enderror" name="nacionalidade" value="{{ old('nacionalidade', 'Angolana') }}" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="data_nascimento">Data de Nascimento</label>
                  <input id="data_nascimento" type="date" class="form-control @error('data_nascimento') is-invalid @enderror" name="data_nascimento" value="{{ old('data_nascimento') }}" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="contacto">Contacto Telefônico</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input id="contacto" type="text" class="form-control @error('contacto') is-invalid @enderror" name="contacto" value="{{ old('contacto') }}" placeholder="+244" required>
                  </div>
                </div>
              </div>

              <h5 class="section-title">Endereço de Residência</h5>
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label" for="rua">Rua</label>
                  <input id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua" value="{{ old('rua') }}" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label" for="bairro">Bairro</label>
                  <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" value="{{ old('bairro') }}" required>
                </div>
              </div>

              <h5 class="section-title">Dados Acadêmicos</h5>
              <div class="row g-3 mb-4">
                <div class="col-12">
                  <label class="form-label" for="escola_actual">Escola de Origem / Atual</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                    <input id="escola_actual" type="text" class="form-control @error('escola_actual') is-invalid @enderror" name="escola_actual" value="{{ old('escola_actual') }}" placeholder="Nome da instituição">
                  </div>
                </div>
              </div>

              <div class="form-check mb-4">
                <input class="form-check-input @error('consentimento_dados') is-invalid @enderror" type="checkbox" name="consentimento_dados" value="1" id="consentimento_dados" required @checked(old('consentimento_dados'))>
                <label class="form-check-label small text-muted" for="consentimento_dados">
                  Confirmo que autorizo a recolha e uso dos meus dados pessoais para criação da conta, matrícula, pagamentos, comunicações e emissão de certificados.
                </label>
              </div>

              <div class="row">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-submit w-100 shadow-sm">
                    <i class="bi bi-check-circle me-2"></i> Finalizar Inscrição
                  </button>
                </div>
                <div class="col-12 text-center mt-3">
                  <p class="small text-muted">Já possui uma conta? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Faça login</a></p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/forms.js') }}"></script>
</body>

</html>
