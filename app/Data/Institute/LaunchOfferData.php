<?php

declare(strict_types=1);

namespace App\Data\Institute;

/**
 * The launch offer, once its discount is known.
 */
final readonly class LaunchOfferData
{
    public function __construct(
        public string $discount,
        public ?string $conditions,
    ) {}
}
