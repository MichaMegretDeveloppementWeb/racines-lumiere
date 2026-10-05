<?php

declare(strict_types=1);

use App\ValueObjects\Treatment\Duration;

it('reads a duration the way the menu writes it', function (int $minutes, string $label): void {
    expect((new Duration($minutes))->label())->toBe($label);
})->with([
    'a single minute' => [1, "1\u{00A0}min"],
    'ten minutes' => [10, "10\u{00A0}min"],
    'under the hour' => [45, "45\u{00A0}min"],
    'one hour' => [60, '1h'],
    'past the hour, padded' => [65, '1h05'],
    'one hour fifteen' => [75, '1h15'],
    'one hour thirty' => [90, '1h30'],
    'one hour forty-five' => [105, '1h45'],
    'two hours' => [120, '2h'],
    'two hours fifteen' => [135, '2h15'],
    'three hours thirty' => [210, '3h30'],
]);

it('refuses a duration that is not strictly positive', function (int $minutes): void {
    new Duration($minutes);
})->with([
    'zero' => [0],
    'negative' => [-15],
])->throws(InvalidArgumentException::class);
