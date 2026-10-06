<article id="{{ $brand->slug }}" class="rl-brand-card" x-data="brandDialog">
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
        <div class="rl-brand-actions">
            <button type="button" x-ref="toggle" @click="open" :aria-expanded="isOpen" aria-haspopup="dialog" aria-controls="{{ $brand->slug }}-details" class="rl-brand-toggle" x-cloak>
                <span>Découvrir la marque<span class="sr-only"> {{ $brand->name }}</span></span>
                <x-web.media.icon name="arrow-right" class="size-4" />
            </button>
            <a class="rl-brand-products" href="{{ $brand->productsUrl ?? $institute->bookingUrl }}" target="_blank" rel="noopener noreferrer">{{ $brand->productsUrl === null ? 'Retrouvez-nous sur Booksy' : 'Voir les produits sur Booksy' }}<span class="sr-only"> ({{ $brand->name }}, nouvel onglet)</span> <x-web.media.icon name="arrow-right" class="size-4" /></a>
        </div>
    </div>
    @include('web.brands.partials.brand-dialog', ['brand' => $brand])
</article>
