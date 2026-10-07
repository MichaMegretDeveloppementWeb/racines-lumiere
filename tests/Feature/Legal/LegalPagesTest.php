<?php

declare(strict_types=1);

it('renders each legal document with its navigation and linked structured data', function (string $route, string $title): void {
    $response = $this->get(route($route))->assertOk()
        ->assertSee('<h1>'.$title.'</h1>', false)
        ->assertSee('aria-label="Fil d’Ariane"', false)
        ->assertSee('<li aria-current="page">'.$title.'</li>', false)
        ->assertDontSee('Cette page est en préparation.');

    preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $response->getContent(), $script);
    $graph = json_decode($script[1], true, flags: JSON_THROW_ON_ERROR)['@graph'];
    $page = collect($graph)->firstWhere('@type', 'WebPage');
    $breadcrumb = collect($graph)->firstWhere('@type', 'BreadcrumbList');

    expect($page['breadcrumb']['@id'])->toBe($breadcrumb['@id'])
        ->and($breadcrumb['itemListElement'][1]['name'])->toBe($title);
})->with([
    ['legal.notice', 'Mentions légales'],
    ['legal.privacy', 'Politique de confidentialité'],
]);

it('identifies missing company information without reusing the former business identity', function (): void {
    $this->get(route('legal.notice'))->assertOk()
        ->assertSee('Dénomination sociale')->assertSee('À compléter')
        ->assertSee('Hostinger International Ltd')->assertSee('6023 Larnaca, Chypre')
        ->assertSee('médiateur')->assertSee(config('institute.contact.email'))
        ->assertDontSee('852 797 414')->assertDontSee('AP INSTITUT');
});

it('describes the contact data flow and uses the configured public contact and session lifetime', function (): void {
    config(['institute.contact.email' => 'privacy@example.com', 'session.lifetime' => 45]);

    $this->get(route('legal.privacy'))->assertOk()
        ->assertSee('téléphone est facultatif')->assertSee('votre consentement')
        ->assertSee('n’est pas enregistré dans la base de données du site')
        ->assertSee('Durée de conservation des échanges')->assertSee('une heure')
        ->assertSee('45 minutes')->assertSee('privacy@example.com')
        ->assertSee('https://www.cnil.fr/fr/adresser-une-plainte', false);
});
