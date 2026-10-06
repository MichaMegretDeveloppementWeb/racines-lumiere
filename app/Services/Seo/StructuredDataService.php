<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Data\Institute\InstituteData;
use App\Data\Seo\WebPageData;

/**
 * The schema.org graph of a page: only what the pages display, or what is certain.
 */
class StructuredDataService
{
    private const string NAME = 'Racines & Lumière';

    private const string ALTERNATE_NAME = 'Racines et Lumière';

    private const string DESCRIPTION = 'Institut de beauté holistique à Sciez, en Chablais : rituels sur mesure pour le corps et le visage, massages et soins experts.';

    private const string LANGUAGE = 'fr-FR';

    private const array SERVED_TOWNS = ['Sciez', 'Thonon-les-Bains', 'Évian-les-Bains', 'Douvaine'];

    private const array FOUNDERS = ['Aurore', 'Lorie'];

    public function __construct(private readonly SiteUrlService $siteUrl) {}

    /**
     * The single graph of a page: the site, the institute and the page itself, then the nodes the page adds.
     *
     * @param  list<array<string, mixed>>  $pageNodes
     * @return array{'@context': string, '@graph': list<array<string, mixed>>}
     */
    public function graph(InstituteData $institute, WebPageData $page, array $pageNodes): array
    {
        $url = $this->siteUrl->urlFor($page->path);
        $trail = $page->breadcrumbName === null ? [] : [$this->breadcrumbNode($url, $page->breadcrumbName)];

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                $this->websiteNode(),
                $this->instituteNode($institute, $page->path === '/' ? $this->pageReference($url) : null),
                $this->webPageNode($url, $page, $trail === [] ? null : ['@id' => $trail[0]['@id']]),
                ...$trail,
                ...$pageNodes,
            ],
        ];
    }

    /**
     * A reference to the institute, for the nodes it provides.
     *
     * @return array{'@id': string}
     */
    public function instituteReference(): array
    {
        return ['@id' => $this->siteUrl->urlFor('/#institute')];
    }

    /**
     * A reference to the page at the given address, for the node it is mainly about.
     *
     * @return array{'@id': string}
     */
    public function pageReference(string $pageUrl): array
    {
        return ['@id' => $pageUrl.'#webpage'];
    }

    /**
     * @return array<string, mixed>
     */
    private function websiteNode(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => $this->siteUrl->urlFor('/#website'),
            'url' => $this->siteUrl->urlFor('/'),
            'name' => self::NAME,
            'alternateName' => self::ALTERNATE_NAME,
            'inLanguage' => self::LANGUAGE,
            'publisher' => $this->instituteReference(),
        ];
    }

    /**
     * The institute, the main subject of the home page.
     *
     * @param  array{'@id': string}|null  $mainPage
     * @return array<string, mixed>
     */
    private function instituteNode(InstituteData $institute, ?array $mainPage): array
    {
        return array_filter([
            '@type' => 'BeautySalon',
            ...$this->instituteReference(),
            'name' => self::NAME,
            'description' => self::DESCRIPTION,
            'url' => $this->siteUrl->urlFor('/'),
            'mainEntityOfPage' => $mainPage,
            'logo' => $this->siteUrl->urlFor('/images/brand/logo-gold-560w.webp'),
            'image' => $this->siteUrl->urlFor('/images/home/hero-1440w.jpg'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $institute->street,
                'postalCode' => $institute->postalCode,
                'addressLocality' => $institute->city,
                'addressCountry' => 'FR',
            ],
            'email' => $institute->email,
            'telephone' => $institute->phone === null ? null : '+33 '.substr($institute->phone, 1),
            'contactPoint' => $this->contactPointNode($institute),
            'geo' => $this->coordinatesNode($institute),
            'founder' => array_map(fn (string $name): array => ['@type' => 'Person', 'name' => $name, 'jobTitle' => 'Co-fondatrice'], self::FOUNDERS),
            'priceRange' => '€€',
            'areaServed' => [
                ...array_map(fn (string $town): array => ['@type' => 'City', 'name' => $town], self::SERVED_TOWNS),
                ['@type' => 'Place', 'name' => 'Chablais'],
            ],
            'potentialAction' => ['@type' => 'ReserveAction', 'target' => $institute->bookingUrl],
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * The public contact details, shared with the page and never the private form recipient.
     *
     * @return array<string, mixed>
     */
    private function contactPointNode(InstituteData $institute): array
    {
        return array_filter([
            '@type' => 'ContactPoint',
            '@id' => $this->siteUrl->urlFor('/#contact-point'),
            'url' => $this->siteUrl->urlFor('/contact'),
            'contactType' => 'Renseignements sur les soins',
            'email' => $institute->email,
            'telephone' => $institute->phone === null ? null : '+33 '.substr($institute->phone, 1),
            'availableLanguage' => 'fr',
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * The verified address coordinates used by the contact map, only when both are known.
     *
     * @return array{'@type': string, latitude: float, longitude: float}|null
     */
    private function coordinatesNode(InstituteData $institute): ?array
    {
        if ($institute->latitude === null || $institute->longitude === null) {
            return null;
        }

        return [
            '@type' => 'GeoCoordinates',
            'latitude' => $institute->latitude,
            'longitude' => $institute->longitude,
        ];
    }

    /**
     * @param  array{'@id': string}|null  $trail
     * @return array<string, mixed>
     */
    private function webPageNode(string $url, WebPageData $page, ?array $trail): array
    {
        return array_filter([
            '@type' => $page->type,
            ...$this->pageReference($url),
            'url' => $url,
            'name' => $page->title,
            'description' => $page->description,
            'about' => $page->type === 'AboutPage' ? $this->instituteReference() : null,
            'mainEntity' => $page->type === 'ContactPage' ? $this->instituteReference() : null,
            'isPartOf' => ['@id' => $this->siteUrl->urlFor('/#website')],
            'inLanguage' => self::LANGUAGE,
            'breadcrumb' => $trail,
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * The trail from the home page down to an inner page.
     *
     * @return array{'@type': string, '@id': string, itemListElement: list<array<string, mixed>>}
     */
    private function breadcrumbNode(string $url, string $pageName): array
    {
        return [
            '@type' => 'BreadcrumbList',
            '@id' => $url.'#breadcrumb',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil', 'item' => $this->siteUrl->urlFor('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $pageName, 'item' => $url],
            ],
        ];
    }
}
