<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Services\Seo\SiteUrlService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;

class ShowRobotsController extends Controller
{
    /**
     * Serve the crawling policy: everything open in production, with the sitemap, everything closed elsewhere.
     */
    public function __invoke(Application $app, SiteUrlService $siteUrl): Response
    {
        $rules = $app->isProduction()
            ? ['User-agent: *', 'Allow: /', '', 'Sitemap: '.$siteUrl->routeUrl('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $rules)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
