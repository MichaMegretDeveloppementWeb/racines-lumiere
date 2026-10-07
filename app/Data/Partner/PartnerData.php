<?php

declare(strict_types=1);

namespace App\Data\Partner;

final readonly class PartnerData
{
    public function __construct(
        public string $slug,
        public string $name,
        public ?string $organizationName,
        public string $specialty,
        public ?string $town,
        public ?string $websiteUrl,
    ) {}
}
