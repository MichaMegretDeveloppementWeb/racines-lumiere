<?php

declare(strict_types=1);

it('points every page at its address on the configured site, whatever host was asked', function (string $route, string $canonical): void {
    config(['app.url' => 'https://racines-lumiere.fr']);

    $this->get('http://www.example.test'.route($route, absolute: false).'?utm_source=instagram')
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.$canonical.'">', false);
})->with([
    'home' => ['home', 'https://racines-lumiere.fr/'],
    'treatment menu' => ['treatments', 'https://racines-lumiere.fr/nos-soins'],
    'legal notice' => ['legal.notice', 'https://racines-lumiere.fr/mentions-legales'],
]);
