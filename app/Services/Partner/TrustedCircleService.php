<?php

declare(strict_types=1);

namespace App\Services\Partner;

use App\Models\Partner;

class TrustedCircleService
{
    /**
     * Whether at least one practitioner has agreed to be published: the trusted circle only exists then.
     */
    public function hasVisiblePartners(): bool
    {
        return Partner::query()->where('is_visible', true)->exists();
    }
}
