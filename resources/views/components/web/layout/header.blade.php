<header class="rl-header" x-data="scrollHeader" :data-enhanced="true" :data-scrolled="isScrolled">
    <div class="rl-header-inner">
        <a href="{{ route('home') }}" class="rl-brand" aria-label="Racines & Lumière, accueil">
            <x-web.media.picture name="brand/monogram-gold" :widths="[96, 192]" sizes="(min-width: 48rem) 72px, 56px" alt="" :width="96" :height="96" fallback="webp" class="rl-brand-mark" />
            <span>
                <span class="rl-brand-name">Racines &amp; Lumière</span>
                <span class="rl-brand-caption">Rituels bien-être, beauté vivante</span>
            </span>
        </a>

        <nav aria-label="Menu principal" class="rl-nav" x-data="siteMenu" @keydown.escape.window="close()" @keydown.tab="keepFocusInside($event)" @click.outside="close()" @resize.window="closeIfHidden()">
            <ul class="rl-nav-desktop">
                @foreach ($links() as $link)
                    <li><a href="{{ $link['url'] }}" @if ($link['isCurrent']) aria-current="page" @endif>{{ $link['label'] }}</a></li>
                @endforeach
            </ul>
            <button type="button" x-ref="toggle" class="rl-menu-toggle" aria-controls="site-menu" :aria-expanded="isOpen" @click="toggle()">Menu</button>
            <ul id="site-menu" x-ref="panel" :data-open="isOpen" class="rl-nav-mobile">
                @foreach ($links() as $link)
                    <li><a href="{{ $link['url'] }}" @if ($link['isCurrent']) aria-current="page" @endif>{{ $link['label'] }}</a></li>
                @endforeach
                <li><a href="{{ $institute->bookingUrl }}" target="_blank" rel="noopener">Réserver sur Booksy<span class="sr-only"> (nouvel onglet)</span></a></li>
            </ul>
        </nav>

        <x-web.booking.button class="rl-header-booking">Réserver</x-web.booking.button>
    </div>
</header>
