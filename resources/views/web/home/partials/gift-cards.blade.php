<section aria-labelledby="gift-cards-title" class="rl-panel rl-gift-cards">
    <div class="rl-gift-image">
        <x-web.media.picture name="home/gift" :widths="[480, 960]" sizes="(min-width: 48rem) 42vw, 100vw" alt="" :width="480" :height="600" class="size-full object-cover" />
    </div>
    <div class="rl-gift-copy">
        <div class="rl-gift-texture" aria-hidden="true">
            <x-web.media.picture name="home/linen" :widths="[480, 960]" sizes="(min-width: 48rem) 56vw, 100vw" alt="" :width="480" :height="320" class="size-full object-cover" />
        </div>
        <h2 id="gift-cards-title" class="rl-title">Offrir un rituel</h2>
        <p>Offrez un moment chez Racines &amp; Lumière&nbsp;: nos cartes cadeaux sont disponibles en ligne.</p>
        <x-web.booking.button :href="$institute->giftCardsUrl" tone="cream">Offrir une carte cadeau</x-web.booking.button>
    </div>
</section>
