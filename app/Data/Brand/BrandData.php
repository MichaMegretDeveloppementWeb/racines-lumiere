<?php

declare(strict_types=1);

namespace App\Data\Brand;

final readonly class BrandData
{
    /**
     * @param  list<string>  $paragraphs
     */
    public function __construct(
        public string $slug,
        public string $name,
        public ?string $tagline,
        public ?string $shortText,
        public array $paragraphs,
        public ?string $roleText,
        public ?string $logoPath,
        public ?string $productsUrl,
    ) {}
}
