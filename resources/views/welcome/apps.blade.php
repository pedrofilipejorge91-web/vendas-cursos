<!DOCTYPE html>
<html lang="pt-AO">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Paruana Comercial | Formação profissional</title>
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/welcome.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="site-shell">
  @include('welcome.header')

  <main>
    @yield('content')
  </main>

  @include('welcome.footer')

  <button class="chat-shortcut" type="button" aria-label="Abrir atendimento">
    <i class="bi bi-chat-fill"></i>
  </button>

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/welcome.js') }}"></script>
</body>
</html>
