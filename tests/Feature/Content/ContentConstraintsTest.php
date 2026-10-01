<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\Partner;
use App\Models\Review;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;

dataset('slugged content', [
    'treatment categories' => [fn (): Factory => TreatmentCategory::factory()],
    'treatments' => [fn (): Factory => Treatment::factory()],
    'brands' => [fn (): Factory => Brand::factory()],
    'partners' => [fn (): Factory => Partner::factory()],
    'reviews' => [fn (): Factory => Review::factory()],
]);

it('refuses a second row with the same slug', function (Factory $factory): void {
    $factory->create(['slug' => 'taken']);

    expect(fn () => $factory->create(['slug' => 'taken']))->toThrow(UniqueConstraintViolationException::class);
})->with('slugged content');

it('refuses two price lines at the same position of a treatment', function (): void {
    $treatment = Treatment::factory()->create();
    TreatmentVariant::factory()->create(['treatment_id' => $treatment->id, 'position' => 10]);

    expect(fn () => TreatmentVariant::factory()->create(['treatment_id' => $treatment->id, 'position' => 10]))
        ->toThrow(UniqueConstraintViolationException::class);
});

it('refuses a care time that is not shorter than the total duration', function (?int $total, int $care): void {
    expect(fn () => TreatmentVariant::factory()->create([
        'total_duration_minutes' => $total,
        'care_duration_minutes' => $care,
    ]))->toThrow(QueryException::class, 'treatment_variants_care_within_total');
})->with([
    'care equal to the total' => [60, 60],
    'care longer than the total' => [60, 75],
    'care without a total' => [null, 45],
]);

it('accepts a care time strictly shorter than the total, or none at all', function (?int $total, ?int $care): void {
    TreatmentVariant::factory()->create(['total_duration_minutes' => $total, 'care_duration_minutes' => $care]);

    expect(TreatmentVariant::query()->count())->toBe(1);
})->with([
    'one minute shorter' => [60, 59],
    'no care time' => [60, null],
    'no duration at all' => [null, null],
]);

it('refuses a rating outside one to five', function (int $rating): void {
    expect(fn () => Review::factory()->create(['rating' => $rating]))
        ->toThrow(QueryException::class, 'reviews_rating_between_1_and_5');
})->with([0, 6]);

it('accepts the ratings at both ends of the scale', function (int $rating): void {
    Review::factory()->create(['rating' => $rating]);

    expect(Review::query()->where('rating', $rating)->exists())->toBeTrue();
})->with([1, 5]);
