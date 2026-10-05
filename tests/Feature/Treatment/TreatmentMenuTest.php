<?php

declare(strict_types=1);

use App\Data\Treatment\TreatmentCategoryData;
use App\Data\Treatment\TreatmentData;
use App\Data\Treatment\TreatmentGroupData;
use App\Data\Treatment\TreatmentMenuData;
use App\Data\Treatment\TreatmentVariantData;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentVariant;
use App\Services\Treatment\TreatmentMenuService;

/**
 * The slugs of the given categories, in their order.
 *
 * @param  list<TreatmentCategoryData>  $categories
 * @return list<string>
 */
function categorySlugs(array $categories): array
{
    return array_map(fn (TreatmentCategoryData $category): string => $category->slug, $categories);
}

/**
 * A visible treatment of the category, with one visible price line.
 */
function treatmentWithPrice(TreatmentCategory $category, string $name, int $position): Treatment
{
    $treatment = Treatment::factory()->create(['treatment_category_id' => $category->id, 'name' => $name, 'position' => $position]);
    TreatmentVariant::factory()->create(['treatment_id' => $treatment->id, 'position' => 10]);

    return $treatment;
}

it('reads the visible categories in their display order, grouped as the page presents them', function (): void {
    TreatmentCategory::factory()->create(['slug' => 'second-ritual', 'is_signature' => true, 'is_featured' => true, 'position' => 20]);
    TreatmentCategory::factory()->create(['slug' => 'first-ritual', 'is_signature' => true, 'is_featured' => true, 'position' => 10]);
    TreatmentCategory::factory()->create(['slug' => 'featured', 'is_featured' => true, 'position' => 30]);
    TreatmentCategory::factory()->create(['slug' => 'additional', 'position' => 40]);
    TreatmentCategory::factory()->create(['slug' => 'hidden-ritual', 'is_signature' => true, 'is_visible' => false, 'position' => 5]);

    $menu = app(TreatmentMenuService::class)->visibleMenu();

    expect($menu)->toBeInstanceOf(TreatmentMenuData::class)
        ->and(categorySlugs($menu->signatureCategories))->toBe(['first-ritual', 'second-ritual'])
        ->and(categorySlugs($menu->featuredCategories))->toBe(['featured'])
        ->and(categorySlugs($menu->additionalCategories))->toBe(['additional'])
        ->and(array_map(fn (TreatmentCategoryData $category): int => $category->number, $menu->categories()))->toBe([1, 2, 3, 4]);
});

it('shows only the treatments and price lines that are visible, in their order', function (): void {
    $category = TreatmentCategory::factory()->create();
    treatmentWithPrice($category, 'Second', 20);
    treatmentWithPrice($category, 'First', 10);
    Treatment::factory()->create(['treatment_category_id' => $category->id, 'name' => 'Hidden', 'is_visible' => false]);
    $withoutVisiblePrice = Treatment::factory()->create(['treatment_category_id' => $category->id, 'name' => 'Without a visible price']);
    TreatmentVariant::factory()->create(['treatment_id' => $withoutVisiblePrice->id, 'is_visible' => false]);
    $kobido = treatmentWithPrice($category, 'Kobido', 30);
    TreatmentVariant::factory()->create(['treatment_id' => $kobido->id, 'label' => 'avec soin visage', 'position' => 20]);
    TreatmentVariant::factory()->create(['treatment_id' => $kobido->id, 'label' => 'hidden line', 'position' => 30, 'is_visible' => false]);

    $treatments = app(TreatmentMenuService::class)->visibleMenu()->additionalCategories[0]->treatments;

    expect(array_map(fn (TreatmentData $treatment): string => $treatment->name, $treatments))->toBe(['First', 'Second', 'Kobido'])
        ->and(array_map(fn (TreatmentVariantData $variant): ?string => $variant->label, $treatments[2]->variants))->toBe([null, 'avec soin visage']);
});

it('groups the treatments of a category by their group label, in their order', function (): void {
    $grouped = TreatmentCategory::factory()->create(['position' => 10]);
    foreach ([['Épilations femmes', 'Sourcils', 10], ['Épilations femmes', 'Lèvres', 20], ['Épilations hommes', 'Dos', 30]] as [$group, $name, $position]) {
        $treatment = treatmentWithPrice($grouped, $name, $position);
        Treatment::query()->whereKey($treatment->id)->update(['group_label' => $group]);
    }
    treatmentWithPrice(TreatmentCategory::factory()->create(['position' => 20]), 'Kobido', 10);

    [$groupedCategory, $plainCategory] = app(TreatmentMenuService::class)->visibleMenu()->additionalCategories;

    expect(array_map(fn (TreatmentGroupData $group): ?string => $group->label, $groupedCategory->treatmentGroups))->toBe(['Épilations femmes', 'Épilations hommes'])
        ->and(array_map(fn (TreatmentData $treatment): string => $treatment->name, $groupedCategory->treatmentGroups[0]->treatments))->toBe(['Sourcils', 'Lèvres'])
        ->and(array_map(fn (TreatmentData $treatment): string => $treatment->name, $groupedCategory->treatments))->toBe(['Sourcils', 'Lèvres', 'Dos'])
        ->and($plainCategory->treatmentGroups)->toBe([]);
});

it('keeps a category that has no visible treatment, its description split into paragraphs', function (): void {
    TreatmentCategory::factory()->create(['description' => "Une peau douce et nette.\n\nRetrouvez nos tarifs en ligne."]);

    $category = app(TreatmentMenuService::class)->visibleMenu()->additionalCategories[0];

    expect($category->treatments)->toBe([])
        ->and($category->descriptionParagraphs)->toBe(['Une peau douce et nette.', 'Retrouvez nos tarifs en ligne.']);
});

it('gives each price line its durations and its price', function (): void {
    $category = TreatmentCategory::factory()->create();
    $ritual = treatmentWithPrice($category, 'Pause essentielle', 10);
    $ritual->variants()->update(['total_duration_minutes' => 75, 'care_duration_minutes' => 45, 'price_cents' => 9500]);
    $supplement = Treatment::factory()->create(['treatment_category_id' => $category->id, 'position' => 20]);
    TreatmentVariant::factory()->create(['treatment_id' => $supplement->id, 'total_duration_minutes' => null, 'price_cents' => 1000]);

    [$ritualLine, $supplementLine] = array_map(
        fn (TreatmentData $treatment): TreatmentVariantData => $treatment->variants[0],
        app(TreatmentMenuService::class)->visibleMenu()->additionalCategories[0]->treatments,
    );

    expect($ritualLine->totalDuration?->label())->toBe('1h15')
        ->and($ritualLine->careDuration?->label())->toBe("45\u{00A0}min")
        ->and($ritualLine->price->label())->toBe("95\u{00A0}€")
        ->and($supplementLine->totalDuration)->toBeNull()
        ->and($supplementLine->careDuration)->toBeNull()
        ->and($supplementLine->price->label())->toBe("10\u{00A0}€");
});

it('applies French spacing to the texts of the menu', function (): void {
    $category = TreatmentCategory::factory()->create(['description' => 'Pour sublimer votre soin : une attention en plus.']);
    $treatment = treatmentWithPrice($category, 'Lèvres', 10);
    Treatment::query()->whereKey($treatment->id)->update(['description' => 'Un massage dédié : vos lèvres retrouvent leur éclat.']);

    $category = app(TreatmentMenuService::class)->visibleMenu()->additionalCategories[0];

    expect($category->descriptionParagraphs[0])->toBe("Pour sublimer votre soin\u{00A0}: une attention en plus.")
        ->and($category->treatments[0]->description)->toBe("Un massage dédié\u{00A0}: vos lèvres retrouvent leur éclat.");
});

it('reads the whole menu in three queries, whatever its size', function (int $treatmentCount): void {
    foreach (range(1, 2) as $categoryNumber) {
        $category = TreatmentCategory::factory()->create(['is_signature' => $categoryNumber === 1]);
        foreach (range(1, $treatmentCount) as $position) {
            treatmentWithPrice($category, "Soin {$position}", $position * 10);
        }
    }

    // Categories, then their visible treatments, then their visible price lines: one query each.
    expect(queryCount(fn () => app(TreatmentMenuService::class)->visibleMenu()))->toBe(3);
})->with(['two treatments per category' => [2], 'twenty treatments per category' => [20]]);
