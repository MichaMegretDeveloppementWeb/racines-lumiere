<?php

declare(strict_types=1);

namespace App\Services\Brand;

use App\Data\Brand\BrandData;
use App\Models\Brand;
use App\Services\Typography\TypographyService;

class BrandCatalogService
{
    public function __construct(private readonly TypographyService $typography) {}

    /**
     * The editorial selection of seven partners, ordered in one query and small enough to render in full.
     *
     * @return list<BrandData>
     */
    public function visibleBrands(): array
    {
        return Brand::query()
            ->where('is_visible', true)
            ->orderBy('position')
            ->orderBy('id')
            ->get(['slug', 'name', 'tagline', 'short_text', 'long_text', 'role_text', 'logo_path', 'products_url'])
            ->map(fn (Brand $brand): BrandData => new BrandData(
                slug: $brand->slug,
                name: $this->typography->withFrenchSpacing($brand->name),
                tagline: $this->spaced($brand->tagline),
                shortText: $this->spaced($brand->short_text),
                paragraphs: array_map($this->typography->withFrenchSpacing(...), preg_split('/\R{2,}/', trim($brand->long_text))),
                roleText: $this->spaced($brand->role_text),
                logoPath: $brand->logo_path,
                productsUrl: $brand->products_url,
            ))
            ->values()
            ->all();
    }

    private function spaced(?string $text): ?string
    {
        return $text === null ? null : $this->typography->withFrenchSpacing($text);
    }
}
