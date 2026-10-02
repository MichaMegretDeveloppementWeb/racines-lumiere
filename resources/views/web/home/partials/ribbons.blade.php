@php($ribbonWords = [
    ['Rituels sur mesure', 'Lumière douce', 'Luxe discret'],
    [$institute->isOpen ? 'Sur rendez-vous, du lundi au samedi' : 'Ouverture le mardi 3 novembre', 'Maison du Mieux-Être', "{$institute->city}, en Chablais"],
])

<div aria-hidden="true" class="relative h-32 overflow-hidden lg:h-[10.625rem]">
    @foreach ($ribbonWords as $words)
        <p @class([
            'absolute -left-[5%] flex w-[110%] items-center py-3.5 font-display text-xs tracking-[0.24em] whitespace-nowrap lg:py-4 lg:text-base',
            'top-[3.75rem] rotate-[1.8deg] bg-sand text-forest' => $loop->first,
            'top-[3.25rem] z-10 -rotate-[2.2deg] bg-terracotta text-cream' => $loop->last,
        ])>
            @for ($round = 0; $round < 3; $round++)
                @foreach ($words as $word)
                    <span>{{ $word }}</span>
                    <x-web.media.icon name="sun" class="mx-7 size-3 shrink-0 text-gold lg:mx-9" />
                @endforeach
            @endfor
        </p>
    @endforeach
</div>
