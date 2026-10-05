<?php

declare(strict_types=1);

namespace App\Data\Treatment;

final readonly class TreatmentCategoryData
{
    /**
     * @param  list<string>  $descriptionParagraphs
     * @param  list<TreatmentData>  $treatments
     * @param  list<TreatmentGroupData>  $treatmentGroups  empty when no treatment of the category has a group label
     */
    public function __construct(
        public int $number,
        public string $slug,
        public string $name,
        public ?string $subtitle,
        public array $descriptionParagraphs,
        public bool $isFeatured,
        public array $treatments,
        public array $treatmentGroups,
    ) {}
}
