<?php

declare(strict_types=1);

use App\Services\Typography\TypographyService;

const NO_BREAK_SPACE = "\u{00A0}";

it('binds high punctuation to the word before it', function (string $text, string $expected): void {
    expect((new TypographyService)->withFrenchSpacing($text))->toBe($expected);
})->with([
    'colon' => ['Sur place : parking', 'Sur place'.NO_BREAK_SPACE.': parking'],
    'semicolon' => ['ralentir ; respirer', 'ralentir'.NO_BREAK_SPACE.'; respirer'],
    'exclamation mark' => ['Je recommande sans hésiter !', 'Je recommande sans hésiter'.NO_BREAK_SPACE.'!'],
    'question mark' => ['Pourquoi pas ?', 'Pourquoi pas'.NO_BREAK_SPACE.'?'],
    'emoticon' => ['un petit coin de paradis :)', 'un petit coin de paradis'.NO_BREAK_SPACE.':)'],
]);

it('binds guillemets to the words they enclose', function (): void {
    expect((new TypographyService)->withFrenchSpacing('le rituel « 10 minutes pour moi » chaque jour'))
        ->toBe('le rituel «'.NO_BREAK_SPACE.'10 minutes pour moi'.NO_BREAK_SPACE.'» chaque jour');
});

it('leaves text without spaced punctuation untouched', function (string $text): void {
    expect((new TypographyService)->withFrenchSpacing($text))->toBe($text);
})->with([
    'an address' => ['https://racines-lumiere.fr/contact'],
    'a tagline' => ['Conscious. Skin. Science.'],
    'punctuation glued to its word' => ['Super!'],
]);

it('never doubles a space that is already non-breaking', function (): void {
    $typeset = 'Sur place'.NO_BREAK_SPACE.': parking';

    expect((new TypographyService)->withFrenchSpacing($typeset))->toBe($typeset);
});
