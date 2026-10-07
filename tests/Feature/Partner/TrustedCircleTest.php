<?php

declare(strict_types=1);

use App\Data\Partner\PartnerData;
use App\Models\Partner;
use App\Services\Partner\TrustedCircleService;

it('has no trusted circle while every partner is hidden', function (): void {
    Partner::factory()->count(2)->create(['is_visible' => false]);

    expect(app(TrustedCircleService::class)->hasVisiblePartners())->toBeFalse();

    $this->get(route('trusted-circle'))->assertNotFound();
    $this->get(route('home'))->assertOk()->assertDontSee(route('trusted-circle'));
    $this->get(route('sitemap'))->assertDontSee(route('trusted-circle'));
});

it('opens the trusted circle as soon as one partner is visible', function (): void {
    Partner::factory()->create(['is_visible' => false]);
    Partner::factory()->create(['is_visible' => true]);

    expect(app(TrustedCircleService::class)->hasVisiblePartners())->toBeTrue();

    $this->get(route('trusted-circle'))->assertOk()->assertSee('<main id="content"', false);
    $this->get(route('home'))->assertOk()->assertSee('href="'.route('trusted-circle').'"', false);
    $this->get(route('sitemap'))->assertSee(route('trusted-circle'));
});

it('presents only approved practitioners in editorial order with their public details', function (): void {
    Partner::factory()->create(['name' => 'Hidden practitioner', 'is_visible' => false]);
    Partner::factory()->create(['name' => 'Second practitioner', 'position' => 20, 'is_visible' => true]);
    Partner::factory()->create([
        'slug' => 'first-practitioner', 'name' => 'First practitioner', 'position' => 10, 'is_visible' => true,
        'organization_name' => 'Independent practice', 'specialty' => 'Ostéopathe',
        'town' => 'Allinges', 'website_url' => 'https://example.com/practice',
    ]);

    $this->get(route('trusted-circle'))->assertOk()
        ->assertSeeInOrder(['First practitioner', 'Second practitioner'])
        ->assertSee('Independent practice')->assertSee('Ostéopathe')->assertSee('Allinges')
        ->assertSee('href="https://example.com/practice"', false)
        ->assertDontSee('Hidden practitioner')->assertDontSee('Cette page est en préparation.');

    expect(app(TrustedCircleService::class)->visiblePartners())->each->toBeInstanceOf(PartnerData::class);
});

it('escapes practitioner content and leaves missing optional details out of its structured data', function (): void {
    Partner::factory()->create([
        'is_visible' => true, 'name' => '<script>alert(1)</script>',
        'town' => null, 'website_url' => null, 'organization_name' => null,
    ]);

    $response = $this->get(route('trusted-circle'))->assertOk()
        ->assertSee('<script>alert(1)</script>')->assertDontSee('<script>alert(1)</script>', false)
        ->assertDontSee('Découvrir son site');
    preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $response->getContent(), $script);
    $graph = json_decode($script[1], true, flags: JSON_THROW_ON_ERROR)['@graph'];
    $list = collect($graph)->firstWhere('@type', 'ItemList');

    expect($list['itemListElement'][0]['item'])->not->toHaveKey('url');
});

it('describes the published selection without claiming employment or medical credentials', function (): void {
    config(['app.url' => 'https://racines-lumiere.fr']);
    Partner::factory()->create(['name' => 'Unpublished person', 'is_visible' => false]);
    Partner::factory()->create([
        'slug' => 'approved-person', 'name' => 'Approved person', 'is_visible' => true,
        'specialty' => 'Conseillère en image', 'website_url' => 'https://example.com/person',
    ]);

    $response = $this->get('https://preview.example/cercle-de-confiance')->assertOk();
    preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $response->getContent(), $script);
    $graph = json_decode($script[1], true, flags: JSON_THROW_ON_ERROR)['@graph'];
    $page = collect($graph)->firstWhere('@type', 'CollectionPage');
    $list = collect($graph)->firstWhere('@type', 'ItemList');

    expect($page['mainEntity'])->toBe(['@id' => 'https://racines-lumiere.fr/cercle-de-confiance#practitioners']);
    expect($list['numberOfItems'])->toBe(1)
        ->and($list['mainEntityOfPage'])->toBe(['@id' => $page['@id']])
        ->and($list['itemListElement'])->toBe([[
            '@type' => 'ListItem', 'position' => 1,
            'item' => [
                '@type' => 'Person', '@id' => 'https://racines-lumiere.fr/cercle-de-confiance#approved-person',
                'name' => 'Approved person', 'jobTitle' => 'Conseillère en image', 'url' => 'https://example.com/person',
            ],
        ]]);
    $response->assertDontSee('Unpublished person');
});

it('renders the whole directory within two queries', function (): void {
    Partner::factory()->count(5)->create(['is_visible' => true]);

    // One visibility check shared with navigation, one projected read for the five editorial recommendations.
    expect(queryCount(fn () => $this->get(route('trusted-circle'))->assertOk()))->toBeLessThanOrEqual(2);
});
