<?php

declare(strict_types=1);

use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentVariant;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Testing\TestResponse;

/**
 * The nodes of the page's structured data graph.
 *
 * @return list<array<string, mixed>>
 */
function structuredGraph(TestResponse $response): array
{
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $response->getContent(), $scripts);

    expect($scripts[1])->toHaveCount(1);

    return json_decode($scripts[1][0], true, flags: JSON_THROW_ON_ERROR)['@graph'];
}

/**
 * The nodes of the graph that carry the given type.
 *
 * @param  list<array<string, mixed>>  $graph
 * @return list<array<string, mixed>>
 */
function nodesOfType(array $graph, string $type): array
{
    return array_values(array_filter($graph, fn (array $node): bool => $node['@type'] === $type));
}

beforeEach(function (): void {
    config(['app.url' => 'https://racines-lumiere.fr']);
});

it('describes the institute on every page, from the configured address of the site', function (string $route): void {
    $this->seed(DatabaseSeeder::class);

    [$institute] = nodesOfType(structuredGraph($this->get(route($route))->assertOk()), 'BeautySalon');

    expect($institute['@id'])->toBe('https://racines-lumiere.fr/#institute')
        ->and($institute['url'])->toBe('https://racines-lumiere.fr/')
        ->and($institute['name'])->toBe('Racines & Lumière')
        ->and($institute['address'])->toMatchArray(['streetAddress' => '205 avenue des Charmes', 'postalCode' => '74140', 'addressLocality' => 'Sciez', 'addressCountry' => 'FR'])
        ->and($institute['potentialAction'])->toMatchArray(['@type' => 'ReserveAction', 'target' => config('institute.booking_url')]);
})->with(['home' => ['home'], 'treatment menu' => ['treatments'], 'a page in preparation' => ['story']]);

it('gives the telephone only once the line is active', function (): void {
    [$withoutLine] = nodesOfType(structuredGraph($this->get(route('story'))), 'BeautySalon');

    config(['institute.contact.phone' => '04 50 00 00 00']);
    app()->forgetScopedInstances();
    [$withLine] = nodesOfType(structuredGraph($this->get(route('story'))), 'BeautySalon');

    expect($withoutLine)->not->toHaveKey('telephone')
        ->and($withLine['telephone'])->toBe('+33 4 50 00 00 00');
});

it('never marks up the reviews', function (): void {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('Ce que vous en dites')
        ->assertDontSee('AggregateRating')
        ->assertDontSee('"Review"', false);
});

it('marks up every treatment and price line of the menu, and nothing hidden', function (): void {
    $this->seed(DatabaseSeeder::class);

    $graph = structuredGraph($this->get(route('treatments'))->assertOk());
    $services = nodesOfType($graph, 'Service');
    $offers = array_merge(...array_map(fn (array $service): array => $service['offers'], $services));
    [$pause] = array_values(array_filter($services, fn (array $service): bool => $service['name'] === 'Pause essentielle'));

    expect($services)->toHaveCount(17)
        ->and($offers)->toHaveCount(19)
        ->and($pause['provider'])->toBe(['@id' => 'https://racines-lumiere.fr/#institute'])
        ->and($pause['category'])->toBe('Nos rituels corps')
        ->and($pause['offers'][0])->toMatchArray(['@type' => 'Offer', 'price' => '95.00', 'priceCurrency' => 'EUR'])
        ->and(array_column($services, 'name'))->not->toContain('Maillot brésilien');
});

it('places the menu under the home page in the breadcrumb trail', function (): void {
    [$trail] = nodesOfType(structuredGraph($this->get(route('treatments'))), 'BreadcrumbList');

    expect($trail['itemListElement'])->toBe([
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil', 'item' => 'https://racines-lumiere.fr/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Nos soins', 'item' => 'https://racines-lumiere.fr/nos-soins'],
    ]);
});

it('cannot be closed early by a text of the menu', function (): void {
    $category = TreatmentCategory::factory()->create();
    $treatment = Treatment::factory()->create(['treatment_category_id' => $category->id, 'name' => 'Soin</script><script>alert(1)</script>']);
    TreatmentVariant::factory()->create(['treatment_id' => $treatment->id]);

    $response = $this->get(route('treatments'))->assertOk();

    expect($response->getContent())->not->toContain('<script>alert(1)')
        ->and(nodesOfType(structuredGraph($response), 'Service')[0]['name'])->toBe('Soin</script><script>alert(1)</script>');
});
