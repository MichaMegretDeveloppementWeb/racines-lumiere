<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Data\Institute\InstituteData;

class SitemapService
{
    private const array ROUTES = ['home', 'treatments', 'brands', 'story', 'trusted-circle', 'contact', 'legal.notice', 'legal.privacy'];

    public function __construct(private readonly SiteUrlService $siteUrl) {}

    /**
     * The address of every public page, the trusted circle only once it exists.
     *
     * @return list<string>
     */
    public function urls(InstituteData $institute): array
    {
        $routes = $institute->hasTrustedCircle ? self::ROUTES : array_diff(self::ROUTES, ['trusted-circle']);

        return array_values(array_map($this->siteUrl->routeUrl(...), $routes));
    }
}
