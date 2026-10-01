<section class="relative isolate flex min-h-[calc(100svh-4rem)] items-center justify-center overflow-hidden bg-forest px-4 py-16 text-center text-cream [--color-focus:var(--color-gold)] lg:min-h-[calc(100svh-5rem)]">
    <div class="mx-auto flex max-w-2xl flex-col items-center gap-7">
        <h1>
            <x-web.media.picture
                name="brand/logo-gold"
                :widths="[280, 560]"
                sizes="(min-width: 64rem) 280px, 200px"
                alt="Racines & Lumière"
                :width="280"
                :height="313"
                fallback="webp"
                is-priority
                class="h-auto w-[200px] lg:w-[280px]"
            />
        </h1>
        <p class="font-display text-xl leading-snug tracking-[0.04em] sm:text-2xl">
            Vous ne choisissez pas votre soin. Nous le créons avec vous.
        </p>
        <p class="text-sand">Rituels holistiques pour le corps et le visage.</p>
        <x-web.booking.button :label="$institute->isOpen ? 'Réserver mon rituel' : 'Réserver dès maintenant'" :on-dark="true" />
    </div>
</section>
