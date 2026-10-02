<section aria-labelledby="hero-title" class="relative isolate mx-2 mt-2 flex min-h-[max(44rem,calc(100svh-1rem))] flex-col overflow-hidden rounded-3xl lg:mx-3.5 lg:mt-3.5 lg:min-h-[max(45rem,calc(100svh-1.75rem))] lg:rounded-[2rem]">
    <div aria-hidden="true" class="absolute inset-0 -z-20 bg-[radial-gradient(ellipse_at_72%_32%,var(--color-cream)_0%,var(--color-honey)_26%,var(--color-sand)_100%)]"></div>
    <x-web.media.picture
        name="home/hero"
        :widths="[960, 1440, 1920]"
        sizes="100vw"
        portrait-name="home/hero-portrait"
        :portrait-widths="[600, 900, 1200]"
        alt=""
        :width="1920"
        :height="1280"
        is-priority
        class="absolute inset-0 -z-20 size-full object-cover object-[74%_38%] portrait:object-[50%_24%]"
    />
    <div aria-hidden="true" class="absolute inset-0 -z-10 bg-linear-to-t from-paper from-10% via-paper/90 via-45% to-paper/0 to-80% lg:bg-linear-to-r lg:from-paper/95 lg:from-0% lg:via-paper/80 lg:via-35% lg:to-paper/0 lg:to-65%"></div>

    <div class="flex flex-1 flex-col justify-end px-6 pt-32 pb-28 lg:max-w-[45rem] lg:justify-center lg:px-[4.5rem] lg:pt-32 lg:pb-32">
        <x-web.typography.eyebrow>Maison du Mieux-Être</x-web.typography.eyebrow>

        <h1 id="hero-title" class="mt-5 lg:mt-7">
            <span class="block text-sm tracking-[0.07em] text-olive sm:text-lg lg:text-[1.3125rem]">Vous ne choisissez pas votre soin.</span>
            <span class="mt-3 block text-[2.3rem] leading-[1.08] sm:text-5xl lg:mt-3.5 lg:text-[4.25rem]">Nous le créons <span class="relative inline-block text-terracotta">avec vous.<svg aria-hidden="true" focusable="false" viewBox="0 0 400 24" preserveAspectRatio="none" class="absolute -bottom-3 -left-[3%] h-4 w-[108%] text-gold lg:-bottom-4 lg:h-[1.375rem]"><path d="M2 19C70 13 170 9.5 300 9c42-.2 72-2.5 98-6.5-24 7-56 9.7-98 10.1C172 13.4 82 16 2 19Z" fill="currentColor" /></svg></span></span>
        </h1>

        <p class="mt-7 max-w-[44ch] text-olive lg:mt-8 lg:text-[1.09375rem]">Rituels holistiques pour le corps et le visage.</p>

        <div class="mt-7 flex flex-wrap items-center gap-x-8 gap-y-3 lg:mt-10">
            <x-web.booking.button :label="$institute->isOpen ? 'Réserver mon rituel' : 'Réserver dès maintenant'" />
            <x-web.navigation.arrow-link :href="route('treatments')">Découvrir la carte</x-web.navigation.arrow-link>
        </div>
    </div>

    <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-4 p-3 lg:p-6 lg:pl-12">
        <p class="hidden min-h-[3.125rem] items-center gap-4 rounded-full bg-cream/60 px-6 text-[0.84375rem] tracking-[0.02em] backdrop-blur-lg xl:flex">
            <x-web.institute.opening-hours />
            <x-web.media.icon name="sun" class="size-3.5 text-gold" />
            <span>{{ $institute->city }}, en Chablais</span>
        </p>

        @if ($reviewSummary !== null)
            <a href="{{ $institute->booksyProfileUrl }}" target="_blank" rel="noopener" class="ml-auto flex items-center gap-3.5 rounded-full bg-cream/60 py-1.5 pr-5 pl-1.5 backdrop-blur-lg transition-colors hover:bg-cream/85">
                <span class="grid size-11 place-items-center rounded-full bg-forest font-display text-sm text-honey">{{ $reviewSummary->averageLabel() }}/5</span>
                <span class="leading-tight">
                    <span class="flex gap-0.5 text-gold">
                        @for ($star = 0; $star < 5; $star++)
                            <x-web.media.icon name="star" class="size-3" />
                        @endfor
                    </span>
                    <span class="mt-1 block text-xs text-olive">{{ $reviewSummary->count }} avis Booksy<span class="sr-only"> (nouvel onglet)</span></span>
                </span>
            </a>
        @endif
    </div>
</section>
