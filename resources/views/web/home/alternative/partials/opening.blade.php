<section aria-labelledby="opening-title" class="alt-wrap alt-opening">
    <div class="alt-opening-texture" aria-hidden="true">
        <x-web.media.picture name="home/alternative/linen-v3" :widths="[480, 960]" sizes="(min-width: 80rem) 1216px, 90vw" alt="" :width="480" :height="320" class="size-full object-cover" />
    </div>
    <div class="alt-opening-intro">
        <div>
            <h2 id="opening-title">Prochainement, l'ouverture de notre Maison du Mieux-Être&nbsp;!</h2>
            <p>Notre Maison du Mieux-Être ouvre ses portes à Sciez le mardi 3 novembre 2026. Vous pouvez réserver votre rituel dès maintenant.</p>
        </div>
        <time datetime="2026-11-03" class="alt-opening-date"><span>3</span><span>novembre<br>2026</span></time>
    </div>
    @if ($institute->launchOffer !== null)
        <div class="alt-launch-offer">
            <h3>Offre de lancement</h3>
            <p>Réservez dès maintenant votre soin du mois de novembre et bénéficiez de {{ $institute->launchOffer->discount }} de remise.</p>
            @if ($institute->launchOffer->conditions !== null)
                <p>{{ $institute->launchOffer->conditions }}</p>
            @endif
        </div>
    @endif
</section>
