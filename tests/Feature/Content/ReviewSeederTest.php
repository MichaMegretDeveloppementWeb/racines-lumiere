<?php

declare(strict_types=1);

use App\Models\Review;
use Database\Seeders\Content\ReviewSeeder;

/**
 * Synthetic review rows, as the seeder receives them.
 *
 * @return list<array<string, mixed>>
 */
function syntheticReviews(string $prefix, int $count): array
{
    return array_map(fn (int $index): array => [
        'slug' => "{$prefix}-review-{$index}",
        'author_name' => "Author {$index}",
        'rating' => 5,
        'body' => 'A review.',
        'reviewed_on' => '2026-04-02',
        'treatment_label' => null,
        'position' => $index * 10,
        'is_visible' => true,
    ], range(1, $count));
}

it('seeds the six reviews of annex E', function (): void {
    $this->seed(ReviewSeeder::class);

    $sampaio = Review::query()->where('slug', 'sampaio-2026-04-02')->firstOrFail();

    expect(Review::query()->orderBy('position')->pluck('slug')->all())->toBe([
        'le-curieux-belfond-2026-08-05', 'sampaio-2026-04-02', 'david-2025-12-01',
        'helene-2025-12-01', 'alain-2025-12-01', 'zoee-2025-12-01',
    ])
        ->and(Review::query()->where('rating', '<>', 5)->exists())->toBeFalse()
        ->and($sampaio->reviewed_on->toDateString())->toBe('2026-04-02')
        ->and($sampaio->treatment_label)->toBe('Rituel Aura Botanica');
});

it('replays the reviews without duplicates and restores altered values', function (): void {
    $this->seed(ReviewSeeder::class);
    Review::query()->where('slug', 'david-2025-12-01')->update(['author_name' => 'Altered']);

    $this->seed(ReviewSeeder::class);

    expect(Review::query()->count())->toBe(6)
        ->and(Review::query()->where('slug', 'david-2025-12-01')->value('author_name'))->toBe('David');
});

it('writes the reviews in one query, whatever their number', function (): void {
    $seeder = new ReviewSeeder;

    $forTen = queryCount(fn () => $seeder->writeReviews(syntheticReviews('small', 10)));
    $forHundred = queryCount(fn () => $seeder->writeReviews(syntheticReviews('large', 100)));

    expect($forTen)->toBe(1)->and($forHundred)->toBe($forTen);
});

it('writes the same review columns as an ordinary save', function (): void {
    [$row] = syntheticReviews('upserted', 1);
    (new ReviewSeeder)->writeReviews([$row]);

    $saved = (new Review)->forceFill(array_merge($row, ['slug' => 'saved-review']));
    $saved->save();

    expect(rowColumns('reviews', Review::query()->where('slug', 'upserted-review-1')->value('id')))
        ->toBe(rowColumns('reviews', $saved->id));
});
