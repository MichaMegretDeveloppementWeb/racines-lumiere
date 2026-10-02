<?php

declare(strict_types=1);

namespace App\View\Components\Web\Layout;

use App\Data\Institute\InstituteData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Header extends Component
{
    private const array PRIMARY_ROUTES = [
        'treatments' => 'Nos soins',
        'brands' => 'Nos marques partenaires',
        'story' => 'Notre histoire',
    ];

    private const array SECONDARY_ROUTES = [
        'trusted-circle' => 'Cercle de confiance',
        'contact' => 'Contact',
    ];

    public function __construct(
        private readonly InstituteData $institute,
        private readonly Request $request,
    ) {}

    /**
     * The pages set to the left of the mark on wide screens.
     *
     * @return list<array{label: string, url: string, isCurrent: bool}>
     */
    public function primaryLinks(): array
    {
        return $this->linksTo(self::PRIMARY_ROUTES);
    }

    /**
     * The pages set to the right of the mark: the trusted circle only appears once it exists.
     *
     * @return list<array{label: string, url: string, isCurrent: bool}>
     */
    public function secondaryLinks(): array
    {
        $routes = self::SECONDARY_ROUTES;

        if (! $this->institute->hasTrustedCircle) {
            unset($routes['trusted-circle']);
        }

        return $this->linksTo($routes);
    }

    /**
     * The whole menu in its order, as the panel of small screens lists it.
     *
     * @return list<array{label: string, url: string, isCurrent: bool}>
     */
    public function links(): array
    {
        return [...$this->primaryLinks(), ...$this->secondaryLinks()];
    }

    public function render(): View
    {
        return view('components.web.layout.header');
    }

    /**
     * @param  array<string, string>  $routes  route names and their labels
     * @return list<array{label: string, url: string, isCurrent: bool}>
     */
    private function linksTo(array $routes): array
    {
        return array_values(array_map(fn (string $route, string $label): array => [
            'label' => $label,
            'url' => route($route),
            'isCurrent' => $this->request->routeIs($route),
        ], array_keys($routes), $routes));
    }
}
