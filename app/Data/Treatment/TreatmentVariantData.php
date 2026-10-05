<?php

declare(strict_types=1);

namespace App\Data\Treatment;

use App\ValueObjects\Treatment\Duration;
use App\ValueObjects\Treatment\Price;

/**
 * One price line of a treatment: its total duration, the care time within it when they differ, and its price.
 */
final readonly class TreatmentVariantData
{
    public function __construct(
        public ?string $label,
        public ?Duration $totalDuration,
        public ?Duration $careDuration,
        public Price $price,
    ) {}
}
