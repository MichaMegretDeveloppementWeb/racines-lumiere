<?php

declare(strict_types=1);

beforeEach(function (): void {
    config(['app.url' => 'https://racines-lumiere.fr']);
});

it('points every page at its address on the configured site, whatever host was asked', function (string $route, string $canonical): void {
    $this->get('http://www.example.test'.route($route, absolute: false).'?utm_source=instagram')
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.$canonical.'">', false)
        ->assertSee('<meta property="og:url" content="'.$canonical.'">', false);
})->with([
    'home' => ['home', 'https://racines-lumiere.fr/'],
    'treatment menu' => ['treatments', 'https://racines-lumiere.fr/nos-soins'],
    'legal notice' => ['legal.notice', 'https://racines-lumiere.fr/mentions-legales'],
]);

it('gives every page its own title and description, for search results and for sharing alike', function (string $route, string $title, string $description): void {
    $this->get(route($route))
        ->assertOk()
        ->assertSee('<title>'.e($title).'</title>', false)
        ->assertSee('<meta name="description" content="'.e($description).'">', false)
        ->assertSee('<meta property="og:title" content="'.e($title).'">', false)
        ->assertSee('<meta property="og:description" content="'.e($description).'">', false);
})->with([
    'home' => [
        'home',
        'Racines & Lumière · Institut de beauté holistique à Sciez',
        'Institut de beauté holistique à Sciez : rituels sur mesure pour le corps et le visage. Ouverture le 3 novembre, réservation en ligne dès maintenant.',
    ],
    'treatment menu' => [
        'treatments',
        'Carte des soins et tarifs à Sciez · Racines & Lumière',
        'Carte des soins de Racines & Lumière à Sciez : rituels corps et visage, Kobido, massage facial, soins Comfort Zone, épilations. Tarifs et réservation en ligne.',
    ],
    'privacy policy' => [
        'legal.privacy',
        'Politique de confidentialité · Racines & Lumière',
        'Comment Racines & Lumière traite les informations transmises par le formulaire de contact.',
    ],
]);

it('describes the home page as it stands after the opening', function (): void {
    config(['institute.is_open' => true]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<meta name="description" content="Institut de beauté holistique à Sciez, en Chablais : rituels sur mesure pour le corps et le visage, massages et soins experts. Réservation en ligne.">', false);
});

it('keeps every page out of the index outside production only', function (): void {
    $this->get(route('home'))->assertOk()->assertSee('<meta name="robots" content="noindex, nofollow">', false);

    app()->detectEnvironment(fn (): string => 'production');

    $this->get(route('home'))->assertOk()->assertDontSee('name="robots"', false);
});

it('shares every page with the image of the site, in the size the networks expect', function (): void {
    $this->get(route('story'))
        ->assertOk()
        ->assertSee('<meta property="og:type" content="website">', false)
        ->assertSee('<meta property="og:site_name" content="Racines &amp; Lumière">', false)
        ->assertSee('<meta property="og:locale" content="fr_FR">', false)
        ->assertSee('<meta property="og:image" content="https://racines-lumiere.fr/images/og/default-1200w.jpg">', false)
        ->assertSee('<meta property="og:image:width" content="1200">', false)
        ->assertSee('<meta property="og:image:height" content="630">', false)
        ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);

    expect(getimagesize(public_path('images/og/default-1200w.jpg')))->toMatchArray([0 => 1200, 1 => 630, 'mime' => 'image/jpeg']);
});
