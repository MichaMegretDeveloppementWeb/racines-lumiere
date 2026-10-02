<section aria-labelledby="reviews-title" aria-roledescription="carrousel" class="alt-wrap alt-reviews alt-section" x-data="reviewCarousel({ authors: {{ Js::from(array_column($reviews, 'authorName')) }} })">
    <div class="alt-reviews-intro">
        <h2 id="reviews-title" class="alt-title">Ce que vous en dites</h2>
        <p class="alt-review-summary"><span>{{ $reviewSummary->averageLabel() }}/5</span><span>{{ $reviewSummary->count }} avis Booksy</span></p>
        <a href="{{ $institute->booksyProfileUrl }}" target="_blank" rel="noopener" class="alt-text-link">Voir tous les avis sur Booksy<span class="sr-only"> (nouvel onglet)</span></a>
    </div>
    <p id="reviews-help" class="sr-only">Utilisez les flèches gauche et droite pour parcourir les avis. Sur mobile, faites glisser les cartes.</p>
    <div class="alt-review-stage">
        <ul id="review-track" class="alt-review-list" tabindex="0" aria-label="Avis des clientes" aria-describedby="reviews-help" x-ref="track" :data-enhanced="true" :data-dragging="isDragging" @scroll.passive="onScroll" @scrollend="settle" @pointerdown="beginDrag" @pointermove="moveDrag" @pointerup="endDrag" @pointercancel="endDrag" @keydown.left.prevent="previous" @keydown.right.prevent="next" @keydown.home.prevent="goTo(0)" @keydown.end.prevent="goTo(count - 1)">
            @foreach (count($reviews) > 1 ? [-1, 0, 1] : [0] as $copy)
                @foreach ($reviews as $review)
                    <li class="alt-review" @if ($copy !== 0) data-review-clone aria-hidden="true" inert @endif>
                        <p class="alt-review-stars" role="img" aria-label="{{ $review->rating }} sur 5">
                            @for ($star = 0; $star < $review->rating; $star++)
                                <x-web.media.icon name="star" class="size-3.5" />
                            @endfor
                        </p>
                        <blockquote>{{ $review->body }}</blockquote>
                        <p class="alt-review-author">{{ $review->authorName }}</p>
                        <p class="alt-review-date">
                            @if ($review->treatmentLabel !== null)
                                {{ $review->treatmentLabel }} ·
                            @endif
                            <time datetime="{{ $review->reviewedOn->toDateString() }}">{{ $review->reviewedOn->isoFormat('Do MMMM YYYY') }}</time>
                        </p>
                    </li>
                @endforeach
            @endforeach
        </ul>
        @if (count($reviews) > 1)
            <div class="alt-carousel-controls" x-cloak>
                <button type="button" class="alt-carousel-button alt-carousel-previous" aria-label="Avis précédents" aria-controls="review-track" @click="previous">
                    <svg viewBox="0 0 52 20" fill="none" aria-hidden="true"><path d="M2 10H50M40 2L50 10L40 18" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </button>
                <button type="button" class="alt-carousel-button" aria-label="Avis suivants" aria-controls="review-track" @click="next">
                    <svg viewBox="0 0 52 20" fill="none" aria-hidden="true"><path d="M2 10H50M40 2L50 10L40 18" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </button>
            </div>
            <p class="sr-only" aria-live="polite" aria-atomic="true" x-cloak>Avis de <span x-text="activeAuthor"></span></p>
        @endif
    </div>
</section>
