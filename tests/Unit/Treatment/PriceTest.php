<?php

declare(strict_types=1);

use App\ValueObjects\Treatment\Price;

it('reads a price the way the menu writes it', function (int $cents, string $label): void {
    expect((new Price($cents))->label())->toBe($label);
})->with([
    'whole euros' => [9500, "95\u{00A0}€"],
    'ten euros' => [1000, "10\u{00A0}€"],
    'with cents, French comma' => [12050, "120,50\u{00A0}€"],
    'a few cents' => [105, "1,05\u{00A0}€"],
    'free' => [0, "0\u{00A0}€"],
    'thousands, thin space' => [129000, "1\u{202F}290\u{00A0}€"],
    'thousands with cents' => [12345678, "123\u{202F}456,78\u{00A0}€"],
]);

it('gives the decimal amount that structured data expects', function (int $cents, string $amount): void {
    expect((new Price($cents))->decimalAmount())->toBe($amount);
})->with([
    'whole euros' => [9500, '95.00'],
    'with cents' => [12050, '120.50'],
    'a few cents' => [105, '1.05'],
    'free' => [0, '0.00'],
    'thousands, no separator' => [129000, '1290.00'],
]);

it('refuses a negative price', function (): void {
    new Price(-1);
})->throws(InvalidArgumentException::class);
