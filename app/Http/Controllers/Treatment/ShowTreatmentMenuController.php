<?php

declare(strict_types=1);

namespace App\Http\Controllers\Treatment;

use App\Http\Controllers\Controller;
use App\Services\Seo\TreatmentCatalogService;
use App\Services\Treatment\TreatmentMenuService;
use Illuminate\Contracts\View\View;

class ShowTreatmentMenuController extends Controller
{
    public function __invoke(TreatmentMenuService $menuService, TreatmentCatalogService $catalog): View
    {
        $menu = $menuService->visibleMenu();

        return view('web.treatments.index', [
            'menu' => $menu,
            'pageStructuredData' => [$catalog->catalogNode($menu)],
        ]);
    }
}
