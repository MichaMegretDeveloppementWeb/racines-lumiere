<?php

declare(strict_types=1);

namespace App\Services\Partner;

use App\Data\Partner\PartnerData;
use App\Models\Partner;

class TrustedCircleService
{
    /**
     * The five editorial recommendations, published only with consent and read in one query.
     *
     * @return list<PartnerData>
     */
    public function visiblePartners(): array
    {
        return Partner::query()
            ->where('is_visible', true)
            ->orderBy('position')
            ->orderBy('id')
            ->get(['slug', 'name', 'organization_name', 'specialty', 'town', 'website_url'])
            ->map(fn (Partner $partner): PartnerData => new PartnerData(
                slug: $partner->slug,
                name: $partner->name,
                organizationName: $partner->organization_name,
                specialty: $partner->specialty,
                town: $partner->town,
                websiteUrl: $partner->website_url,
            ))
            ->all();
    }

    /**
     * Whether at least one practitioner has agreed to be published: the trusted circle only exists then.
     */
    public function hasVisiblePartners(): bool
    {
        return Partner::query()->where('is_visible', true)->exists();
    }
}
