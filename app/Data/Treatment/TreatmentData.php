<?php

declare(strict_types=1);

namespace App\Data\Treatment;

final readonly class TreatmentData
{
    /**
     * @param  list<TreatmentVariantData>  $variants
     */
    public function __construct(
        public string $name,
        public ?string $subtitle,
        public ?string $description,
        public ?string $groupLabel,
        public array $variants,
    ) {}
}
