<section aria-labelledby="visit-title" class="rl-wrap rl-visit rl-section">
    <div>
        <h2 id="visit-title" class="rl-title">Nous trouver</h2>
        <address class="rl-visit-address">{{ $institute->street }}<br>{{ $institute->postalCode }} {{ $institute->city }}</address>
        <dl class="rl-visit-details">
            @if ($institute->accessNote !== null)
                <div><dt>Accès</dt><dd>{{ $institute->accessNote }}</dd></div>
            @endif
            <div><dt>Horaires</dt><dd><x-web.institute.opening-hours /></dd></div>
        </dl>
        <a href="{{ route('contact') }}" class="rl-button rl-button-forest">Accès et contact</a>
    </div>
    <div class="rl-visit-image">
        <x-web.media.picture name="home/house-1" :widths="[440, 880]" sizes="(min-width: 80rem) 510px, (min-width: 48rem) 38vw, 90vw" alt="" :width="440" :height="587" class="size-full object-cover" />
    </div>
</section>
