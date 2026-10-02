<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\Review\ReviewService;
use App\Services\Treatment\TreatmentMenuService;
use Illuminate\Http\Response;

class ShowAlternativeHomeController extends Controller
{
    public function __invoke(TreatmentMenuService $menu, ReviewService $reviewService): Response
    {
        $reviews = $reviewService->visibleReviews();

        return response()->view('web.home.alternative.index', [
            'featuredCategories' => $menu->featuredCategories(),
            'reviews' => $reviews,
            'reviewSummary' => $reviewService->summaryOf($reviews),
        ])->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
