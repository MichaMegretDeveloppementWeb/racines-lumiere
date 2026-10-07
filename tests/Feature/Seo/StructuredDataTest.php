<?php

declare(strict_types=1);

use App\Models\Partner;
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

/**
 * The node that describes the page itself, whatever its type.
 *
 * @param  list<array<string, mixed>>  $graph
 * @return array<string, mixed>
 */
function pageNode(array $graph): array
{
    $pages = array_values(array_filter($graph, fn (array $node): bool => str_ends_with((string) ($node['@id'] ?? ''), '#webpage')));

    expect($pages)->toHaveCount(1);

    return $pages[0];
}

/**
 * The treatments of a catalogue, however deep, with the name of the catalogue that lists each of them.
 *
 * @param  array<string, mixed>  $catalog
 * @return list<array<string, mixed>>
 */
function catalogEntries(array $catalog): array
{
    return array_merge(...array_map(
        fn (array $entry): array => $entry['@type'] === 'OfferCatalog' ? catalogEntries($entry) : [[...$entry, 'listedUnder' => $catalog['name']]],
        $catalog['itemListElement'],
    ));
}

/**
 * The treatments of the menu's catalogue, with the name of the category or group that lists each of them.
 *
 * @param  list<array<string, mixed>>  $graph
 * @return list<array<string, mixed>>
 */
function catalogServices(array $graph): array
{
    return catalogEntries(nodesOfType($graph, 'OfferCatalog')[0]);
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
        ->and($institute['email'])->toBe(config('institute.contact.email'))
        ->and($institute['founder'])->toBe([
            ['@type' => 'Person', 'name' => 'Aurore', 'jobTitle' => 'Co-fondatrice'],
            ['@type' => 'Person', 'name' => 'Lorie', 'jobTitle' => 'Co-fondatrice'],
        ])
        ->and($institute['address'])->toMatchArray(['streetAddress' => '205 avenue des Charmes', 'postalCode' => '74140', 'addressLocality' => 'Sciez', 'addressCountry' => 'FR'])
        ->and($institute['potentialAction'])->toMatchArray(['@type' => 'ReserveAction', 'target' => config('institute.booking_url')])
        ->and($institute['geo'])->toBe(['@type' => 'GeoCoordinates', 'latitude' => 46.330093, 'longitude' => 6.375758])
        ->and($institute)->not->toHaveKeys(['openingHoursSpecification', 'sameAs']);
})->with(['home' => ['home'], 'treatment menu' => ['treatments'], 'story' => ['story'], 'a page in preparation' => ['contact']]);

it('links the institute to the home page only when that page is described in the graph', function (string $route, bool $isHome): void {
    $graph = structuredGraph($this->get(route($route))->assertOk());
    [$institute] = nodesOfType($graph, 'BeautySalon');

    if ($isHome) {
        expect($institute['mainEntityOfPage'])->toBe(['@id' => pageNode($graph)['@id']]);
    } else {
        expect($institute)->not->toHaveKey('mainEntityOfPage');
    }
})->with([
    'home' => ['home', true],
    'treatment menu' => ['treatments', false],
    'story' => ['story', false],
    'a page in preparation' => ['contact', false],
]);

it('identifies the institute described in the graph as the subject of the story', function (): void {
    $graph = structuredGraph($this->get(route('story'))->assertOk());
    [$institute] = nodesOfType($graph, 'BeautySalon');
    [$page] = nodesOfType($graph, 'AboutPage');

    expect($page['about'])->toBe(['@id' => $institute['@id']]);
});

it('connects the contact page to the institute and its visible contact details', function (?string $phone): void {
    config([
        'institute.contact.email' => 'bonjour@example.com',
        'institute.contact.form_recipient' => 'private@example.com',
        'institute.contact.phone' => $phone,
    ]);
    $response = $this->get(route('contact'))->assertOk();
    $graph = structuredGraph($response);
    [$institute] = nodesOfType($graph, 'BeautySalon');
    [$page] = nodesOfType($graph, 'ContactPage');

    expect($page['mainEntity'])->toBe(['@id' => $institute['@id']]);
    expect($page)->not->toHaveKeys(['email', 'telephone', 'contactPoint']);
    expect($institute['contactPoint'])->toMatchArray([
        '@type' => 'ContactPoint',
        '@id' => 'https://racines-lumiere.fr/#contact-point',
        'url' => 'https://racines-lumiere.fr/contact',
        'email' => 'bonjour@example.com',
        'contactType' => 'Renseignements sur les soins',
        'availableLanguage' => 'fr',
    ]);
    $response->assertSee('href="mailto:bonjour@example.com"', false)->assertDontSee('private@example.com');

    if ($phone === null) {
        expect($institute)->not->toHaveKey('telephone');
        expect($institute['contactPoint'])->not->toHaveKey('telephone');
        $response->assertDontSee('href="tel:', false);
    } else {
        expect($institute['telephone'])->toBe('+33 4 50 00 00 00');
        expect($institute['contactPoint']['telephone'])->toBe('+33 4 50 00 00 00');
        $response->assertSee('href="tel:0450000000"', false);
    }
})->with(['no confirmed telephone' => [null], 'confirmed telephone' => ['04 50 00 00 00']]);

it('omits geographic coordinates when the address location is incomplete', function (?float $latitude, ?float $longitude): void {
    config(['institute.geo.latitude' => $latitude, 'institute.geo.longitude' => $longitude]);
    [$institute] = nodesOfType(structuredGraph($this->get(route('story'))->assertOk()), 'BeautySalon');

    expect($institute)->not->toHaveKey('geo');
})->with([[null, null], [46.330093, null], [null, 6.375758]]);

it('names the site on every page, published by the institute', function (string $route): void {
    [$site] = nodesOfType(structuredGraph($this->get(route($route))->assertOk()), 'WebSite');

    expect($site)->toBe([
        '@type' => 'WebSite',
        '@id' => 'https://racines-lumiere.fr/#website',
        'url' => 'https://racines-lumiere.fr/',
        'name' => 'Racines & Lumière',
        'alternateName' => 'Racines et Lumière',
        'inLanguage' => 'fr-FR',
        'publisher' => ['@id' => 'https://racines-lumiere.fr/#institute'],
    ]);
})->with(['home' => ['home'], 'a page in preparation' => ['legal.notice']]);

it('describes each page as a page of the site, of its kind, with its title and description', function (string $route, string $type, string $url, string $title): void {
    $page = pageNode(structuredGraph($this->get('http://www.example.test'.route($route, absolute: false))->assertOk()));

    expect($page)->toMatchArray([
        '@type' => $type,
        '@id' => $url.'#webpage',
        'url' => $url,
        'name' => $title,
        'isPartOf' => ['@id' => 'https://racines-lumiere.fr/#website'],
        'inLanguage' => 'fr-FR',
    ])->and($page['description'])->toBeString()->not->toBeEmpty();

    if ($type !== 'AboutPage') {
        expect($page)->not->toHaveKey('about');
    }
})->with([
    'home' => ['home', 'WebPage', 'https://racines-lumiere.fr/', 'Racines & Lumière · Institut de beauté holistique à Sciez'],
    'treatment menu' => ['treatments', 'WebPage', 'https://racines-lumiere.fr/nos-soins', 'Carte des soins et tarifs à Sciez · Racines & Lumière'],
    'story' => ['story', 'AboutPage', 'https://racines-lumiere.fr/notre-histoire', 'Aurore et Lorie, institut holistique à Sciez · Racines & Lumière'],
    'contact' => ['contact', 'ContactPage', 'https://racines-lumiere.fr/contact', 'Nous trouver et nous écrire à Sciez · Racines & Lumière'],
]);

it('places every inner page under the home page in the breadcrumb trail', function (string $route, string $name): void {
    Partner::factory()->create(['is_visible' => true]);
    $url = 'https://racines-lumiere.fr'.route($route, absolute: false);

    $response = $this->get(route($route))->assertOk();
    $graph = structuredGraph($response);
    [$trail] = nodesOfType($graph, 'BreadcrumbList');
    $page = pageNode($graph);

    expect($trail['@id'])->toBe($url.'#breadcrumb')
        ->and($page['breadcrumb'])->toBe(['@id' => $url.'#breadcrumb'])
        ->and($trail['itemListElement'])->toBe([
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil', 'item' => 'https://racines-lumiere.fr/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $name, 'item' => $url],
        ]);

    if (in_array($route, ['contact', 'brands', 'treatments', 'story', 'trusted-circle'], true)) {
        $response->assertSee('aria-label="Fil d’Ariane"', false)
            ->assertSee('<li aria-current="page">'.$name.'</li>', false);
    }
})->with([
    'treatment menu' => ['treatments', 'Nos soins'],
    'partner brands' => ['brands', 'Nos marques partenaires'],
    'story' => ['story', 'Notre histoire'],
    'trusted circle' => ['trusted-circle', 'Cercle de confiance'],
    'contact' => ['contact', 'Contact'],
    'legal notice' => ['legal.notice', 'Mentions légales'],
    'privacy policy' => ['legal.privacy', 'Politique de confidentialité'],
]);

it('gives the home page no breadcrumb trail', function (): void {
    $graph = structuredGraph($this->get(route('home'))->assertOk());

    expect(nodesOfType($graph, 'BreadcrumbList'))->toBe([])
        ->and(nodesOfType($graph, 'WebPage')[0])->not->toHaveKey('breadcrumb');
});

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

it('marks up the menu as a catalogue of its categories, treatments and price lines, and nothing hidden', function (): void {
    $this->seed(DatabaseSeeder::class);
    $hidden = Treatment::factory()->create(['treatment_category_id' => TreatmentCategory::query()->value('id'), 'name' => 'Soin masqué', 'is_visible' => false]);
    TreatmentVariant::factory()->create(['treatment_id' => $hidden->id]);

    $graph = structuredGraph($this->get(route('treatments'))->assertOk());
    [$catalog] = nodesOfType($graph, 'OfferCatalog');
    $services = catalogServices($graph);
    [$pause] = array_values(array_filter($services, fn (array $service): bool => $service['name'] === 'Pause essentielle'));
    [$kobido] = array_values(array_filter($services, fn (array $service): bool => $service['name'] === 'Kobido'));

    expect($catalog)->toMatchArray([
        '@id' => 'https://racines-lumiere.fr/nos-soins#catalog',
        'name' => 'Carte des soins',
        'mainEntityOfPage' => ['@id' => 'https://racines-lumiere.fr/nos-soins#webpage'],
    ])
        ->and(array_column($catalog['itemListElement'], 'name'))->toBe([
            'Nos rituels corps', 'Notre rituel Visage & Âme', 'Nos rituels complets Corps & Visage',
            'Nos traitements visage', 'Nos singuliers', "Les suppléments d'Âme", "L'art de l'épilation",
        ])
        ->and($services)->toHaveCount(43)
        ->and(array_merge(...array_column($services, 'offers')))->toHaveCount(45)
        ->and($pause['listedUnder'])->toBe('Nos rituels corps')
        ->and($pause['provider'])->toBe(['@id' => 'https://racines-lumiere.fr/#institute'])
        ->and($pause['offers'][0])->toBe(['@type' => 'Offer', 'description' => "1h15 dont 45\u{00A0}min de soin", 'price' => '95.00', 'priceCurrency' => 'EUR'])
        ->and(array_column($kobido['offers'], 'description'))->toBe(['1h', '1h30 avec soin visage'])
        ->and(array_column($services, 'name'))->toContain('Maillot brésilien')
        ->and(array_column($services, 'name'))->not->toContain('Soin masqué');
});

it('lists the waxing in its groups, as the menu folds them', function (): void {
    $this->seed(DatabaseSeeder::class);

    $graph = structuredGraph($this->get(route('treatments'))->assertOk());
    [$catalog] = nodesOfType($graph, 'OfferCatalog');
    [$waxing] = array_values(array_filter($catalog['itemListElement'], fn (array $category): bool => $category['name'] === "L'art de l'épilation"));
    $underarms = array_values(array_filter(catalogServices($graph), fn (array $service): bool => $service['name'] === 'Aisselles'));

    expect(array_column($waxing['itemListElement'], '@type'))->toBe(['OfferCatalog', 'OfferCatalog', 'OfferCatalog'])
        ->and(array_column($waxing['itemListElement'], 'name'))->toBe(['Épilations femmes', 'Forfaits femmes', 'Épilations hommes'])
        ->and(array_map(fn (array $service): array => [$service['listedUnder'], $service['offers'][0]['price']], $underarms))
        ->toBe([['Épilations femmes', '15.00'], ['Épilations hommes', '18.00']]);
});

it('leaves out of the catalogue a category that has no visible treatment', function (): void {
    $listed = TreatmentCategory::factory()->create(['name' => 'Rituels', 'position' => 10]);
    TreatmentVariant::factory()->create(['treatment_id' => Treatment::factory()->create(['treatment_category_id' => $listed->id])->id]);
    TreatmentCategory::factory()->create(['name' => 'Sans soin', 'position' => 20]);

    [$catalog] = nodesOfType(structuredGraph($this->get(route('treatments'))->assertOk()), 'OfferCatalog');

    expect(array_column($catalog['itemListElement'], 'name'))->toBe(['Rituels']);
});

it('cannot be closed early by a text of the menu', function (): void {
    $category = TreatmentCategory::factory()->create();
    $treatment = Treatment::factory()->create(['treatment_category_id' => $category->id, 'name' => 'Soin</script><script>alert(1)</script>']);
    TreatmentVariant::factory()->create(['treatment_id' => $treatment->id]);

    $response = $this->get(route('treatments'))->assertOk();

    expect($response->getContent())->not->toContain('<script>alert(1)')
        ->and(catalogServices(structuredGraph($response))[0]['name'])->toBe('Soin</script><script>alert(1)</script>');
});
