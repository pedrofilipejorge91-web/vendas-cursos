<footer class="bg-blue-950 text-slate-50 py-20">
    <div class="flex flex-col md:flex-row justify-between items-start px-8 max-w-7xl mx-auto gap-16">
        <div class="space-y-6 max-w-sm">
            <div class="text-2xl font-bold text-white tracking-widest uppercase">Paruana Comercial</div>
            <p class="text-slate-400 text-sm font-light">
                Capacitando a próxima geração de profissionais com educação prática e adaptada ao contexto angolano.
            </p>
            <div class="flex gap-4">
                <a class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all" href="#">
                    <span class="material-symbols-outlined text-xl">social_leaderboard</span>
                </a>
                <a class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all" href="#">
                    <span class="material-symbols-outlined text-xl">video_library</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-12 w-full">
            <div class="space-y-6">
                <h6 class="text-white text-xs font-bold uppercase tracking-widest">Institucional</h6>
                <ul class="space-y-4">
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('home.catalogo') }}">Cursos</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('home') }}#categorias">Categorias</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('home') }}#formadores">Formadores</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="#">Privacidade</a></li>
                </ul>
            </div>

            <div class="space-y-6">
                <h6 class="text-white text-xs font-bold uppercase tracking-widest">Suporte</h6>
                <ul class="space-y-4">
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('login') }}">Entrar</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('register') }}">Criar conta</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('home.catalogo') }}">Inscrições</a></li>
                </ul>
            </div>

            <div class="col-span-2 space-y-6">
                <h6 class="text-white text-xs font-bold uppercase tracking-widest">Localização</h6>
                <div class="h-40 bg-white/5 rounded-lg overflow-hidden grayscale opacity-60 hover:opacity-100 transition-opacity">
                    <img alt="Luanda, Angola" class="w-full h-full object-cover" src="{{ asset('assets/img/paruana.png') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-8 mt-20 pt-8 border-t border-white/5 text-center md:text-left">
        <p class="text-slate-500 text-xs uppercase tracking-widest">© {{ date('Y') }} Centro de Formação Paruana Comercial. Excelência no ensino comercial.</p>
    </div>
</footer>
