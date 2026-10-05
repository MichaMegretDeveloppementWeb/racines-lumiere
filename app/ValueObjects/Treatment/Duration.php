<?php

declare(strict_types=1);

namespace App\ValueObjects\Treatment;

use InvalidArgumentException;

/**
 * A length of time on the menu, in whole minutes.
 */
final readonly class Duration
{
    private const string NO_BREAK_SPACE = "\u{00A0}";

    public function __construct(public int $minutes)
    {
        if ($minutes <= 0) {
            throw new InvalidArgumentException(sprintf('A duration must last at least one minute, %d given.', $minutes));
        }
    }

    /**
     * The duration as the menu writes it: 45 min, 1h, 1h15.
     */
    public function label(): string
    {
        if ($this->minutes < 60) {
            return $this->minutes.self::NO_BREAK_SPACE.'min';
        }

        $hours = intdiv($this->minutes, 60);
        $minutes = $this->minutes % 60;

        return $minutes === 0 ? "{$hours}h" : sprintf('%dh%02d', $hours, $minutes);
    }
}
