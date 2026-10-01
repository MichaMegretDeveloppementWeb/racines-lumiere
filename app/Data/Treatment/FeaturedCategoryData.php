<?php

declare(strict_types=1);

namespace App\Data\Treatment;

/**
 * A treatment category as the home page previews it.
 */
final readonly class FeaturedCategoryData
{
    public function __construct(
        public string $slug,
        public string $name,
        public ?string $subtitle,
    ) {}
}
