<header class="alt-header" x-data="scrollHeader" :data-enhanced="true" :data-scrolled="isScrolled">
    <div class="alt-header-inner">
        <a href="{{ route('home') }}" class="alt-brand" aria-label="Racines & Lumière, accueil">
            <x-web.media.picture name="brand/monogram-gold" :widths="[96, 192]" sizes="(min-width: 48rem) 72px, 56px" alt="" :width="96" :height="96" fallback="webp" class="alt-brand-mark" />
            <span>
                <span class="alt-brand-name">Racines &amp; Lumière</span>
                <span class="alt-brand-caption">Rituels bien-être, beauté vivante</span>
            </span>
        </a>

        <nav aria-label="Menu principal" class="alt-nav" x-data="siteMenu" @keydown.escape.window="close()" @keydown.tab="keepFocusInside($event)" @click.outside="close()">
            <ul class="alt-nav-desktop">
                <li><a href="{{ route('treatments') }}">Nos soins</a></li>
                <li><a href="{{ route('brands') }}">Nos marques</a></li>
                <li><a href="{{ route('story') }}">Notre histoire</a></li>
                @if ($institute->hasTrustedCircle)
                    <li><a href="{{ route('trusted-circle') }}">Cercle de confiance</a></li>
                @endif
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
            <button type="button" x-ref="toggle" class="alt-menu-toggle" aria-controls="alternative-menu" :aria-expanded="isOpen" @click="toggle()">Menu</button>
            <ul id="alternative-menu" x-ref="panel" :data-open="isOpen" class="alt-nav-mobile">
                <li><a href="{{ route('treatments') }}">Nos soins</a></li>
                <li><a href="{{ route('brands') }}">Nos marques partenaires</a></li>
                <li><a href="{{ route('story') }}">Notre histoire</a></li>
                @if ($institute->hasTrustedCircle)
                    <li><a href="{{ route('trusted-circle') }}">Cercle de confiance</a></li>
                @endif
                <li><a href="{{ route('contact') }}">Contact</a></li>
                <li><a href="{{ $institute->bookingUrl }}" target="_blank" rel="noopener">Réserver sur Booksy<span class="sr-only"> (nouvel onglet)</span></a></li>
            </ul>
        </nav>

        <a href="{{ $institute->bookingUrl }}" target="_blank" rel="noopener" class="alt-button alt-button-terracotta alt-header-booking">Réserver<span class="sr-only"> sur Booksy (nouvel onglet)</span></a>
    </div>
</header>
