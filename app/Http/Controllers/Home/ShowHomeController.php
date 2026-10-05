<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\Review\ReviewService;
use App\Services\Treatment\TreatmentMenuService;
use Illuminate\Contracts\View\View;

class ShowHomeController extends Controller
{
    public function __invoke(TreatmentMenuService $menu, ReviewService $reviewService): View
    {
        $reviews = $reviewService->visibleReviews();

        return view('web.home.index', [
            'featuredCategories' => $menu->featuredCategories(),
            'reviews' => $reviews,
            'reviewSummary' => $reviewService->summaryOf($reviews),
        ]);
    }
}
