<?php

declare(strict_types=1);

namespace App\View\Components\Web\Seo;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Canonical extends Component
{
    public function __construct(
        private readonly Repository $config,
        private readonly Request $request,
    ) {}

    /**
     * The address of this page on the configured site, without the host the request came through or its query.
     */
    public function url(): string
    {
        return rtrim((string) $this->config->get('app.url'), '/').$this->request->getPathInfo();
    }

    public function render(): View
    {
        return view('components.web.seo.canonical');
    }
}
