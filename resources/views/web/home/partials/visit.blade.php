<section aria-labelledby="visit-title" class="px-6 py-24 lg:py-40">
    <div class="mx-auto grid max-w-[77.5rem] items-center gap-16 lg:grid-cols-[1.05fr_0.95fr] lg:gap-24 lg:px-10">
        <div>
            <x-web.typography.eyebrow>{{ $institute->city }}, en Chablais</x-web.typography.eyebrow>
            <h2 id="visit-title" class="mt-6 text-[2.5rem] leading-none lg:text-[3.5rem]">Nous <span class="text-terracotta">trouver</span></h2>
            <address class="mt-9 font-display text-xl leading-normal tracking-[0.03em] not-italic lg:text-2xl">
                {{ $institute->street }}<br>
                {{ $institute->postalCode }} {{ $institute->city }}
            </address>

            <dl class="mt-11 grid gap-7 sm:grid-cols-2 sm:gap-9">
                @if ($institute->accessNote !== null)
                    <div>
                        <x-web.typography.eyebrow as="dt">Accès</x-web.typography.eyebrow>
                        <dd class="mt-3 leading-[1.8] text-olive">{{ $institute->accessNote }}</dd>
                    </div>
                @endif
                <div>
                    <x-web.typography.eyebrow as="dt">Horaires</x-web.typography.eyebrow>
                    <dd class="mt-3 leading-[1.8] text-olive"><x-web.institute.opening-hours /></dd>
                </div>
            </dl>

            <x-web.navigation.pill-link :href="route('contact')" tone="forest" class="mt-12">Accès et contact</x-web.navigation.pill-link>
        </div>

        <div aria-hidden="true" class="relative">
            <div class="aspect-[4/5] rounded-t-full rounded-b-[1.75rem] bg-[radial-gradient(ellipse_at_62%_42%,var(--color-honey)_0%,var(--color-sand)_45%,var(--color-olive)_120%)]"></div>
            <svg viewBox="0 0 132 132" class="absolute -bottom-7 -left-2.5 size-28 rounded-full bg-cream shadow-[0_24px_50px_-24px_rgb(60_40_20/0.45)] lg:bottom-[4.375rem] lg:-left-14 lg:size-[8.25rem]">
                <defs>
                    <path id="visit-seal-ring" d="M66 66m-47 0a47 47 0 1 1 94 0a47 47 0 1 1-94 0" />
                </defs>
                <text class="fill-terracotta font-display text-[9.2px] tracking-[2.6px]"><textPath href="#visit-seal-ring" textLength="292" lengthAdjust="spacing">SUR RENDEZ-VOUS · DU LUNDI AU SAMEDI ·</textPath></text>
                <use href="#icon-sun" x="52" y="52" width="28" height="28" class="text-gold" />
            </svg>
        </div>
    </div>
</section>
