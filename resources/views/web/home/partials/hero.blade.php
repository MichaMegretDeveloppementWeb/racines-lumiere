<section aria-labelledby="hero-title" class="rl-panel rl-panel-wide rl-hero">
    <div class="rl-hero-copy">
        <h1 id="hero-title">Vous ne choisissez pas votre soin.<span>Nous le créons avec vous.</span></h1>
        <p class="rl-hero-description">Rituels holistiques pour le corps et le visage.</p>
        <x-web.booking.button>{{ $institute->isOpen ? 'Réserver mon rituel' : 'Réserver dès maintenant' }}</x-web.booking.button>
        <div class="rl-hero-meta">
            <p class="rl-hero-location">{{ $institute->city }}, en Chablais · Sur rendez-vous</p>
            @if ($reviewSummary !== null)
                <a href="#reviews-title" class="rl-hero-rating">{{ $reviewSummary->averageLabel() }}/5 · {{ $reviewSummary->count }} avis Booksy</a>
            @endif
        </div>
    </div>
    <div class="rl-hero-texture" aria-hidden="true">
        <x-web.media.picture name="home/linen" :widths="[480, 960]" sizes="100vw" alt="" :width="480" :height="320" class="size-full object-cover" />
    </div>
    <div class="rl-hero-image">
        <x-web.media.picture name="home/hero" :widths="[480, 960, 1440]" sizes="100vw" alt="" :width="480" :height="320" is-priority class="size-full object-cover" />
    </div>
</section>
