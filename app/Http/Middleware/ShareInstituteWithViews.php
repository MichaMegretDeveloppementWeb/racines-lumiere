<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Institute\InstituteService;
use Closure;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shares the institute details with every view rendered for the request.
 */
class ShareInstituteWithViews
{
    public function __construct(
        private readonly InstituteService $institute,
        private readonly Factory $views,
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->views->share('institute', $this->institute->details());

        return $next($request);
    }
}
