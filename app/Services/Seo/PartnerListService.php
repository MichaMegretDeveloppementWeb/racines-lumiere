<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Data\Partner\PartnerData;

class PartnerListService
{
    public function __construct(
        private readonly SiteUrlService $siteUrl,
        private readonly StructuredDataService $structuredData,
    ) {}

    /**
     * Describe the published recommendations without implying employment or medical qualifications.
     *
     * @param  list<PartnerData>  $partners
     * @return array<string, mixed>
     */
    public function listNode(array $partners): array
    {
        $pageUrl = $this->siteUrl->routeUrl('trusted-circle');

        return [
            '@type' => 'ItemList',
            '@id' => $pageUrl.'#practitioners',
            'name' => 'Cercle de confiance',
            'mainEntityOfPage' => $this->structuredData->pageReference($pageUrl),
            'numberOfItems' => count($partners),
            'itemListElement' => array_map(fn (PartnerData $partner, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => array_filter([
                    '@type' => 'Person',
                    '@id' => $pageUrl.'#'.$partner->slug,
                    'name' => $partner->name,
                    'jobTitle' => $partner->specialty,
                    'url' => $partner->websiteUrl,
                ], fn (mixed $value): bool => $value !== null),
            ], $partners, array_keys($partners)),
        ];
    }
}
