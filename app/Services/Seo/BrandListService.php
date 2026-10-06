<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Data\Brand\BrandData;

class BrandListService
{
    public function __construct(
        private readonly SiteUrlService $siteUrl,
        private readonly StructuredDataService $structuredData,
    ) {}

    /**
     * The same visible brands as the page, without inventing products or offers.
     *
     * @param  list<BrandData>  $brands
     * @return array<string, mixed>
     */
    public function listNode(array $brands): array
    {
        $pageUrl = $this->siteUrl->routeUrl('brands');

        return [
            '@type' => 'ItemList',
            '@id' => $pageUrl.'#brands',
            'name' => 'Nos marques partenaires',
            'mainEntityOfPage' => $this->structuredData->pageReference($pageUrl),
            'numberOfItems' => count($brands),
            'itemListElement' => array_map(fn (BrandData $brand, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => array_filter([
                    '@type' => 'Brand',
                    '@id' => $pageUrl.'#'.$brand->slug,
                    'name' => $brand->name,
                    'description' => $brand->shortText,
                    'logo' => $brand->logoPath === null ? null : $this->siteUrl->urlFor('/'.$brand->logoPath),
                ], fn (mixed $value): bool => $value !== null),
            ], $brands, array_keys($brands)),
        ];
    }
}
