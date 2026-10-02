<?php

declare(strict_types=1);

namespace App\View\Components\Web\Media;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * A responsive image served in AVIF first, from files named {name}-{width}w.{format} under public/images.
 */
class Picture extends Component
{
    /**
     * @param  list<int>  $widths  the widths available, smallest first
     * @param  string  $fallback  the format of the plain image tag: webp for marks with transparency, jpg for photos
     * @param  string|null  $portraitName  a vertical crop served to screens held upright
     * @param  list<int>  $portraitWidths  the widths available for that crop, smallest first
     */
    public function __construct(
        public string $name,
        public array $widths,
        public string $sizes,
        public string $alt,
        public int $width,
        public int $height,
        public string $fallback = 'jpg',
        public bool $isPriority = false,
        public ?string $portraitName = null,
        public array $portraitWidths = [],
    ) {}

    /**
     * The modern formats offered before the fallback.
     *
     * @return list<string>
     */
    public function sourceFormats(): array
    {
        return $this->fallback === 'webp' ? ['avif'] : ['avif', 'webp'];
    }

    /**
     * The formats of the vertical crop: the fallback too, since the plain image tag only carries the main crop.
     *
     * @return list<string>
     */
    public function portraitFormats(): array
    {
        return [...$this->sourceFormats(), $this->fallback];
    }

    /**
     * Every available width of the image, or of its vertical crop, in one format, as a srcset value.
     */
    public function srcsetFor(string $format, bool $isPortrait = false): string
    {
        [$name, $widths] = $isPortrait ? [$this->portraitName, $this->portraitWidths] : [$this->name, $this->widths];

        return implode(', ', array_map(
            fn (int $width): string => asset("images/{$name}-{$width}w.{$format}")." {$width}w",
            $widths,
        ));
    }

    /**
     * The media type a source announces for a file format.
     */
    public function mediaTypeOf(string $format): string
    {
        return match ($format) {
            'avif' => 'image/avif',
            'webp' => 'image/webp',
            'jpg' => 'image/jpeg',
        };
    }

    /**
     * The smallest width in the fallback format, for browsers that ignore srcset.
     */
    public function fallbackUrl(): string
    {
        return asset("images/{$this->name}-{$this->widths[0]}w.{$this->fallback}");
    }

    public function render(): View
    {
        return view('components.web.media.picture');
    }
}
