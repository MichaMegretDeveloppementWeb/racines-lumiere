<?php

declare(strict_types=1);

namespace App\View\Components\Web\Layout;

use App\Data\Institute\InstituteData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Header extends Component
{
    public function __construct(
        private readonly InstituteData $institute,
        private readonly Request $request,
    ) {}

    /**
     * The main menu, in its order: the trusted circle only appears once it exists.
     *
     * @return list<array{label: string, url: string, isCurrent: bool}>
     */
    public function links(): array
    {
        $routes = [
            'treatments' => 'Nos soins',
            'brands' => 'Nos marques partenaires',
            'story' => 'Notre histoire',
            'trusted-circle' => 'Cercle de confiance',
            'contact' => 'Contact',
        ];

        if (! $this->institute->hasTrustedCircle) {
            unset($routes['trusted-circle']);
        }

        return array_values(array_map(fn (string $route, string $label): array => [
            'label' => $label,
            'url' => route($route),
            'isCurrent' => $this->request->routeIs($route),
        ], array_keys($routes), $routes));
    }

    public function render(): View
    {
        return view('components.web.layout.header');
    }
}
