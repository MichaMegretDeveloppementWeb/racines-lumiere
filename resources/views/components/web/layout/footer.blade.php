<footer class="mx-2 mb-2 overflow-hidden rounded-3xl bg-forest text-cream [--color-focus:var(--color-gold)] lg:mx-3.5 lg:mb-3.5 lg:rounded-[2rem]">
    <div class="mx-auto grid max-w-[77.5rem] gap-x-6 gap-y-12 px-6 pt-16 pb-12 sm:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr] lg:gap-x-12 lg:px-16 lg:pt-24 lg:pb-16">
        <div class="sm:col-span-2 lg:col-span-1">
            <x-web.media.picture
                name="brand/monogram-gold"
                :widths="[96, 192]"
                sizes="88px"
                alt=""
                :width="96"
                :height="96"
                fallback="webp"
                class="size-[4.25rem] lg:size-[5.5rem]"
            />
            <p class="mt-7 max-w-[24ch] font-display text-[1.0625rem] leading-relaxed tracking-[0.03em]">Vous ne choisissez pas votre soin. Nous le créons avec vous.</p>
        </div>

        <div>
            <x-web.typography.eyebrow as="h2" on-dark>La maison</x-web.typography.eyebrow>
            <address class="mt-6 text-[0.9375rem] leading-relaxed text-cream/80 not-italic">
                {{ $institute->street }}<br>
                {{ $institute->postalCode }} {{ $institute->city }}
            </address>
            <x-web.institute.opening-hours class="mt-3 block text-[0.9375rem] leading-relaxed text-cream/80" />
            <ul class="mt-2 text-[0.9375rem] text-cream/80">
                @if ($institute->phone !== null)
                    <li><a href="tel:{{ str_replace(' ', '', $institute->phone) }}" class="inline-flex min-h-11 items-center hover:text-honey">{{ $institute->phone }}</a></li>
                @endif
                <li><a href="{{ $institute->instagramUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center hover:text-honey">Instagram<span class="sr-only"> (nouvel onglet)</span></a></li>
            </ul>
        </div>

        <div>
            <x-web.typography.eyebrow as="h2" on-dark>Réserver</x-web.typography.eyebrow>
            <ul class="mt-4 text-[0.9375rem] text-cream/80">
                <li><a href="{{ $institute->bookingUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center hover:text-honey">Sur rendez-vous uniquement · Réserver en ligne<span class="sr-only"> (nouvel onglet)</span></a></li>
                <li><a href="{{ $institute->giftCardsUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center hover:text-honey">Offrir une carte cadeau<span class="sr-only"> (nouvel onglet)</span></a></li>
                @if ($institute->hasTrustedCircle)
                    <li><a href="{{ route('trusted-circle') }}" class="inline-flex min-h-11 items-center hover:text-honey">Cercle de confiance</a></li>
                @endif
            </ul>
        </div>

        <div>
            <x-web.typography.eyebrow as="h2" on-dark>Informations</x-web.typography.eyebrow>
            <ul class="mt-4 text-[0.9375rem] text-cream/80">
                <li><a href="{{ route('legal.notice') }}" class="inline-flex min-h-11 items-center hover:text-honey">Mentions légales</a></li>
                <li><a href="{{ route('legal.privacy') }}" class="inline-flex min-h-11 items-center hover:text-honey">Politique de confidentialité</a></li>
            </ul>
        </div>
    </div>

    <div class="mx-auto flex max-w-[77.5rem] flex-col gap-1 border-t border-cream/10 px-6 py-4 text-sm text-cream/60 sm:flex-row sm:items-center sm:justify-between lg:px-16">
        <p>© 2026 Racines &amp; Lumière</p>
        <a href="mailto:{{ $institute->email }}" class="inline-flex min-h-11 items-center hover:text-honey">{{ $institute->email }}</a>
    </div>

    <p aria-hidden="true" class="translate-y-[18%] text-center font-display text-[7.4vw] leading-[0.78] tracking-[0.02em] whitespace-nowrap text-sand 2xl:text-[7.4rem]">Racines &amp; Lumière</p>
</footer>
