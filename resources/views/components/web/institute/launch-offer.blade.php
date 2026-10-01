@if (! $institute->isOpen && $institute->launchOffer !== null)
    <aside {{ $attributes->class('border border-terracotta/40 bg-cream px-6 py-6 text-center') }} aria-labelledby="launch-offer-title">
        <p id="launch-offer-title" class="font-display text-lg tracking-[0.08em] text-terracotta">Offre de lancement</p>
        <p class="mt-3">
            Réservez dès maintenant votre soin du mois de novembre et bénéficiez de {{ $institute->launchOffer->discount }} de remise.
            @if ($institute->launchOffer->conditions !== null)
                {{ $institute->launchOffer->conditions }}
            @endif
        </p>
    </aside>
@endif
