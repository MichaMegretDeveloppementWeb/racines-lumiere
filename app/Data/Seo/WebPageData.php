<?php

declare(strict_types=1);

namespace App\Data\Seo;

/**
 * The page being rendered, as its head describes it: its path, its schema.org type, its title, its description and its name in the trail.
 */
final readonly class WebPageData
{
    public function __construct(
        public string $path,
        public string $type,
        public string $title,
        public string $description,
        public ?string $breadcrumbName,
    ) {}
}
