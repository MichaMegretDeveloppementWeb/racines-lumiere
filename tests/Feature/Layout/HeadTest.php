<?php

declare(strict_types=1);

/**
 * The lines of the page's head, between its opening and closing tags.
 *
 * @return list<string>
 */
function headLines(string $html): array
{
    preg_match('#\n {4}<head>\n(.*?)\n {4}</head>\n#s', $html, $head);

    expect($head)->not->toBeEmpty();

    return explode("\n", $head[1]);
}

it('lays out its markup one element per line, the content of an element one level deeper', function (): void {
    $markup = "\n        <meta charset=\"utf-8\"><link rel=\"first\">   <link rel=\"second\" />\n\n\n"
        ."        <script type=\"module\" src=\"/app.js\"></script>\n"
        ."<script>\n    const markup = '<b>bold</b> <i>italic</i>';\n</script>"
        ."<script type=\"application/ld+json\">\n{\n    \"list\": [\n        1\n    ]\n}\n</script>\n    ";

    $head = (string) $this->blade('<x-web.layout.head>{!! $markup !!}</x-web.layout.head>', ['markup' => $markup]);

    expect($head)->toBe(implode("\n", [
        '<head>',
        '        <meta charset="utf-8">',
        '        <link rel="first">',
        '        <link rel="second" />',
        '',
        '        <script type="module" src="/app.js"></script>',
        '        <script>',
        "            const markup = '<b>bold</b> <i>italic</i>';",
        '        </script>',
        '        <script type="application/ld+json">',
        '            {',
        '                "list": [',
        '                    1',
        '                ]',
        '            }',
        '        </script>',
        '    </head>',
    ])."\n");
});

it('writes one element per line in the head of every page, its structured data indented under it', function (string $route): void {
    $lines = headLines($this->get(route($route))->assertOk()->getContent());
    $scriptStart = array_search('        <script type="application/ld+json">', $lines, true);
    $scriptEnd = array_search('        </script>', $lines, true);
    $jsonLines = array_slice($lines, $scriptStart + 1, $scriptEnd - $scriptStart - 1);
    $elementLines = array_slice($lines, 0, $scriptStart);

    expect(array_filter($elementLines, fn (string $line): bool => $line !== '' && preg_match('#^ {8}<[a-z][^<]*(</[a-z]+>)?$#', $line) !== 1))->toBe([])
        ->and($scriptEnd)->toBe(count($lines) - 1)
        ->and($jsonLines[0])->toBe('            {')
        ->and(array_filter($jsonLines, fn (string $line): bool => ! str_starts_with($line, '            ')))->toBe([])
        ->and(json_decode(implode("\n", $jsonLines), true, flags: JSON_THROW_ON_ERROR)['@context'])->toBe('https://schema.org');
})->with(['home' => ['home'], 'treatment menu' => ['treatments'], 'story' => ['story'], 'a page in preparation' => ['contact']]);
