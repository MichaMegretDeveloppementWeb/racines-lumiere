<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seo;

use App\Data\Institute\InstituteData;
use App\Http\Controllers\Controller;
use App\Services\Seo\SitemapService;
use Illuminate\Http\Response;

class ShowSitemapController extends Controller
{
    public function __invoke(SitemapService $sitemap, InstituteData $institute): Response
    {
        return response()->view(
            'web.sitemap.index',
            ['urls' => $sitemap->urls($institute)],
            200,
            ['Content-Type' => 'application/xml; charset=UTF-8'],
        );
    }
}
