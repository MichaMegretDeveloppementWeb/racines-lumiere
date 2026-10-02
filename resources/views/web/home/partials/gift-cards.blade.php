<section aria-labelledby="gift-cards-title" class="relative isolate mx-2 grid place-items-center overflow-hidden rounded-3xl px-3.5 py-14 lg:mx-3.5 lg:rounded-[2rem] lg:py-28">
    <div aria-hidden="true" class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_18%_30%,var(--color-honey)_0%,transparent_22%),radial-gradient(circle_at_82%_70%,var(--color-honey)_0%,transparent_26%),linear-gradient(135deg,var(--color-gold),var(--color-terracotta))]"></div>
    <x-web.media.picture name="home/gift" :widths="[640, 1280]" sizes="100vw" alt="" :width="1280" :height="640" class="absolute inset-0 -z-10 size-full object-cover" />

    <div class="w-full max-w-[29.375rem] rounded-t-[14.7rem] rounded-b-[1.75rem] bg-terracotta px-7 pt-20 pb-10 text-center text-cream shadow-[0_50px_80px_-40px_rgb(30_14_4/0.6)] [--color-focus:var(--color-gold)] lg:px-14 lg:pt-24 lg:pb-14">
        <x-web.media.icon name="sun" class="mx-auto size-[1.875rem] text-honey" />
        <x-web.typography.eyebrow on-dark :has-sun="false" class="mt-5 justify-center">Cartes cadeaux</x-web.typography.eyebrow>
        <h2 id="gift-cards-title" class="mt-4 text-[1.9rem] leading-[1.12] lg:text-[2.5rem]">Offrir <span class="text-honey">un rituel</span></h2>
        <p class="mt-5 leading-[1.8] text-cream/85">Offrez un moment chez Racines &amp; Lumière&nbsp;: nos cartes cadeaux sont disponibles en ligne.</p>
        <x-web.booking.button label="Offrir une carte cadeau" :href="$institute->giftCardsUrl" tone="cream" class="mt-9" />
    </div>
</section>
