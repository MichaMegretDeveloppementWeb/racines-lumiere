<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Data\Institute\InstituteData;
use App\Data\Treatment\TreatmentData;
use App\Data\Treatment\TreatmentMenuData;
use App\Data\Treatment\TreatmentVariantData;
use Illuminate\Contracts\Config\Repository;

/**
 * The schema.org nodes the pages publish: only what they display, or what is certain.
 */
class StructuredDataService
{
    private const string NAME = 'Racines & Lumière';

    private const string DESCRIPTION = 'Institut de beauté holistique à Sciez, en Chablais : rituels sur mesure pour le corps et le visage, massages et soins experts.';

    private const array SERVED_TOWNS = ['Sciez', 'Thonon-les-Bains', 'Évian-les-Bains', 'Douvaine'];

    public function __construct(private readonly Repository $config) {}

    /**
     * The institute, published on every page.
     *
     * @return array<string, mixed>
     */
    public function instituteNode(InstituteData $institute): array
    {
        return array_filter([
            '@type' => 'BeautySalon',
            '@id' => $this->instituteId(),
            'name' => self::NAME,
            'description' => self::DESCRIPTION,
            'url' => $this->absoluteUrl('/'),
            'logo' => $this->absoluteUrl('/images/brand/logo-gold-560w.webp'),
            'image' => $this->absoluteUrl('/images/home/hero-1440w.jpg'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $institute->street,
                'postalCode' => $institute->postalCode,
                'addressLocality' => $institute->city,
                'addressCountry' => 'FR',
            ],
            'telephone' => $institute->phone === null ? null : '+33 '.substr($institute->phone, 1),
            'priceRange' => '€€',
            'areaServed' => [
                ...array_map(fn (string $town): array => ['@type' => 'City', 'name' => $town], self::SERVED_TOWNS),
                ['@type' => 'Place', 'name' => 'Chablais'],
            ],
            'potentialAction' => ['@type' => 'ReserveAction', 'target' => $institute->bookingUrl],
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * The trail from the home page down to an inner page.
     *
     * @return array<string, mixed>
     */
    public function breadcrumbNode(string $pageName, string $routeName): array
    {
        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil', 'item' => $this->absoluteUrl('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $pageName, 'item' => $this->absoluteUrl(route($routeName, absolute: false))],
            ],
        ];
    }

    /**
     * One service per treatment of the menu, one offer per price line, all provided by the institute.
     *
     * @return list<array<string, mixed>>
     */
    public function menuNodes(TreatmentMenuData $menu): array
    {
        $nodes = [];

        foreach ($menu->categories() as $category) {
            foreach ($category->treatments as $treatment) {
                $nodes[] = $this->serviceNode($treatment, $category->name);
            }
        }

        return $nodes;
    }

    /**
     * @return array<string, mixed>
     */
    private function serviceNode(TreatmentData $treatment, string $categoryName): array
    {
        return array_filter([
            '@type' => 'Service',
            'name' => $treatment->name,
            'description' => $treatment->description,
            'category' => $categoryName,
            'provider' => ['@id' => $this->instituteId()],
            'offers' => array_map($this->offerNode(...), $treatment->variants),
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * @return array<string, mixed>
     */
    private function offerNode(TreatmentVariantData $variant): array
    {
        $summary = trim(implode(' ', [$variant->totalDuration?->label() ?? '', $variant->label ?? '']));

        return array_filter([
            '@type' => 'Offer',
            'description' => $summary === '' ? null : $summary,
            'price' => $variant->price->decimalAmount(),
            'priceCurrency' => 'EUR',
        ], fn (mixed $value): bool => $value !== null);
    }

    private function instituteId(): string
    {
        return $this->absoluteUrl('/#institute');
    }

    /**
     * An address on the configured site, never on the host the request came through.
     */
    private function absoluteUrl(string $path): string
    {
        return rtrim((string) $this->config->get('app.url'), '/').$path;
    }
}
