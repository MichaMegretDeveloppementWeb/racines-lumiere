<?php

declare(strict_types=1);

namespace App\Services\Review;

use App\Data\Review\ReviewData;
use App\Models\Review;
use App\Services\Typography\TypographyService;

class ReviewService
{
    public function __construct(private readonly TypographyService $typography) {}

    /**
     * The visible reviews, in their display order, their words untouched.
     *
     * @return list<ReviewData>
     */
    public function visibleReviews(): array
    {
        return Review::query()
            ->where('is_visible', true)
            ->orderBy('position')
            ->get(['author_name', 'rating', 'body', 'reviewed_on', 'treatment_label'])
            ->map(fn (Review $review): ReviewData => new ReviewData(
                authorName: $review->author_name,
                rating: $review->rating,
                body: $this->typography->withFrenchSpacing($review->body),
                reviewedOn: $review->reviewed_on,
                treatmentLabel: $review->treatment_label,
            ))
            ->values()
            ->all();
    }
}
