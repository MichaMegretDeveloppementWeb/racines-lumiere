<?php

declare(strict_types=1);

use App\Data\Review\ReviewData;
use App\Models\Review;
use App\Services\Review\ReviewService;

it('reads the visible reviews only, in their display order', function (): void {
    Review::factory()->create(['author_name' => 'Second', 'position' => 20]);
    Review::factory()->create(['author_name' => 'First', 'position' => 10]);
    Review::factory()->create(['author_name' => 'Hidden', 'position' => 1, 'is_visible' => false]);

    $reviews = app(ReviewService::class)->visibleReviews();

    expect($reviews)->each->toBeInstanceOf(ReviewData::class)
        ->and(array_map(fn (ReviewData $review): string => $review->authorName, $reviews))
        ->toBe(['First', 'Second']);
});

it('sets the review text in French typography, words unchanged', function (): void {
    Review::factory()->create(['body' => 'Je recommande sans hésiter !', 'reviewed_on' => '2026-04-02']);

    [$review] = app(ReviewService::class)->visibleReviews();

    expect($review->body)->toBe("Je recommande sans hésiter\u{00A0}!")
        ->and($review->reviewedOn->toDateString())->toBe('2026-04-02');
});

it('reads the reviews in a single query', function (): void {
    Review::factory()->count(3)->create();

    expect(queryCount(fn () => app(ReviewService::class)->visibleReviews()))->toBe(1);
});
