<footer class="site-footer">
    <div class="site-container footer-grid">
        <div class="footer-brand">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Paruana Comercial">
            <p>Formação pratica para profissionais e empresas que querem crescer com metodo, suporte e certificado.</p>
            <ul class="footer-contact">
                <li><i class="bi bi-envelope-fill"></i> geral@paruana.co.ao</li>
                <li><i class="bi bi-geo-alt-fill"></i> Vila verde cativa Sector 5, Municipio do Sequele,
                     Provincia do Icolo e Bengo</li>
                <li><i class="bi bi-telephone-fill"></i> (+244) 946 684 772 / 937 876 773</li>
            </ul>
        </div>

        <div>
            <h6>Links</h6>
            <a href="{{ route('home') }}#inicio">Inicio</a>
            <a href="{{ route('home') }}#sobre">Sobre</a>
            <a href="{{ route('home') }}#servicos">Servicos</a>
            <a href="{{ route('home.catalogo') }}">Cursos</a>
            <a href="{{ route('home') }}#galeria">Galeria</a>
        </div>

        <div>
            <h6>Nossos servicos</h6>
            @forelse($categoriasall->take(5) as $categoria)
                <a href="{{ route('home.categorias', $categoria->id) }}">{{ $categoria->nome }}</a>
            @empty
                <a href="{{ route('home.catalogo') }}">Cursos profissionais</a>
                <a href="{{ route('home.catalogo') }}">Certificacao</a>
                <a href="{{ route('home.catalogo') }}">Formação online</a>
            @endforelse
        </div>

        <form class="newsletter-card">
            <h5>Newsletter</h5>
            <label class="visually-hidden" for="newsletter-email">Email</label>
            <div class="newsletter-field">
                <input id="newsletter-email" type="email" placeholder="Digite seu melhor e-mail">
                <i class="bi bi-at"></i>
            </div>
            <button type="submit">Assinar <i class="bi bi-arrow-right"></i></button>
            <div class="social-list">
                <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            </div>
        </form>
    </div>

    <div class="site-container footer-bottom">
        <p>&copy; {{ date('Y') }} Paruana Comercial. Todos os direitos reservados.</p>
    </div>
</footer>
