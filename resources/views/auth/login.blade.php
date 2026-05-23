<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Entrar</title>

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(90deg, rgba(255,255,255,0.05), rgba(0,0,0,0.02)), url('/assets/img/logo.png') center/cover no-repeat;
            position: relative;
        }
        .blur-bg {
            position: absolute;
            inset: 0;
            backdrop-filter: blur(8px) saturate(120%);
            background: rgba(255,255,255,0.03);
            z-index: 0;
        }
        .login-card {
            position: absolute;
            z-index: 1;
            width: 420px;
            max-width: calc(100% - 32px);
            padding: 32px;
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,250,250,0.96));
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        .logo-img { max-width: 140px; }
        .btn-black { background: #000; color: #fff; border: none; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6c757d; }
        .form-control.with-icon { padding-left: 44px; }
        .eye-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; }
    </style>
</head>
<body>
<div class="blur-bg"></div>
    <div class="login-card">
        <div class="text-center mb-3">
            <img src="/assets/img/logo.png" alt="logo" class="logo-img mb-2">
            <h3 class="mb-0">Bem-vindo</h3>
            <p class="text-muted small">Acesse sua conta para continuar</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3 position-relative">
                <label class="form-label small text-muted">E-MAIL</label>
                <span class="input-icon"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control with-icon @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="exemplo@email.com" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label small text-muted">SENHA</label>
                <span class="input-icon"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control with-icon @error('password') is-invalid @enderror" placeholder="••••••••" required>
                <button type="button" class="eye-toggle" onclick="togglePassword()" aria-label="Mostrar senha"><i id="eyeIcon" class="bi bi-eye"></i></button>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                        <label class="form-check-label small" for="rememberMe">Lembrar</label>
                    </div>
                </div>
                <div><a href="{{ route('password.request') }}" class="small">Esqueceu?</a></div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-black btn-lg">Entrar</button>
            </div>

            <p class="text-center small mb-0">Ainda não tem uma conta? <a href="{{ route('register') }}">Cadastre-se</a></p>
        </form>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/bootstrap-icons/bootstrap-icons.js"></script>
    <script>
        function togglePassword(){
            const pw = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if(pw.type === 'password'){
                pw.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                pw.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>
