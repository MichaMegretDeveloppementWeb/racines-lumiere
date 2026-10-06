<?php

declare(strict_types=1);

it('renders each page within the layout and reflects its preparation status', function (string $route, string $title, bool $isPreparing): void {
    $response = $this->get(route($route))
        ->assertOk()
        ->assertSee('<main id="content"', false)
        ->assertSee('<h1', false)
        ->assertSee($title);

    $isPreparing ? $response->assertSee('Cette page est en préparation.') : $response->assertDontSee('Cette page est en préparation.');
})->with([
    'partner brands' => ['brands', 'Nos marques partenaires', false],
    'contact' => ['contact', 'Contact', false],
    'legal notice' => ['legal.notice', 'Mentions légales', true],
    'privacy policy' => ['legal.privacy', 'Politique de confidentialité', true],
]);

it('serves the public pages at their French addresses', function (string $route, string $path): void {
    expect(route($route, absolute: false))->toBe($path);
})->with([
    ['home', '/'],
    ['treatments', '/nos-soins'],
    ['brands', '/nos-marques-partenaires'],
    ['story', '/notre-histoire'],
    ['trusted-circle', '/cercle-de-confiance'],
    ['contact', '/contact'],
    ['legal.notice', '/mentions-legales'],
    ['legal.privacy', '/politique-de-confidentialite'],
]);
