@props(['heading' => 'h3'])

@if (! $institute->isOpen && $institute->launchOffer !== null)
    <div {{ $attributes->class('rl-launch-offer') }}>
        <{{ $heading }}>Offre de lancement</{{ $heading }}>
        <p>Réservez dès maintenant votre soin du mois de novembre et bénéficiez de {{ $institute->launchOffer->discount }} de remise.</p>
        @if ($institute->launchOffer->conditions !== null)
            <p>{{ $institute->launchOffer->conditions }}</p>
        @endif
    </div>
@endif
