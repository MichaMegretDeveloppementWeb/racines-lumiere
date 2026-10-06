<dialog id="{{ $brand->slug }}-details" x-ref="dialog" class="rl-brand-dialog" aria-labelledby="{{ $brand->slug }}-dialog-title" @click="closeOnBackdrop" @close="restoreFocus" @keydown.tab="keepFocusInside">
    <div class="rl-brand-dialog-header">
        <h2 id="{{ $brand->slug }}-dialog-title">{{ $brand->name }}</h2>
        <button type="button" class="rl-brand-dialog-close" @click="close" autofocus aria-label="Fermer la présentation de {{ $brand->name }}">
            <span>Fermer</span><span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="rl-brand-dialog-body" x-ref="body">
        <div class="rl-brand-dialog-identity">
            <div class="rl-brand-logo rl-brand-logo-{{ $brand->slug }}">
                @if ($brand->logoPath !== null)
                    <img src="{{ asset($brand->logoPath) }}" alt="" width="280" height="112" loading="lazy" decoding="async">
                @else
                    <span class="rl-brand-wordmark">{{ $brand->name }}</span>
                @endif
            </div>
            @if ($brand->tagline !== null)
                <p class="rl-brand-dialog-tagline">{{ $brand->tagline }}</p>
            @endif
            @if ($brand->roleText !== null)
                <div class="rl-brand-role">
                    <h3>Dans notre maison</h3>
                    <p>{{ $brand->roleText }}</p>
                </div>
            @endif
        </div>
        <div class="rl-brand-dialog-text">
            @foreach ($brand->paragraphs as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
            @if ($brand->productsUrl !== null)
                <a class="rl-button rl-button-forest" href="{{ $brand->productsUrl }}" target="_blank" rel="noopener noreferrer">Voir les produits sur Booksy<span class="sr-only"> de {{ $brand->name }} (nouvel onglet)</span> <x-web.media.icon name="arrow-right" class="size-4" /></a>
            @endif
        </div>
    </div>
</dialog>
