<header class="sticky top-0 z-40 h-0">
    <div class="px-4 pt-4 lg:px-[1.625rem] lg:pt-[1.625rem]">
        <div class="relative mx-auto flex max-w-[90rem] items-center rounded-full border border-cream/70 bg-cream/75 p-1.5 shadow-[0_14px_36px_-22px_rgb(48_52_37/0.5)] backdrop-blur-lg">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center rounded-full p-0.5 lg:absolute lg:top-1/2 lg:left-1/2 lg:-translate-x-1/2 lg:-translate-y-1/2">
                <x-web.media.picture
                    name="brand/monogram-terracotta"
                    :widths="[56, 112]"
                    sizes="(min-width: 64rem) 52px, 44px"
                    alt="Racines & Lumière, accueil"
                    :width="56"
                    :height="56"
                    fallback="webp"
                    is-priority
                    class="size-11 lg:size-[3.25rem]"
                />
            </a>

            <nav
                aria-label="Menu principal"
                class="ml-auto flex items-center lg:ml-0 lg:flex-1 lg:justify-between lg:pl-4"
                x-data="siteMenu"
                @keydown.escape.window="close()"
                @keydown.tab="keepFocusInside($event)"
                @click.outside="close()"
            >
                @foreach ([$primaryLinks(), $secondaryLinks()] as $group)
                    <ul class="hidden lg:flex">
                        @foreach ($group as $link)
                            <li>
                                <a
                                    href="{{ $link['url'] }}"
                                    @if ($link['isCurrent']) aria-current="page" @endif
                                    class="flex min-h-11 items-center rounded-full px-4 text-[0.9375rem] text-forest transition-colors hover:text-terracotta aria-[current=page]:text-terracotta"
                                >{{ $link['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach

                <button
                    type="button"
                    x-ref="toggle"
                    class="flex min-h-11 items-center rounded-full px-4 text-[0.9375rem] font-semibold lg:hidden"
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
                    class="absolute inset-x-0 top-full mt-2 hidden max-h-[calc(100dvh-6.5rem)] flex-col gap-1 overflow-y-auto rounded-[1.75rem] border border-cream/70 bg-cream p-3 shadow-[0_24px_48px_-24px_rgb(48_52_37/0.5)] max-lg:data-open:flex lg:hidden"
                >
                    @foreach ($links() as $link)
                        <li>
                            <a
                                href="{{ $link['url'] }}"
                                @if ($link['isCurrent']) aria-current="page" @endif
                                class="flex min-h-12 items-center rounded-2xl px-4 font-display text-xl text-forest hover:bg-paper aria-[current=page]:text-terracotta"
                            >{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <x-web.booking.button tone="forest" size="small" :has-arrow="false" class="ml-1 shrink-0 lg:ml-4" />
        </div>
    </div>
</header>
