<?php

declare(strict_types=1);

use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentVariant;
use Database\Seeders\Content\TreatmentMenuSeeder;
use Illuminate\Support\Facades\DB;

/**
 * A synthetic menu: one category for every five treatments, two price lines per treatment.
 *
 * @return array{0: list<array<string, mixed>>, 1: list<array<string, mixed>>}
 */
function syntheticMenu(string $prefix, int $treatmentCount): array
{
    $categories = array_map(fn (int $index): array => [
        'slug' => "{$prefix}-category-{$index}",
        'name' => "Category {$index}",
        'subtitle' => null,
        'description' => null,
        'is_signature' => false,
        'is_featured' => false,
        'booking_url' => null,
        'position' => $index * 10,
        'is_visible' => true,
    ], range(1, intdiv($treatmentCount, 5)));

    $treatments = array_map(fn (int $index): array => [
        'category' => "{$prefix}-category-".(intdiv($index - 1, 5) + 1),
        'slug' => "{$prefix}-treatment-{$index}",
        'name' => "Treatment {$index}",
        'subtitle' => null,
        'description' => null,
        'group_label' => null,
        'position' => $index * 10,
        'is_visible' => true,
        'variants' => [
            ['label' => null, 'total_duration_minutes' => 60, 'care_duration_minutes' => 30, 'price_cents' => 9000, 'position' => 10, 'is_visible' => true],
            ['label' => 'long', 'total_duration_minutes' => 90, 'care_duration_minutes' => null, 'price_cents' => 12000, 'position' => 20, 'is_visible' => true],
        ],
    ], range(1, $treatmentCount));

    return [$categories, $treatments];
}

/**
 * Count the price lines a visitor can see: visible line, visible treatment, visible category.
 */
function visibleVariantCount(): int
{
    return TreatmentVariant::query()
        ->join('treatments', 'treatments.id', '=', 'treatment_variants.treatment_id')
        ->join('treatment_categories', 'treatment_categories.id', '=', 'treatments.treatment_category_id')
        ->where('treatment_variants.is_visible', true)
        ->where('treatments.is_visible', true)
        ->where('treatment_categories.is_visible', true)
        ->count();
}

it('seeds the treatment menu of annex B', function (): void {
    $this->seed(TreatmentMenuSeeder::class);

    expect(TreatmentCategory::query()->orderBy('position')->pluck('slug')->all())->toBe([
        'rituels-corps', 'rituel-visage-et-ame', 'rituels-complets', 'traitements-visage',
        'singuliers', 'supplements-d-ame', 'epilation',
    ])
        ->and(Treatment::query()->count())->toBe(43)
        ->and(TreatmentVariant::query()->count())->toBe(45)
        ->and(visibleVariantCount())->toBe(45)
        ->and(Treatment::query()->where('is_visible', true)->count())->toBe(43);
});

it('gives every signature ritual exactly thirty more minutes than its care time', function (): void {
    $this->seed(TreatmentMenuSeeder::class);

    $rituals = TreatmentVariant::query()
        ->join('treatments', 'treatments.id', '=', 'treatment_variants.treatment_id')
        ->join('treatment_categories', 'treatment_categories.id', '=', 'treatments.treatment_category_id')
        ->where('treatment_categories.is_signature', true)
        ->get(['treatment_variants.total_duration_minutes', 'treatment_variants.care_duration_minutes']);

    expect($rituals)->toHaveCount(8);
    $rituals->each(fn (TreatmentVariant $ritual) => expect(
        $ritual->total_duration_minutes - $ritual->care_duration_minutes
    )->toBe(30));
});

it('shows every waxing treatment in its group', function (): void {
    $this->seed(TreatmentMenuSeeder::class);

    $waxingCategoryId = TreatmentCategory::query()->where('slug', 'epilation')->value('id');
    $waxing = Treatment::query()->where('treatment_category_id', $waxingCategoryId)->get();

    expect($waxing)->toHaveCount(26)
        ->and($waxing->where('is_visible', false))->toBeEmpty()
        ->and($waxing->countBy('group_label')->all())->toBe(['Épilations femmes' => 12, 'Forfaits femmes' => 7, 'Épilations hommes' => 7])
        ->and(TreatmentCategory::query()->where('slug', 'epilation')->value('is_visible'))->toBeTrue();
});

it('describes every visible treatment outside a group', function (): void {
    $this->seed(TreatmentMenuSeeder::class);

    $undescribed = Treatment::query()->where('is_visible', true)->whereNull('group_label')->whereNull('description')->pluck('slug');

    expect($undescribed->all())->toBe([]);
});

it('records the two price lines of the Kobido', function (): void {
    $this->seed(TreatmentMenuSeeder::class);

    $kobidoId = Treatment::query()->where('slug', 'kobido')->value('id');
    $lines = TreatmentVariant::query()->where('treatment_id', $kobidoId)->orderBy('position')
        ->get(['label', 'total_duration_minutes', 'care_duration_minutes', 'price_cents'])
        ->toArray();

    expect($lines)->toBe([
        ['label' => null, 'total_duration_minutes' => 60, 'care_duration_minutes' => null, 'price_cents' => 12000],
        ['label' => 'avec soin visage', 'total_duration_minutes' => 90, 'care_duration_minutes' => null, 'price_cents' => 15000],
    ]);
});

it('replays without duplicates, restores altered values and deletes nothing', function (): void {
    $this->seed(TreatmentMenuSeeder::class);
    $unlisted = TreatmentCategory::factory()->create();

    Treatment::query()->where('slug', 'kobido')->update(['name' => 'Altered']);
    TreatmentVariant::query()->where('price_cents', 9500)->update(['price_cents' => 1]);

    $this->seed(TreatmentMenuSeeder::class);

    expect(TreatmentCategory::query()->count())->toBe(8)
        ->and(Treatment::query()->count())->toBe(43)
        ->and(TreatmentVariant::query()->count())->toBe(45)
        ->and(Treatment::query()->where('slug', 'kobido')->value('name'))->toBe('Kobido')
        ->and(TreatmentVariant::query()->where('price_cents', 9500)->exists())->toBeTrue()
        ->and($unlisted->fresh())->not->toBeNull();
});

it('writes the menu in five queries, whatever its size', function (): void {
    $seeder = new TreatmentMenuSeeder;

    // Three upserts (categories, treatments, price lines) and two slug-to-id lookups.
    $forTen = queryCount(fn () => $seeder->writeMenu(...syntheticMenu('small', 10)));
    $forHundred = queryCount(fn () => $seeder->writeMenu(...syntheticMenu('large', 100)));

    expect($forTen)->toBe(5)
        ->and($forHundred)->toBe($forTen)
        ->and(Treatment::query()->count())->toBe(110)
        ->and(TreatmentVariant::query()->count())->toBe(220);
});

it('writes the same columns as an ordinary save', function (): void {
    [$categories, $treatments] = syntheticMenu('upserted', 5);
    $treatments = [$treatments[0]];
    (new TreatmentMenuSeeder)->writeMenu($categories, $treatments);

    $category = (new TreatmentCategory)->forceFill(array_merge($categories[0], ['slug' => 'saved-category']));
    $category->save();
    $treatment = (new Treatment)->forceFill(array_merge(
        array_diff_key($treatments[0], array_flip(['category', 'variants'])),
        ['slug' => 'saved-treatment', 'treatment_category_id' => $category->id],
    ));
    $treatment->save();
    $variant = (new TreatmentVariant)->forceFill(array_merge($treatments[0]['variants'][0], ['treatment_id' => $treatment->id]));
    $variant->save();

    $upsertedCategoryId = TreatmentCategory::query()->where('slug', 'upserted-category-1')->value('id');
    $upsertedTreatmentId = Treatment::query()->where('slug', 'upserted-treatment-1')->value('id');
    $upsertedVariantId = TreatmentVariant::query()->where('treatment_id', $upsertedTreatmentId)->where('position', 10)->value('id');

    expect(rowColumns('treatment_categories', $upsertedCategoryId))->toBe(rowColumns('treatment_categories', $category->id))
        ->and(rowColumns('treatments', $upsertedTreatmentId, ['id', 'slug', 'treatment_category_id']))
        ->toBe(rowColumns('treatments', $treatment->id, ['id', 'slug', 'treatment_category_id']))
        ->and(rowColumns('treatment_variants', $upsertedVariantId, ['id', 'treatment_id']))
        ->toBe(rowColumns('treatment_variants', $variant->id, ['id', 'treatment_id']));

    expect(DB::table('treatments')->where('id', $upsertedTreatmentId)->value('treatment_category_id'))->toBe($upsertedCategoryId);
});
