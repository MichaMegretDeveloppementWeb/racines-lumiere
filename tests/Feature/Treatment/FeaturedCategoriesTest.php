<?php

declare(strict_types=1);

use App\Data\Treatment\FeaturedCategoryData;
use App\Models\TreatmentCategory;
use App\Services\Treatment\TreatmentMenuService;

it('reads the visible featured categories only, in their display order', function (): void {
    TreatmentCategory::factory()->create(['slug' => 'second', 'is_featured' => true, 'position' => 20]);
    TreatmentCategory::factory()->create(['slug' => 'first', 'is_featured' => true, 'position' => 10]);
    TreatmentCategory::factory()->create(['slug' => 'not-featured', 'is_featured' => false, 'position' => 5]);
    TreatmentCategory::factory()->create(['slug' => 'hidden', 'is_featured' => true, 'is_visible' => false, 'position' => 1]);

    $categories = app(TreatmentMenuService::class)->featuredCategories();

    expect($categories)->each->toBeInstanceOf(FeaturedCategoryData::class)
        ->and(array_map(fn (FeaturedCategoryData $category): string => $category->slug, $categories))
        ->toBe(['first', 'second']);
});

it('reads the featured categories in a single query', function (): void {
    TreatmentCategory::factory()->count(3)->create(['is_featured' => true]);

    expect(queryCount(fn () => app(TreatmentMenuService::class)->featuredCategories()))->toBe(1);
});
