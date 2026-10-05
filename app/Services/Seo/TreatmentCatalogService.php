<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Data\Treatment\TreatmentCategoryData;
use App\Data\Treatment\TreatmentData;
use App\Data\Treatment\TreatmentGroupData;
use App\Data\Treatment\TreatmentMenuData;
use App\Data\Treatment\TreatmentVariantData;

/**
 * The treatment menu as a schema.org offer catalogue: its categories, their treatments and their price lines.
 */
class TreatmentCatalogService
{
    public function __construct(
        private readonly StructuredDataService $structuredData,
        private readonly SiteUrlService $siteUrl,
    ) {}

    /**
     * The catalogue of the menu page: every category that lists a treatment, in the order of the menu.
     *
     * @return array<string, mixed>
     */
    public function catalogNode(TreatmentMenuData $menu): array
    {
        $pageUrl = $this->siteUrl->routeUrl('treatments');
        $provider = $this->structuredData->instituteReference();
        $listedCategories = array_filter($menu->categories(), fn (TreatmentCategoryData $category): bool => $category->treatments !== []);

        return [
            '@type' => 'OfferCatalog',
            '@id' => $pageUrl.'#catalog',
            'name' => 'Carte des soins',
            'mainEntityOfPage' => $this->structuredData->pageReference($pageUrl),
            'itemListElement' => array_values(array_map(
                fn (TreatmentCategoryData $category): array => $this->categoryNode($category, $provider),
                $listedCategories,
            )),
        ];
    }

    /**
     * A category of the menu, its treatments listed in their groups when the menu folds them so.
     *
     * @param  array{'@id': string}  $provider
     * @return array<string, mixed>
     */
    private function categoryNode(TreatmentCategoryData $category, array $provider): array
    {
        return array_filter([
            '@type' => 'OfferCatalog',
            'name' => $category->name,
            'description' => $category->descriptionParagraphs === [] ? null : implode(' ', $category->descriptionParagraphs),
            'itemListElement' => $category->treatmentGroups === []
                ? $this->serviceNodes($category->treatments, $provider)
                : array_map(fn (TreatmentGroupData $group): array => array_filter([
                    '@type' => 'OfferCatalog',
                    'name' => $group->label,
                    'itemListElement' => $this->serviceNodes($group->treatments, $provider),
                ], fn (mixed $value): bool => $value !== null), $category->treatmentGroups),
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * @param  list<TreatmentData>  $treatments
     * @param  array{'@id': string}  $provider
     * @return list<array<string, mixed>>
     */
    private function serviceNodes(array $treatments, array $provider): array
    {
        return array_map(fn (TreatmentData $treatment): array => $this->serviceNode($treatment, $provider), $treatments);
    }

    /**
     * @param  array{'@id': string}  $provider
     * @return array<string, mixed>
     */
    private function serviceNode(TreatmentData $treatment, array $provider): array
    {
        return array_filter([
            '@type' => 'Service',
            'name' => $treatment->name,
            'description' => $treatment->description,
            'provider' => $provider,
            'offers' => array_map($this->offerNode(...), $treatment->variants),
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * A price line, described as the menu writes it: its total duration, its label, then the time of care within it.
     *
     * @return array<string, mixed>
     */
    private function offerNode(TreatmentVariantData $variant): array
    {
        $summary = implode(' ', array_filter([
            $variant->totalDuration?->label(),
            $variant->label,
            $variant->careDuration === null ? null : 'dont '.$variant->careDuration->label().' de soin',
        ]));

        return array_filter([
            '@type' => 'Offer',
            'description' => $summary === '' ? null : $summary,
            'price' => $variant->price->decimalAmount(),
            'priceCurrency' => 'EUR',
        ], fn (mixed $value): bool => $value !== null);
    }
}
