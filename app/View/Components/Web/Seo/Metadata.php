<?php

declare(strict_types=1);

namespace App\View\Components\Web\Seo;

use App\Services\Seo\SiteUrlService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Metadata extends Component
{
    public function __construct(
        private readonly SiteUrlService $siteUrl,
        private readonly Request $request,
        private readonly Application $app,
        public readonly string $title,
        public readonly string $description,
    ) {}

    /**
     * The address of this page on the configured site, without the host the request came through or its query.
     */
    public function canonicalUrl(): string
    {
        return $this->siteUrl->urlFor($this->request->getPathInfo());
    }

    /**
     * Whether crawlers may index the page: in production only.
     */
    public function isIndexable(): bool
    {
        return $this->app->isProduction();
    }

    public function shareImageUrl(): string
    {
        return $this->siteUrl->urlFor('/images/og/default-1200w.jpg');
    }

    public function render(): View
    {
        return view('components.web.seo.metadata');
    }
}
