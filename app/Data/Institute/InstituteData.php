<?php

declare(strict_types=1);

namespace App\Data\Institute;

/**
 * What every page knows about the institute: whether it has opened, and where to book.
 */
final readonly class InstituteData
{
    public function __construct(
        public bool $isOpen,
        public string $bookingUrl,
    ) {}
}
