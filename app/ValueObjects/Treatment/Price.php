<?php

declare(strict_types=1);

namespace App\ValueObjects\Treatment;

use InvalidArgumentException;

/**
 * An amount in euros, kept in cents.
 */
final readonly class Price
{
    private const string NO_BREAK_SPACE = "\u{00A0}";

    private const string NARROW_NO_BREAK_SPACE = "\u{202F}";

    public function __construct(public int $cents)
    {
        if ($cents < 0) {
            throw new InvalidArgumentException(sprintf('A price cannot be negative, %d cents given.', $cents));
        }
    }

    /**
     * The price as the menu writes it: 95 €, 120,50 €, the cents only when there are some.
     */
    public function label(): string
    {
        $euros = number_format(intdiv($this->cents, 100), 0, '', self::NARROW_NO_BREAK_SPACE);
        $cents = $this->cents % 100;

        return ($cents === 0 ? $euros : sprintf('%s,%02d', $euros, $cents)).self::NO_BREAK_SPACE.'€';
    }

    /**
     * The amount with two decimals and a point, as structured data expects it.
     */
    public function decimalAmount(): string
    {
        return sprintf('%d.%02d', intdiv($this->cents, 100), $this->cents % 100);
    }
}
