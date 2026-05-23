<!-- TopAppBar -->
<header class="fixed top-0 left-0 right-0 z-50 bg-slate-50/70 backdrop-blur-xl shadow-sm">
    <div class="flex justify-between items-center w-full px-8 py-5 max-w-7xl mx-auto">

       <div class="flex items-center gap-3">

    <!-- Logo imagem -->
   <img src="{{ asset('assets/img/logo.png') }}"
     alt="Paruana Comercial"
     class="w-10 h-10 object-contain">

    <!-- Nome -->
    <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-blue-950 uppercase">
        Paruana Comercial
    </a>

</div>

        <!-- Menu -->
        <nav class="hidden md:flex items-center gap-8">

            <a href="{{ route('home.catalogo') }}" class="text-blue-950 font-semibold border-b-2 border-blue-950 pb-1 hover:text-blue-800 transition">
                Cursos
            </a>
            <!-- Dropdown -->
            <div class="relative group">

                <!-- Botão -->
                <button class="flex items-center gap-1 text-blue-950 font-semibold hover:text-blue-700 transition">
                    Categorias
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                    </svg>
                </button>

                <!-- Menu Dropdown -->
                <div class="absolute left-0 mt-3 w-56 bg-white rounded-xl shadow-lg border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">

                    @foreach($categoriasall as $categoria)
                        <a href="{{ route('home.categorias', $categoria->id) }}"
                           class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">
                            {{ $categoria->nome }}
                        </a>
                    @endforeach

                </div>
            </div>

            <a href="{{ route('home') }}#formadores" class="text-slate-500 font-medium hover:text-blue-800 transition">
                Formadores
            </a>

            <a href="{{ route('home') }}#categorias" class="text-slate-500 font-medium hover:text-blue-800 transition">
                Áreas
            </a>

        </nav>

        <!-- Auth -->
        <div class="flex items-center gap-4">

            @auth
                @php
                    $dashboardRoute = Auth::user()->tipo === 'admin'
                        ? 'admin.dashboard'
                        : (Auth::user()->tipo === 'formador' ? 'formador.dashboard' : 'dashboard');
                @endphp
                <a class="hidden md:block text-slate-500 font-medium hover:text-blue-800 transition"
                   href="{{ route($dashboardRoute) }}">
                    Dashboard
                </a>
            @else
                <a class="hidden md:block text-slate-500 font-medium hover:text-blue-800 transition"
                   href="{{ route('login') }}">
                    Entrar
                </a>

                @if (Route::has('register'))
                    <a class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow-md"
                       href="{{ route('register') }}">
                        Inscrever-se
                    </a>
                @endif
            @endauth
      


        </div>

    </div>
</header>
