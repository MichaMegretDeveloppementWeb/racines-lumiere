<section class="bg-forest px-4 py-20 text-cream [--color-focus:var(--color-gold)] lg:py-28" aria-labelledby="reviews-title">
    <div class="mx-auto max-w-6xl">
        <h2 id="reviews-title" class="text-center text-2xl text-gold sm:text-3xl">Ce que vous en dites</h2>

        <ul class="mt-12 flex snap-x snap-mandatory gap-4 overflow-x-auto pb-4" tabindex="0" aria-label="Avis, à faire défiler">
            @foreach ($reviews as $review)
                <li class="flex w-[85%] shrink-0 snap-start flex-col gap-4 border border-cream/15 px-6 py-7 sm:w-80">
                    <p class="text-gold" aria-label="{{ $review->rating }} sur 5">{{ str_repeat('★', $review->rating) }}</p>
                    <blockquote class="flex-1 leading-relaxed">{{ $review->body }}</blockquote>
                    <p class="text-sm text-sand">
                        {{ $review->authorName }} · {{ $review->reviewedOn->isoFormat('Do MMMM YYYY') }}
                        @if ($review->treatmentLabel !== null)
                            <br>{{ $review->treatmentLabel }}
                        @endif
                    </p>
                </li>
            @endforeach
        </ul>

        <p class="mt-8 text-center">
            <a href="{{ $institute->booksyProfileUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center px-3 font-semibold text-gold underline underline-offset-4">Voir tous les avis sur Booksy<span class="sr-only"> (nouvel onglet)</span></a>
        </p>
    </div>
</section>
