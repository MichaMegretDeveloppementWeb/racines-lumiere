<?php

declare(strict_types=1);

namespace App\View\Components\Web\Navigation;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * A rounded call to action, filled in one of the brand tones, ending on an arrow dot.
 */
class PillLink extends Component
{
    /**
     * @param  string  $tone  terracotta, cream or forest: the fill of the pill
     * @param  string  $size  regular for a section, small for the header
     */
    public function __construct(
        public string $href,
        public string $tone = 'terracotta',
        public string $size = 'regular',
        public bool $isExternal = false,
        public bool $hasArrow = true,
    ) {}

    /**
     * The fill, height and padding of the pill.
     */
    public function classes(): string
    {
        $fill = match ($this->tone) {
            'terracotta' => 'bg-terracotta text-cream shadow-[0_18px_34px_-18px_rgb(115_49_6/0.7)] hover:bg-forest',
            'cream' => 'bg-cream text-forest hover:bg-honey',
            'forest' => 'bg-forest text-cream hover:bg-terracotta',
        };

        $height = match ($this->size) {
            'regular' => 'min-h-[3.375rem] text-[0.9375rem]',
            'small' => 'min-h-11 text-sm',
        };

        $padding = $this->hasArrow ? 'py-1.5 pr-1.5 pl-7' : 'px-5';

        return "{$fill} {$height} {$padding}";
    }

    /**
     * The colours of the arrow dot, the reverse of the pill.
     */
    public function dotClasses(): string
    {
        return match ($this->tone) {
            'terracotta' => 'bg-cream text-terracotta',
            'cream' => 'bg-terracotta text-cream',
            'forest' => 'bg-honey text-forest',
        };
    }

    public function render(): View
    {
        return view('components.web.navigation.pill-link');
    }
}
