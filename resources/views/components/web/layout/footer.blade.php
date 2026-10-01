<footer class="bg-forest text-cream [--color-focus:var(--color-gold)]">
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 lg:grid-cols-[auto_1fr_1fr] lg:gap-16 lg:px-8">
        <x-web.media.picture
            name="brand/monogram-gold"
            :widths="[96, 192]"
            sizes="96px"
            alt=""
            :width="96"
            :height="96"
            fallback="webp"
            class="size-24"
        />

        <div class="flex flex-col gap-3 text-sand">
            <address class="not-italic">
                {{ $institute->street }}<br>
                {{ $institute->postalCode }} {{ $institute->city }}
            </address>
            <x-web.institute.opening-hours />
            <ul class="flex flex-col">
                <li><a href="mailto:{{ $institute->email }}" class="inline-flex min-h-11 items-center hover:text-gold">{{ $institute->email }}</a></li>
                @if ($institute->phone !== null)
                    <li><a href="tel:{{ str_replace(' ', '', $institute->phone) }}" class="inline-flex min-h-11 items-center hover:text-gold">{{ $institute->phone }}</a></li>
                @endif
                <li><a href="{{ $institute->instagramUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center hover:text-gold">Instagram<span class="sr-only"> (nouvel onglet)</span></a></li>
            </ul>
        </div>

        <ul class="flex flex-col">
            <li><a href="{{ $institute->bookingUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center hover:text-gold">Sur rendez-vous uniquement · Réserver en ligne<span class="sr-only"> (nouvel onglet)</span></a></li>
            <li><a href="{{ $institute->giftCardsUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center hover:text-gold">Offrir une carte cadeau<span class="sr-only"> (nouvel onglet)</span></a></li>
            @if ($institute->hasTrustedCircle)
                <li><a href="{{ route('trusted-circle') }}" class="inline-flex min-h-11 items-center hover:text-gold">Cercle de confiance</a></li>
            @endif
            <li><a href="{{ route('legal.notice') }}" class="inline-flex min-h-11 items-center hover:text-gold">Mentions légales</a></li>
            <li><a href="{{ route('legal.privacy') }}" class="inline-flex min-h-11 items-center hover:text-gold">Politique de confidentialité</a></li>
        </ul>
    </div>

    <p class="border-t border-cream/15 px-4 py-6 text-center text-sm text-sand">© 2026 Racines &amp; Lumière</p>
</footer>
