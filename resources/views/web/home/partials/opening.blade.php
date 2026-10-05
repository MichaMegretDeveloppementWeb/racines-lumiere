<section aria-labelledby="opening-title" class="rl-wrap rl-opening">
    <div class="rl-opening-texture" aria-hidden="true">
        <x-web.media.picture name="home/linen" :widths="[480, 960]" sizes="(min-width: 80rem) 1216px, 90vw" alt="" :width="480" :height="320" class="size-full object-cover" />
    </div>
    <div class="rl-opening-intro">
        <div>
            <h2 id="opening-title">Prochainement, l'ouverture de notre Maison du Mieux-Être&nbsp;!</h2>
            <p>Notre Maison du Mieux-Être ouvre ses portes à Sciez le mardi 3 novembre 2026. Vous pouvez réserver votre rituel dès maintenant.</p>
        </div>
        <time datetime="2026-11-03" class="rl-opening-date"><span>3</span><span>novembre<br>2026</span></time>
    </div>
    @if ($institute->launchOffer !== null)
        <div class="rl-launch-offer">
            <h3>Offre de lancement</h3>
            <p>Réservez dès maintenant votre soin du mois de novembre et bénéficiez de {{ $institute->launchOffer->discount }} de remise.</p>
            @if ($institute->launchOffer->conditions !== null)
                <p>{{ $institute->launchOffer->conditions }}</p>
            @endif
        </div>
    @endif
</section>
