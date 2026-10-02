<?php

declare(strict_types=1);

namespace App\Data\Review;

use Carbon\CarbonImmutable;

final readonly class ReviewData
{
    public function __construct(
        public string $authorName,
        public int $rating,
        public string $body,
        public CarbonImmutable $reviewedOn,
        public ?string $treatmentLabel,
    ) {}
}
