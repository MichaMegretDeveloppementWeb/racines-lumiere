<section aria-labelledby="reviews-title" class="overflow-hidden py-24 lg:py-40">
    <div class="mx-auto grid max-w-[77.5rem] gap-10 px-6 lg:grid-cols-[21.25rem_1fr] lg:gap-[4.375rem] lg:px-16">
        <div>
            <x-web.typography.eyebrow>Avis clientes</x-web.typography.eyebrow>
            <h2 id="reviews-title" class="mt-6 text-[2rem] leading-[1.12] lg:text-[2.75rem]">Ce que vous <span class="text-terracotta">en dites</span></h2>

            @if ($reviewSummary !== null)
                <p class="mt-8 flex items-center gap-4">
                    <span class="font-display text-[3.25rem] leading-none">{{ $reviewSummary->averageLabel() }}/5</span>
                    <span>
                        <span class="flex gap-1 text-gold">
                            @for ($star = 0; $star < 5; $star++)
                                <x-web.media.icon name="star" class="size-3.5" />
                            @endfor
                        </span>
                        <span class="mt-1 block text-[0.8125rem] text-olive">{{ $reviewSummary->count }} avis Booksy</span>
                    </span>
                </p>
            @endif

            <x-web.navigation.arrow-link :href="$institute->booksyProfileUrl" is-external class="mt-8">Voir tous les avis sur Booksy</x-web.navigation.arrow-link>
        </div>

        <ul class="-mr-6 flex snap-x snap-mandatory gap-6 overflow-x-auto pr-6 pb-4 [scrollbar-color:var(--color-sand)_transparent] [scrollbar-width:thin] lg:mr-[calc(-4rem-max(0rem,(100vw-77.5rem)/2))]" tabindex="0" aria-label="Avis, à faire défiler">
            @foreach ($reviews as $review)
                <li class="flex w-[18.75rem] shrink-0 snap-start flex-col rounded-[1.75rem] bg-paper px-7 pt-9 pb-8 lg:w-[30rem] lg:px-[2.875rem] lg:pt-12 lg:pb-10">
                    <p class="flex gap-1 text-gold" role="img" aria-label="{{ $review->rating }} sur 5">
                        @for ($star = 0; $star < $review->rating; $star++)
                            <x-web.media.icon name="star" class="size-4" />
                        @endfor
                    </p>
                    <blockquote class="mt-6 flex-1 text-[1.03rem] leading-[1.75] lg:text-[1.15625rem]">{{ $review->body }}</blockquote>
                    <p class="mt-7 flex flex-wrap items-center gap-x-3.5 gap-y-1 border-t border-forest/10 pt-6 text-[0.8125rem] text-olive">
                        <span class="font-display tracking-[0.22em] text-terracotta uppercase">{{ $review->authorName }}</span>
                        <span aria-hidden="true" class="size-[3px] rounded-full bg-sand"></span>
                        <span>
                            @if ($review->treatmentLabel !== null)
                                {{ $review->treatmentLabel }} ·
                            @endif
                            {{ $review->reviewedOn->isoFormat('Do MMMM YYYY') }}
                        </span>
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
</section>
