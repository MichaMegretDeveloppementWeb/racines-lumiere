<?php

declare(strict_types=1);

namespace App\View\Components\Web\Seo;

use App\Data\Institute\InstituteData;
use App\Data\Seo\WebPageData;
use App\Services\Seo\StructuredDataService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class StructuredData extends Component
{
    /**
     * @param  list<array<string, mixed>>  $nodes  the nodes the page adds to the graph
     */
    public function __construct(
        private readonly StructuredDataService $structuredData,
        private readonly InstituteData $institute,
        private readonly Request $request,
        private readonly string $title,
        private readonly string $description,
        private readonly ?string $breadcrumb = null,
        private readonly array $nodes = [],
    ) {}

    /**
     * The graph of the page as indented JSON, safe to place inside a script element.
     */
    public function json(): string
    {
        $page = new WebPageData($this->request->getPathInfo(), $this->title, $this->description, $this->breadcrumb);

        return json_encode(
            $this->structuredData->graph($this->institute, $page, $this->nodes),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_THROW_ON_ERROR,
        );
    }

    public function render(): View
    {
        return view('components.web.seo.structured-data');
    }
}
