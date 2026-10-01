<?php

declare(strict_types=1);

it('renders each page still in preparation within the layout', function (string $route, string $title): void {
    $this->get(route($route))
        ->assertOk()
        ->assertSee('<main id="content"', false)
        ->assertSee('<h1', false)
        ->assertSee($title)
        ->assertSee('Cette page est en préparation.');
})->with([
    'treatment menu' => ['treatments', 'Nos soins'],
    'partner brands' => ['brands', 'Nos marques partenaires'],
    'story' => ['story', 'Notre histoire'],
    'contact' => ['contact', 'Contact'],
    'legal notice' => ['legal.notice', 'Mentions légales'],
    'privacy policy' => ['legal.privacy', 'Politique de confidentialité'],
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
