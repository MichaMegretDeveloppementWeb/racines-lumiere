<header class="sticky top-0 z-40 border-b border-sand/60 bg-cream">
    <div class="mx-auto flex h-16 max-w-6xl items-center gap-3 px-4 lg:h-20 lg:gap-8 lg:px-8">
        <a href="{{ route('home') }}" class="-m-1 flex shrink-0 items-center p-1">
            <x-web.media.picture
                name="brand/monogram-terracotta"
                :widths="[56, 112]"
                sizes="(min-width: 64rem) 56px, 48px"
                alt="Racines & Lumière, accueil"
                :width="56"
                :height="56"
                fallback="webp"
                is-priority
                class="size-12 lg:size-14"
            />
        </a>

        <nav
            aria-label="Menu principal"
            class="ml-auto lg:ml-0 lg:flex-1"
            x-data="siteMenu"
            @keydown.escape.window="close()"
            @keydown.tab="keepFocusInside($event)"
            @click.outside="close()"
        >
            <button
                type="button"
                x-ref="toggle"
                class="flex min-h-11 items-center gap-2 px-3 text-sm font-semibold tracking-[0.12em] uppercase lg:hidden"
                aria-controls="site-menu"
                :aria-expanded="isOpen"
                @click="toggle()"
            >
                Menu
            </button>

            <ul
                id="site-menu"
                x-ref="panel"
                :data-open="isOpen"
                class="max-lg:hidden max-lg:data-open:flex max-lg:absolute max-lg:inset-x-0 max-lg:top-full max-lg:h-[calc(100dvh-4rem)] max-lg:flex-col max-lg:gap-1 max-lg:overflow-y-auto max-lg:border-t max-lg:border-sand/60 max-lg:bg-cream max-lg:px-4 max-lg:py-6 lg:flex lg:items-center lg:justify-center lg:gap-1"
            >
                @foreach ($links() as $link)
                    <li>
                        <a
                            href="{{ $link['url'] }}"
                            @if ($link['isCurrent']) aria-current="page" @endif
                            class="flex min-h-11 items-center px-3 py-2 text-lg text-forest hover:text-terracotta aria-[current=page]:text-terracotta lg:text-sm"
                        >{{ $link['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <x-web.booking.button class="shrink-0" />
    </div>
</header>
