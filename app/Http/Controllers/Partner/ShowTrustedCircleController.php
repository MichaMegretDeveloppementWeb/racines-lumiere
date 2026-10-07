<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner;

use App\Data\Institute\InstituteData;
use App\Http\Controllers\Controller;
use App\Services\Partner\TrustedCircleService;
use App\Services\Seo\PartnerListService;
use Illuminate\Contracts\View\View;

class ShowTrustedCircleController extends Controller
{
    /**
     * Show the trusted circle, which does not exist until one practitioner has agreed to be published.
     */
    public function __invoke(InstituteData $institute, TrustedCircleService $circle, PartnerListService $structuredData): View
    {
        abort_unless($institute->hasTrustedCircle, 404);

        $partners = $circle->visiblePartners();

        return view('web.trusted-circle.index', [
            'partners' => $partners,
            'pageStructuredData' => [$structuredData->listNode($partners)],
        ]);
    }
}
