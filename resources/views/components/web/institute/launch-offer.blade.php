@if (! $institute->isOpen && $institute->launchOffer !== null)
    <aside {{ $attributes->class('rounded-[1.75rem] bg-cream px-7 py-8 shadow-[0_24px_50px_-30px_rgb(60_40_20/0.45)] lg:px-10 lg:py-10') }} aria-labelledby="launch-offer-title">
        <x-web.typography.eyebrow id="launch-offer-title">Offre de lancement</x-web.typography.eyebrow>
        <p class="mt-5 leading-relaxed">
            Réservez dès maintenant votre soin du mois de novembre et bénéficiez de {{ $institute->launchOffer->discount }} de remise.
            @if ($institute->launchOffer->conditions !== null)
                {{ $institute->launchOffer->conditions }}
            @endif
        </p>
    </aside>
@endif
