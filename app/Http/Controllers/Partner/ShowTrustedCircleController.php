<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner;

use App\Data\Institute\InstituteData;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ShowTrustedCircleController extends Controller
{
    /**
     * Show the trusted circle, which does not exist until one practitioner has agreed to be published.
     */
    public function __invoke(InstituteData $institute): View
    {
        abort_unless($institute->hasTrustedCircle, 404);

        return view('web.trusted-circle.index');
    }
}
