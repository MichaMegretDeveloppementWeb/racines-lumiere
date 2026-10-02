<section aria-labelledby="treatments-preview-title" class="py-24 lg:pt-32 lg:pb-36">
    <div class="mx-auto max-w-[77.5rem] px-6 lg:px-16">
        <div class="flex flex-col items-start gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <x-web.typography.eyebrow>La carte</x-web.typography.eyebrow>
                <h2 id="treatments-preview-title" class="mt-5 text-[2.5rem] leading-none lg:text-[3.5rem]">Nos <span class="text-terracotta">soins</span></h2>
            </div>
            <x-web.navigation.arrow-link :href="route('treatments')">Découvrir la carte des soins</x-web.navigation.arrow-link>
        </div>

        <ul class="-mr-6 mt-10 flex snap-x snap-mandatory gap-3.5 overflow-x-auto pr-6 pb-3 [scrollbar-color:var(--color-sand)_transparent] [scrollbar-width:thin] lg:mt-16 lg:mr-0 lg:grid lg:grid-cols-[repeat(auto-fit,minmax(0,1fr))] lg:gap-5 lg:overflow-visible lg:pr-0 lg:pb-0">
            @foreach ($featuredCategories as $category)
                <li class="w-[64%] shrink-0 snap-start sm:w-[40%] lg:w-auto">
                    <a href="{{ route('treatments') }}#{{ $category->slug }}" class="group block">
                        <span class="relative block aspect-[3/4.4] overflow-hidden rounded-t-full rounded-b-md bg-linear-to-b from-honey to-sand">
                            <x-web.media.picture :name="'treatments/'.$category->slug" :widths="[320, 640]" sizes="(min-width: 64rem) 210px, 64vw" alt="" :width="320" :height="469" class="absolute inset-0 size-full object-cover transition-transform duration-700 group-hover:scale-[1.03]" />
                            <span class="absolute bottom-4 left-1/2 -translate-x-1/2 font-display text-[0.6875rem] tracking-[0.3em] text-cream">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </span>
                        <span class="mt-5 block font-display text-base leading-snug tracking-[0.04em] text-forest transition-colors group-hover:text-terracotta lg:text-[1.03rem]">{{ $category->name }}</span>
                        @if ($category->subtitle !== null)
                            <span class="mt-1.5 block text-sm text-olive">{{ $category->subtitle }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
