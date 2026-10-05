<?php

declare(strict_types=1);

namespace App\View\Components\Web\Seo;

use App\Data\Institute\InstituteData;
use App\Services\Seo\StructuredDataService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StructuredData extends Component
{
    /**
     * @param  list<array<string, mixed>>  $nodes  the nodes of the page, published beside the institute
     */
    public function __construct(
        private readonly StructuredDataService $structuredData,
        private readonly InstituteData $institute,
        private readonly array $nodes = [],
    ) {}

    /**
     * The graph of the page as JSON, safe to place inside a script element.
     */
    public function json(): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [$this->structuredData->instituteNode($this->institute), ...$this->nodes],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_THROW_ON_ERROR);
    }

    public function render(): View
    {
        return view('components.web.seo.structured-data');
    }
}
