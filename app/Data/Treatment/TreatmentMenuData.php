<?php

declare(strict_types=1);

namespace App\Data\Treatment;

/**
 * The menu as its page groups it: the signature rituals, the other featured categories, then the rest.
 */
final readonly class TreatmentMenuData
{
    /**
     * @param  list<TreatmentCategoryData>  $signatureCategories
     * @param  list<TreatmentCategoryData>  $featuredCategories
     * @param  list<TreatmentCategoryData>  $additionalCategories
     */
    public function __construct(
        public array $signatureCategories,
        public array $featuredCategories,
        public array $additionalCategories,
    ) {}

    /**
     * Every category of the menu, in its display order.
     *
     * @return list<TreatmentCategoryData>
     */
    public function categories(): array
    {
        return [...$this->signatureCategories, ...$this->featuredCategories, ...$this->additionalCategories];
    }
}
