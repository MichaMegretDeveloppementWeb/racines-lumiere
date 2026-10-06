<article id="{{ $brand->slug }}" class="rl-brand-card" x-data="brandDisclosure" @keydown.escape.stop="close()" @click.outside="close(false)" :data-open="isOpen">
    <div class="rl-brand-logo rl-brand-logo-{{ $brand->slug }}">
        @if ($brand->logoPath !== null)
            <img src="{{ asset($brand->logoPath) }}" alt="{{ $brand->name }}" width="280" height="112" loading="lazy" decoding="async">
        @else
            <span class="rl-brand-wordmark">{{ $brand->name }}</span>
        @endif
    </div>
    <div class="rl-brand-copy">
        <h3 id="{{ $brand->slug }}-title">{{ $brand->name }}</h3>
        @if ($brand->tagline !== null)
            <p class="rl-brand-tagline">{{ $brand->tagline }}</p>
        @endif
        @if ($brand->shortText !== null)
            <p class="rl-brand-summary">{{ $brand->shortText }}</p>
        @endif
        <button type="button" x-ref="toggle" @click="toggle" :aria-expanded="isOpen" aria-controls="{{ $brand->slug }}-details" class="rl-brand-toggle" x-cloak>
            <span x-show="!isOpen">En savoir plus<span class="sr-only"> sur {{ $brand->name }}</span></span>
            <span x-show="isOpen">Refermer<span class="sr-only"> la présentation de {{ $brand->name }}</span></span>
            <span class="rl-brand-toggle-sign" aria-hidden="true"></span>
        </button>
        <div id="{{ $brand->slug }}-details" class="rl-brand-details" x-show="isOpen" x-collapse role="region" aria-labelledby="{{ $brand->slug }}-title">
            <div class="rl-brand-details-inner">
                @foreach ($brand->paragraphs as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
                @if ($brand->roleText !== null)
                    <div class="rl-brand-role">
                        <h4>Dans notre maison</h4>
                        <p>{{ $brand->roleText }}</p>
                    </div>
                @endif
                @if ($brand->productsUrl !== null)
                    <a class="rl-text-link" href="{{ $brand->productsUrl }}" target="_blank" rel="noopener noreferrer">Voir les produits sur Booksy<span class="sr-only"> (nouvel onglet)</span> <x-web.media.icon name="arrow-right" class="size-4" /></a>
                @endif
            </div>
        </div>
    </div>
</article>
