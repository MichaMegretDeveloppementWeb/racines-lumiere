<footer class="rl-panel rl-panel-wide rl-footer">
    <div class="rl-wrap">
        <div class="rl-footer-main">
            <a href="{{ route('home') }}" class="rl-footer-brand">
                <x-web.media.picture name="brand/logo-gold" :widths="[280, 560]" sizes="210px" alt="Racines & Lumière, rituels bien-être, beauté vivante, accueil" :width="280" :height="313" fallback="webp" />
            </a>
            <div class="rl-footer-address">
                <h2>La maison</h2>
                <address>{{ $institute->street }}<br>{{ $institute->postalCode }} {{ $institute->city }}</address>
                <x-web.institute.opening-hours />
                @if ($institute->phone !== null)
                    <a href="tel:{{ str_replace(' ', '', $institute->phone) }}">{{ $institute->phone }}</a>
                @endif
                <a href="mailto:{{ $institute->email }}"><span>{{ $institute->email }}</span></a>
                <a href="{{ route('contact') }}">Nous contacter</a>
            </div>
            <div class="rl-footer-links">
                <h2>Votre prochain rituel</h2>
                <ul>
                    <li><a href="{{ $institute->bookingUrl }}" target="_blank" rel="noopener">Réserver sur Booksy<span class="sr-only"> (nouvel onglet)</span></a></li>
                    <li><a href="{{ $institute->giftCardsUrl }}" target="_blank" rel="noopener">Offrir une carte cadeau<span class="sr-only"> (nouvel onglet)</span></a></li>
                    <li><a href="{{ $institute->instagramUrl }}" target="_blank" rel="noopener">Instagram<span class="sr-only"> (nouvel onglet)</span></a></li>
                    @if ($institute->hasTrustedCircle)
                        <li><a href="{{ route('trusted-circle') }}">Cercle de confiance</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="rl-footer-bottom">
            <p>© 2026 Racines &amp; Lumière</p>
            <ul>
                <li><a href="{{ route('legal.notice') }}">Mentions légales</a></li>
                <li><a href="{{ route('legal.privacy') }}">Politique de confidentialité</a></li>
            </ul>
        </div>
    </div>
</footer>
