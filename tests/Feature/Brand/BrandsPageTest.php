<?php

declare(strict_types=1);

use App\Data\Brand\BrandData;
use App\Models\Brand;
use App\Services\Brand\BrandCatalogService;
use Database\Seeders\Content\BrandSeeder;

it('presents only visible brands in their editorial order with their complete texts', function (): void {
    Brand::factory()->create(['name' => 'Second partner', 'position' => 20]);
    Brand::factory()->create([
        'name' => 'First partner',
        'position' => 10,
        'long_text' => "First detailed paragraph.\n\nSecond detailed paragraph.",
        'role_text' => 'A specific role in the institute.',
    ]);
    Brand::factory()->create(['name' => 'Hidden partner', 'position' => 1, 'is_visible' => false]);

    $this->get(route('brands'))->assertOk()
        ->assertSeeInOrder(['First partner', 'Second partner'])
        ->assertSee('First detailed paragraph.')
        ->assertSee('Second detailed paragraph.')
        ->assertSee('A specific role in the institute.')
        ->assertDontSee('Hidden partner')
        ->assertDontSee('Cette page est en préparation.');

    $brands = app(BrandCatalogService::class)->visibleBrands();

    expect($brands)->each->toBeInstanceOf(BrandData::class)
        ->and($brands[0]->paragraphs)->toBe(['First detailed paragraph.', 'Second detailed paragraph.']);
});

it('offers a products link only when configured and escapes editorial text', function (): void {
    $brand = Brand::factory()->create([
        'long_text' => '<script>alert("unsafe")</script>',
        'products_url' => null,
        'logo_path' => null,
        'short_text' => null,
        'role_text' => null,
        'tagline' => null,
    ]);

    $this->get(route('brands'))->assertOk()
        ->assertSee('<script>alert("unsafe")</script>')
        ->assertDontSee('<script>alert("unsafe")</script>', false)
        ->assertDontSee('Voir les produits sur Booksy');

    $brand->forceFill(['products_url' => 'https://booksy.com/fr-fr/brand-products'])->save();

    $response = $this->get(route('brands'))->assertOk()
        ->assertSee('href="https://booksy.com/fr-fr/brand-products"', false)
        ->assertSee('Voir les produits sur Booksy');

    $beforeDialog = explode('<dialog', $response->getContent(), 2)[0];

    expect($beforeDialog)->toContain('href="https://booksy.com/fr-fr/brand-products"');
});

it('describes the same selection in structured data using the canonical site address', function (): void {
    config(['app.url' => 'https://racines-lumiere.fr']);
    $this->seed(BrandSeeder::class);
    Brand::query()->where('slug', 'ilse')->update(['is_visible' => false]);

    $response = $this->get('https://preview.example/nos-marques-partenaires')->assertOk();
    preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $response->getContent(), $script);
    $graph = json_decode($script[1], true, flags: JSON_THROW_ON_ERROR)['@graph'];
    $page = collect($graph)->firstWhere('@type', 'CollectionPage');
    $list = collect($graph)->firstWhere('@type', 'ItemList');

    expect($page['url'])->toBe('https://racines-lumiere.fr/nos-marques-partenaires')
        ->and($list['mainEntityOfPage'])->toBe(['@id' => $page['@id']])
        ->and($list['numberOfItems'])->toBe(6)
        ->and(array_column($list['itemListElement'], 'position'))->toBe(range(1, 6))
        ->and(array_column(array_column($list['itemListElement'], 'item'), 'name'))->toBe([
            'Altearah Bio', 'Comfort Zone', 'Laboté', 'Gingerly', 'Skin Diligent', 'Demain Beauty',
        ]);

    foreach ($list['itemListElement'] as $entry) {
        expect($entry['item']['@type'])->toBe('Brand')
            ->and($entry['item']['logo'])->toStartWith('https://racines-lumiere.fr/images/brands/');
        $response->assertSee($entry['item']['description']);
        expect(is_file(public_path(parse_url($entry['item']['logo'], PHP_URL_PATH))))->toBeTrue();
    }
});

it('keeps the complete page within its two query budget', function (): void {
    $this->seed(BrandSeeder::class);

    // One read for the seven editorial brands, one shared read for the trusted-circle navigation.
    expect(queryCount(fn () => $this->get(route('brands'))->assertOk()))->toBeLessThanOrEqual(2);
});
