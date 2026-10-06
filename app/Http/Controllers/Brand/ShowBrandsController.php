<?php

declare(strict_types=1);

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Services\Brand\BrandCatalogService;
use App\Services\Seo\BrandListService;
use Illuminate\Contracts\View\View;

class ShowBrandsController extends Controller
{
    public function __invoke(BrandCatalogService $catalog, BrandListService $structuredData): View
    {
        $brands = $catalog->visibleBrands();

        return view('web.brands.index', [
            'brands' => $brands,
            'pageStructuredData' => [$structuredData->listNode($brands)],
        ]);
    }
}
