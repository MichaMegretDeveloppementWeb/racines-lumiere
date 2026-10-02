<?php

declare(strict_types=1);

namespace App\Data\Review;

final readonly class ReviewSummaryData
{
    public function __construct(
        public float $averageRating,
        public int $count,
    ) {}

    /**
     * The average out of five, to one decimal with a French comma, and no trailing zero.
     */
    public function averageLabel(): string
    {
        return str_replace('.', ',', (string) round($this->averageRating, 1));
    }
}
