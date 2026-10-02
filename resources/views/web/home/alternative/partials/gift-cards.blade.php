<section aria-labelledby="gift-cards-title" class="alt-panel alt-gift-cards">
    <div class="alt-gift-image">
        <x-web.media.picture name="home/alternative/gift" :widths="[480, 960]" sizes="(min-width: 48rem) 42vw, 100vw" alt="" :width="480" :height="600" class="size-full object-cover" />
    </div>
    <div class="alt-gift-copy">
        <div class="alt-gift-texture" aria-hidden="true">
            <x-web.media.picture name="home/alternative/linen-v3" :widths="[480, 960]" sizes="(min-width: 48rem) 56vw, 100vw" alt="" :width="480" :height="320" class="size-full object-cover" />
        </div>
        <h2 id="gift-cards-title" class="alt-title">Offrir un rituel</h2>
        <p>Offrez un moment chez Racines &amp; Lumière&nbsp;: nos cartes cadeaux sont disponibles en ligne.</p>
        <a href="{{ $institute->giftCardsUrl }}" target="_blank" rel="noopener" class="alt-button alt-button-cream">Offrir une carte cadeau<span class="sr-only"> sur Booksy (nouvel onglet)</span></a>
    </div>
</section>
