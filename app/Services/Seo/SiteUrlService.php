<?php

declare(strict_types=1);

namespace App\Services\Seo;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * Addresses on the configured site, never on the host the request came through.
 */
class SiteUrlService
{
    public function __construct(
        private readonly Repository $config,
        private readonly UrlGenerator $urls,
    ) {}

    /**
     * The address of a path of the site.
     */
    public function urlFor(string $path): string
    {
        return rtrim((string) $this->config->get('app.url'), '/').$path;
    }

    /**
     * The address of a named route of the site.
     */
    public function routeUrl(string $routeName): string
    {
        return $this->urlFor($this->urls->route($routeName, absolute: false));
    }
}
