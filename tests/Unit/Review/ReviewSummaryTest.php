<?php

declare(strict_types=1);

use App\Data\Review\ReviewData;
use App\Services\Review\ReviewService;
use App\Services\Typography\TypographyService;
use Carbon\CarbonImmutable;

/**
 * Reviews carrying the given ratings, everything else alike.
 *
 * @param  list<int>  $ratings
 * @return list<ReviewData>
 */
function reviewsRated(array $ratings): array
{
    return array_map(fn (int $rating): ReviewData => new ReviewData(
        authorName: 'Client',
        rating: $rating,
        body: 'Un moment rare.',
        reviewedOn: CarbonImmutable::parse('2026-04-02'),
        treatmentLabel: null,
    ), $ratings);
}

it('sums up the reviews as their average rating and their number', function (array $ratings, string $averageLabel, int $count): void {
    $summary = (new ReviewService(new TypographyService))->summaryOf(reviewsRated($ratings));

    expect($summary->averageLabel())->toBe($averageLabel)
        ->and($summary->count)->toBe($count);
})->with([
    'all at five' => [[5, 5, 5, 5, 5, 5], '5', 6],
    'rounded to one decimal, French comma' => [[5, 5, 4], '4,7', 3],
    'a single review' => [[4], '4', 1],
]);

it('has nothing to sum up without reviews', function (): void {
    expect((new ReviewService(new TypographyService))->summaryOf([]))->toBeNull();
});
