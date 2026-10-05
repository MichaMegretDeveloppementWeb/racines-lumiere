<?php

declare(strict_types=1);

use App\Models\Partner;
use Illuminate\Testing\TestResponse;

/**
 * The addresses the sitemap lists, in its order.
 *
 * @return list<string>
 */
function sitemapUrls(TestResponse $response): array
{
    $sitemap = simplexml_load_string($response->getContent());

    expect($sitemap)->not->toBeFalse()
        ->and($sitemap->getName())->toBe('urlset')
        ->and($sitemap->getNamespaces())->toBe(['' => 'http://www.sitemaps.org/schemas/sitemap/0.9']);

    $urls = [];
    foreach ($sitemap->url as $url) {
        $urls[] = (string) $url->loc;
    }

    return $urls;
}

beforeEach(function (): void {
    config(['app.url' => 'https://racines-lumiere.fr']);
});

it('lists the public pages at their addresses on the configured site, whatever host was asked', function (): void {
    $response = $this->get('http://www.example.test/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

    expect(sitemapUrls($response))->toBe([
        'https://racines-lumiere.fr/',
        'https://racines-lumiere.fr/nos-soins',
        'https://racines-lumiere.fr/nos-marques-partenaires',
        'https://racines-lumiere.fr/notre-histoire',
        'https://racines-lumiere.fr/contact',
        'https://racines-lumiere.fr/mentions-legales',
        'https://racines-lumiere.fr/politique-de-confidentialite',
    ]);
});

it('lists the trusted circle only once one partner is visible', function (): void {
    Partner::factory()->create(['is_visible' => false]);
    expect(sitemapUrls($this->get(route('sitemap'))))->not->toContain('https://racines-lumiere.fr/cercle-de-confiance');

    Partner::factory()->create(['is_visible' => true]);
    app()->forgetScopedInstances();

    expect(sitemapUrls($this->get(route('sitemap'))))->toHaveCount(8)
        ->toContain('https://racines-lumiere.fr/cercle-de-confiance');
});

it('builds the sitemap in one query', function (): void {
    // Whether the trusted circle has a visible partner, nothing else.
    expect(queryCount(fn () => $this->get(route('sitemap'))->assertOk()))->toBe(1);
});
