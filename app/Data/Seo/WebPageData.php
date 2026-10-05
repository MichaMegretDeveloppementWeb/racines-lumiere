<?php

declare(strict_types=1);

namespace App\Data\Seo;

/**
 * The page being rendered, as its head describes it: its path, its title, its description and its name in the trail.
 */
final readonly class WebPageData
{
    public function __construct(
        public string $path,
        public string $title,
        public string $description,
        public ?string $breadcrumbName,
    ) {}
}
