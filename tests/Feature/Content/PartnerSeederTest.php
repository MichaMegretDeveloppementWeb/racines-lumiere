<?php

declare(strict_types=1);

use App\Models\Partner;
use Database\Seeders\Content\PartnerSeeder;

/**
 * Synthetic partner rows, as the seeder receives them.
 *
 * @return list<array<string, mixed>>
 */
function syntheticPartners(string $prefix, int $count): array
{
    return array_map(fn (int $index): array => [
        'slug' => "{$prefix}-partner-{$index}",
        'name' => "Partner {$index}",
        'organization_name' => null,
        'specialty' => 'A specialty',
        'town' => 'A town',
        'website_url' => 'https://example.com/',
        'position' => $index * 10,
        'is_visible' => false,
    ], range(1, $count));
}

it('publishes the five practitioners of annex D whose consent is confirmed', function (): void {
    $this->seed(PartnerSeeder::class);

    expect(Partner::query()->orderBy('position')->pluck('slug')->all())->toBe([
        'alice-peillex', 'julie-deage-martinez', 'camille-gouyon', 'marie-christine-gosetto', 'joelle-plantaz',
    ])
        ->and(Partner::query()->where('is_visible', true)->count())->toBe(5)
        ->and(Partner::query()->where('slug', 'camille-gouyon')->value('specialty'))->toBe('Énergéticienne · ostéo douce')
        ->and(Partner::query()->where('slug', 'marie-christine-gosetto')->value('specialty'))->toBe('Énergéticienne · médiumnité')
        ->and(Partner::query()->where('slug', 'joelle-plantaz')->value('organization_name'))->toBe('Jojo les Bas Bleus');
});

it('replays the partners without duplicates and restores altered values', function (): void {
    $this->seed(PartnerSeeder::class);
    Partner::query()->where('slug', 'alice-peillex')->update(['specialty' => 'Altered', 'is_visible' => false]);

    $this->seed(PartnerSeeder::class);

    expect(Partner::query()->count())->toBe(5)
        ->and(Partner::query()->where('is_visible', true)->count())->toBe(5)
        ->and(Partner::query()->where('slug', 'alice-peillex')->value('specialty'))->toBe('Kinésiologue');
});

it('writes the partners in one query, whatever their number', function (): void {
    $seeder = new PartnerSeeder;

    $forTen = queryCount(fn () => $seeder->writePartners(syntheticPartners('small', 10)));
    $forHundred = queryCount(fn () => $seeder->writePartners(syntheticPartners('large', 100)));

    expect($forTen)->toBe(1)->and($forHundred)->toBe($forTen);
});

it('writes the same partner columns as an ordinary save', function (): void {
    [$row] = syntheticPartners('upserted', 1);
    (new PartnerSeeder)->writePartners([$row]);

    $saved = (new Partner)->forceFill(array_merge($row, ['slug' => 'saved-partner']));
    $saved->save();

    expect(rowColumns('partners', Partner::query()->where('slug', 'upserted-partner-1')->value('id')))
        ->toBe(rowColumns('partners', $saved->id));
});
