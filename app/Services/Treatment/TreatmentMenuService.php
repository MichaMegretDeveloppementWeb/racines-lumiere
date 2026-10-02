<?php

declare(strict_types=1);

namespace App\Services\Treatment;

use App\Data\Treatment\FeaturedCategoryData;
use App\Models\TreatmentCategory;
use App\Services\Typography\TypographyService;

class TreatmentMenuService
{
    public function __construct(private readonly TypographyService $typography) {}

    /**
     * The visible categories marked for the home page preview, in their display order.
     *
     * @return list<FeaturedCategoryData>
     */
    public function featuredCategories(): array
    {
        return TreatmentCategory::query()
            ->where('is_visible', true)
            ->where('is_featured', true)
            ->orderBy('position')
            ->get(['slug', 'name', 'subtitle'])
            ->map(fn (TreatmentCategory $category): FeaturedCategoryData => new FeaturedCategoryData(
                slug: $category->slug,
                name: $this->typography->withFrenchSpacing($category->name),
                subtitle: $category->subtitle === null ? null : $this->typography->withFrenchSpacing($category->subtitle),
            ))
            ->values()
            ->all();
    }
}
