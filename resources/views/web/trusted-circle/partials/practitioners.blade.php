<section class="rl-panel rl-circle-practitioners" aria-labelledby="practitioners-title">
    <x-web.media.texture />
    <div class="rl-wrap">
        <div class="rl-circle-introduction">
            <h2 id="practitioners-title">Des rencontres<br>qui font du bien.</h2>
            <p>Autour de Racines &amp; Lumière, des professionnels du Chablais que nous souhaitons vous faire découvrir. Chacun vous accueille dans son univers, avec sa propre approche.</p>
        </div>
        <ul class="rl-circle-directory">
            @foreach ($partners as $partner)
                <li id="{{ $partner->slug }}" class="rl-circle-person">
                    <div class="rl-circle-identity">
                        <h3>{{ $partner->name }}</h3>
                        @if ($partner->organizationName !== null)
                            <p>{{ $partner->organizationName }}</p>
                        @endif
                    </div>
                    <p class="rl-circle-specialty">{{ $partner->specialty }}</p>
                    <div class="rl-circle-details">
                        @if ($partner->town !== null)
                            <p>{{ $partner->town }}</p>
                        @endif
                        @if ($partner->websiteUrl !== null)
                            <a href="{{ $partner->websiteUrl }}" target="_blank" rel="noopener noreferrer">Découvrir son site<span class="sr-only"> : {{ $partner->name }} (nouvel onglet)</span> <x-web.media.icon name="arrow-right" class="size-4" /></a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</section>
