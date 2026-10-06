{{-- Address and directions --}}
<section id="visit" aria-labelledby="visit-title" class="rl-wrap rl-section rl-contact-visit">
    <div class="rl-contact-visit-copy">
        <p class="rl-contact-kicker">Notre maison du mieux-être</p>
        <h2 id="visit-title" class="rl-title">Votre parenthèse<br>commence ici.</h2>
        <address class="rl-contact-address">
            <strong>{{ $institute->street }}</strong>
            <span>{{ $institute->postalCode }} {{ $institute->city }}</span>
        </address>
        <p class="rl-prose">{{ $institute->accessNote }}</p>
        <div class="rl-contact-hours">
            <h3>Au rythme de vos rendez-vous</h3>
            <p>Sur rendez-vous uniquement,<br>du lundi au samedi.</p>
            @unless ($institute->isOpen)
                <p class="rl-contact-opening-date">Ouverture le mardi 3 novembre.</p>
            @endunless
        </div>
        <p class="rl-contact-access-note">Une question pour préparer votre venue&nbsp;? Écrivez-nous, nous vous guiderons.</p>
    </div>
    <div class="rl-contact-location">
        <figure class="rl-contact-map">
            <img src="/images/contact/map.svg" width="1200" height="800" loading="lazy" decoding="async" alt="Plan du quartier : Racines & Lumière, avenue des Charmes à Sciez, derrière le centre E.Leclerc.">
            <figcaption><a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">© les contributeurs d’OpenStreetMap<span class="sr-only"> (nouvel onglet)</span></a></figcaption>
        </figure>
        <nav class="rl-contact-directions" aria-label="Choisir une application pour l’itinéraire">
            <p>Venir jusqu’à nous</p>
            <div>
                <a href="https://www.google.com/maps/dir/?api=1&amp;destination={{ rawurlencode($institute->street.', '.$institute->postalCode.' '.$institute->city) }}" target="_blank" rel="noopener">Google Maps<span class="sr-only"> (nouvel onglet)</span><span aria-hidden="true">↗</span></a>
                <a href="https://www.waze.com/ul?ll={{ $institute->latitude }},{{ $institute->longitude }}&amp;navigate=yes" target="_blank" rel="noopener">Waze<span class="sr-only"> (nouvel onglet)</span><span aria-hidden="true">↗</span></a>
                <a href="https://maps.apple.com/?daddr={{ rawurlencode($institute->street.', '.$institute->postalCode.' '.$institute->city) }}" target="_blank" rel="noopener">Plans<span class="sr-only"> (nouvel onglet)</span><span aria-hidden="true">↗</span></a>
            </div>
        </nav>
    </div>
</section>
