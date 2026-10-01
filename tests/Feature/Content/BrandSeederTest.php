<?php

declare(strict_types=1);

use App\Models\Brand;
use Database\Seeders\Content\BrandSeeder;

/**
 * Synthetic brand rows, as the seeder receives them.
 *
 * @return list<array<string, mixed>>
 */
function syntheticBrands(string $prefix, int $count): array
{
    return array_map(fn (int $index): array => [
        'slug' => "{$prefix}-brand-{$index}",
        'name' => "Brand {$index}",
        'tagline' => 'A tagline',
        'long_text' => "First paragraph.\n\nSecond paragraph.",
        'short_text' => null,
        'role_text' => 'A role',
        'logo_path' => null,
        'products_url' => null,
        'position' => $index * 10,
        'is_visible' => true,
    ], range(1, $count));
}

it('seeds the seven partner brands of annex C', function (): void {
    $this->seed(BrandSeeder::class);

    expect(Brand::query()->orderBy('position')->pluck('slug')->all())->toBe([
        'altearah-bio', 'comfort-zone', 'labote', 'gingerly', 'ilse', 'skin-diligent', 'demain-beauty',
    ])
        ->and(Brand::query()->where('slug', 'comfort-zone')->value('tagline'))->toBe('Conscious. Skin. Science.')
        ->and(explode("\n\n", Brand::query()->where('slug', 'altearah-bio')->value('long_text')))->toHaveCount(3)
        ->and(Brand::query()->whereNotNull('products_url')->exists())->toBeFalse()
        ->and(Brand::query()->where('is_visible', false)->exists())->toBeFalse();
});

it('replays the brands without duplicates and restores altered values', function (): void {
    $this->seed(BrandSeeder::class);
    Brand::query()->where('slug', 'labote')->update(['name' => 'Altered']);

    $this->seed(BrandSeeder::class);

    expect(Brand::query()->count())->toBe(7)
        ->and(Brand::query()->where('slug', 'labote')->value('name'))->toBe('Laboté');
});

it('writes the brands in one query, whatever their number', function (): void {
    $seeder = new BrandSeeder;

    $forTen = queryCount(fn () => $seeder->writeBrands(syntheticBrands('small', 10)));
    $forHundred = queryCount(fn () => $seeder->writeBrands(syntheticBrands('large', 100)));

    expect($forTen)->toBe(1)->and($forHundred)->toBe($forTen);
});

it('writes the same brand columns as an ordinary save', function (): void {
    [$row] = syntheticBrands('upserted', 1);
    (new BrandSeeder)->writeBrands([$row]);

    $saved = (new Brand)->forceFill(array_merge($row, ['slug' => 'saved-brand']));
    $saved->save();

    expect(rowColumns('brands', Brand::query()->where('slug', 'upserted-brand-1')->value('id')))
        ->toBe(rowColumns('brands', $saved->id));
});
