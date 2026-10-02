<section aria-labelledby="hero-title" class="alt-panel alt-panel-wide alt-hero">
    <div class="alt-hero-copy">
        <h1 id="hero-title">Vous ne choisissez pas votre soin.<span>Nous le créons avec vous.</span></h1>
        <p class="alt-hero-description">Rituels holistiques pour le corps et le visage.</p>
        <a href="{{ $institute->bookingUrl }}" target="_blank" rel="noopener" class="alt-button alt-button-terracotta">{{ $institute->isOpen ? 'Réserver mon rituel' : 'Réserver dès maintenant' }}<span class="sr-only"> sur Booksy (nouvel onglet)</span></a>
        <div class="alt-hero-meta">
            <p class="alt-hero-location">{{ $institute->city }}, en Chablais · Sur rendez-vous</p>
            @if ($reviewSummary !== null)
                <a href="#reviews-title" class="alt-hero-rating">{{ $reviewSummary->averageLabel() }}/5 · {{ $reviewSummary->count }} avis Booksy</a>
            @endif
        </div>
    </div>
    <div class="alt-hero-texture" aria-hidden="true">
        <x-web.media.picture name="home/alternative/linen-v3" :widths="[480, 960]" sizes="100vw" alt="" :width="480" :height="320" class="size-full object-cover" />
    </div>
    <div class="alt-hero-image">
        <x-web.media.picture name="home/alternative/hero-v3" :widths="[480, 960, 1440]" sizes="100vw" alt="" :width="480" :height="320" is-priority class="size-full object-cover" />
    </div>
</section>
