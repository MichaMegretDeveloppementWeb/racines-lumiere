<?php

declare(strict_types=1);

namespace App\Services\Typography;

class TypographyService
{
    private const string NO_BREAK_SPACE = "\u{00A0}";

    /**
     * The text with French spacing: high punctuation and guillemets kept on the line of the word they belong to.
     */
    public function withFrenchSpacing(string $text): string
    {
        $text = (string) preg_replace('/[ \x{00A0}\x{202F}]+([:;!?»])/u', self::NO_BREAK_SPACE.'$1', $text);

        return (string) preg_replace('/«[ \x{00A0}\x{202F}]+/u', '«'.self::NO_BREAK_SPACE, $text);
    }
}
