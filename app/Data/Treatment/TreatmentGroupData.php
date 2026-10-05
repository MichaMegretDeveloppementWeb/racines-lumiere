<?php

declare(strict_types=1);

namespace App\Data\Treatment;

/**
 * The treatments of a category that share a group label, as the menu folds them.
 */
final readonly class TreatmentGroupData
{
    /**
     * @param  list<TreatmentData>  $treatments
     */
    public function __construct(
        public ?string $label,
        public array $treatments,
    ) {}
}
