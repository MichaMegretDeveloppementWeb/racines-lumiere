<section class="rl-wrap rl-brands-gallery" aria-labelledby="selection-title">
    <div class="rl-brands-introduction">
        <div>
            <p class="rl-brands-eyebrow">Notre sélection</p>
            <h2 id="selection-title" class="rl-title">Choisies avec soin.<br>Pour prendre soin.</h2>
        </div>
        <p class="rl-prose">Du soin en cabine aux gestes que l’on garde chez soi, chaque marque a sa place dans notre maison. Découvrez son univers et la façon dont elle accompagne votre rituel chez Racines &amp; Lumière.</p>
    </div>

    <div class="rl-brands-grid">
        @foreach ($brands as $brand)
            @include('web.brands.partials.brand', ['brand' => $brand])
        @endforeach

        <aside class="rl-brands-invitation" aria-labelledby="invitation-title">
            <x-web.media.texture sizes="(min-width: 64rem) 65vw, 100vw" />
            <p class="rl-brands-eyebrow">Le conseil fait partie du soin</p>
            <h2 id="invitation-title">Le bon soin,<br>au bon moment.<br><span>Pour vous.</span></h2>
            <p>Vous hésitez entre plusieurs approches ? Aurore et Lorie prennent le temps de vous écouter pour vous orienter vers le soin et les gestes qui vous correspondent.</p>
            <div class="rl-brands-invitation-links">
                <a class="rl-button rl-button-cream" href="{{ route('treatments') }}">Découvrir nos soins <x-web.media.icon name="arrow-right" class="size-4" /></a>
                <a class="rl-text-link" href="{{ route('contact') }}">Échanger avec nous <x-web.media.icon name="arrow-right" class="size-4" /></a>
            </div>
        </aside>
    </div>
</section>
