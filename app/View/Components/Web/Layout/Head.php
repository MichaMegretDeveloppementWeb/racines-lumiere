<?php

declare(strict_types=1);

namespace App\View\Components\Web\Layout;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Head extends Component
{
    private const string ELEMENT_INDENT = '        ';

    private const string CONTENT_INDENT = '            ';

    /**
     * The markup of the head one element per line, the template's blank lines kept, the content of a script one level deeper.
     */
    public function lines(Htmlable $markup): string
    {
        $lines = [];
        $content = null;

        foreach (explode("\n", $this->separated($markup->toHtml())) as $line) {
            $element = trim($line);

            if ($content !== null && preg_match('#^</(script|style)>$#', $element) !== 1) {
                $content[] = $line;
            } elseif ($content !== null) {
                array_push($lines, ...$this->contentLines($content));
                $lines[] = self::ELEMENT_INDENT.$element;
                $content = null;
            } elseif ($element === '') {
                $lines[] = '';
            } else {
                $lines[] = self::ELEMENT_INDENT.$element;
                $content = preg_match('#^<(script|style)\b[^>]*>$#', $element) === 1 ? [] : null;
            }
        }

        return implode("\n", $lines);
    }

    public function render(): View
    {
        return view('components.web.layout.head');
    }

    /**
     * The markup broken between two elements, a blank line where the template had one, the content of scripts and styles untouched.
     */
    private function separated(string $markup): string
    {
        return (string) preg_replace_callback(
            '#<(script|style)\b[^>]*>.*?</\1(*SKIP)(*FAIL)|>\s*(?=<(?!/))#s',
            fn (array $gap): string => substr_count($gap[0], "\n") > 1 ? ">\n\n" : ">\n",
            trim($markup),
        );
    }

    /**
     * The lines of a script, moved under its tag with their own indentation kept.
     *
     * @param  list<string>  $content
     * @return list<string>
     */
    private function contentLines(array $content): array
    {
        $filledLines = array_filter($content, fn (string $line): bool => trim($line) !== '');
        $margin = $filledLines === [] ? 0 : min(array_map(fn (string $line): int => strlen($line) - strlen(ltrim($line)), $filledLines));

        return array_map(fn (string $line): string => trim($line) === '' ? '' : self::CONTENT_INDENT.rtrim(substr($line, $margin)), $content);
    }
}
