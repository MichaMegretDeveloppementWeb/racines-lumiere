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
     * Every available width of the image in one format, as a srcset value.
     */
    public function srcsetFor(string $format): string
    {
        return implode(', ', array_map(
            fn (int $width): string => asset("images/{$this->name}-{$width}w.{$format}")." {$width}w",
            $this->widths,
        ));
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
