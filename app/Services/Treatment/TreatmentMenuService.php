<?php

declare(strict_types=1);

namespace App\Services\Treatment;

use App\Data\Treatment\FeaturedCategoryData;
use App\Data\Treatment\TreatmentCategoryData;
use App\Data\Treatment\TreatmentData;
use App\Data\Treatment\TreatmentGroupData;
use App\Data\Treatment\TreatmentMenuData;
use App\Data\Treatment\TreatmentVariantData;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentVariant;
use App\Services\Typography\TypographyService;
use App\ValueObjects\Treatment\Duration;
use App\ValueObjects\Treatment\Price;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as SupportCollection;

class TreatmentMenuService
{
    public function __construct(private readonly TypographyService $typography) {}

    /**
     * The visible categories marked for the home page preview, in their display order.
     *
     * @return list<FeaturedCategoryData>
     */
    public function featuredCategories(): array
    {
        return TreatmentCategory::query()
            ->where('is_visible', true)
            ->where('is_featured', true)
            ->orderBy('position')
            ->get(['slug', 'name', 'subtitle'])
            ->map(fn (TreatmentCategory $category): FeaturedCategoryData => new FeaturedCategoryData(
                slug: $category->slug,
                name: $this->typography->withFrenchSpacing($category->name),
                subtitle: $this->spaced($category->subtitle),
            ))
            ->values()
            ->all();
    }

    /**
     * The visible menu in its display order, numbered and grouped as its page presents it.
     */
    public function visibleMenu(): TreatmentMenuData
    {
        $groups = ['signature' => [], 'featured' => [], 'additional' => []];

        foreach ($this->visibleCategories()->values() as $index => $category) {
            $group = match (true) {
                $category->is_signature => 'signature',
                $category->is_featured => 'featured',
                default => 'additional',
            };
            $groups[$group][] = $this->categoryData($category, $index + 1);
        }

        return new TreatmentMenuData(
            signatureCategories: $groups['signature'],
            featuredCategories: $groups['featured'],
            additionalCategories: $groups['additional'],
        );
    }

    /**
     * The visible categories with their visible treatments and price lines: a treatment shows only with at least
     * one visible price line.
     *
     * @return Collection<int, TreatmentCategory>
     */
    private function visibleCategories(): Collection
    {
        return TreatmentCategory::query()
            ->where('is_visible', true)
            ->orderBy('position')
            ->with([
                'treatments' => fn (HasMany $treatments): HasMany => $treatments
                    ->where('is_visible', true)
                    ->whereHas('variants', fn (Builder $variants): Builder => $variants->where('is_visible', true))
                    ->orderBy('position')
                    ->select(['id', 'treatment_category_id', 'name', 'subtitle', 'description', 'group_label']),
                'treatments.variants' => fn (HasMany $variants): HasMany => $variants
                    ->where('is_visible', true)
                    ->orderBy('position')
                    ->select(['treatment_id', 'label', 'total_duration_minutes', 'care_duration_minutes', 'price_cents']),
            ])
            ->get(['id', 'slug', 'name', 'subtitle', 'description', 'is_signature', 'is_featured']);
    }

    private function categoryData(TreatmentCategory $category, int $number): TreatmentCategoryData
    {
        $paragraphs = $category->description === null ? [] : preg_split('/\R{2,}/', trim($category->description));
        $treatments = $category->treatments->map($this->treatmentData(...))->values()->all();

        return new TreatmentCategoryData(
            number: $number,
            slug: $category->slug,
            name: $this->typography->withFrenchSpacing($category->name),
            subtitle: $this->spaced($category->subtitle),
            descriptionParagraphs: array_map($this->typography->withFrenchSpacing(...), $paragraphs),
            isFeatured: $category->is_featured,
            treatments: $treatments,
            treatmentGroups: $this->groupsOf($treatments),
        );
    }

    /**
     * The treatments gathered by consecutive group label, or none when no treatment has a label.
     *
     * @param  list<TreatmentData>  $treatments
     * @return list<TreatmentGroupData>
     */
    private function groupsOf(array $treatments): array
    {
        $treatments = collect($treatments);

        if (! $treatments->contains(fn (TreatmentData $treatment): bool => $treatment->groupLabel !== null)) {
            return [];
        }

        return $treatments
            ->chunkWhile(fn (TreatmentData $treatment, int $index, SupportCollection $group): bool => $treatment->groupLabel === $group->last()->groupLabel)
            ->map(fn (SupportCollection $group): TreatmentGroupData => new TreatmentGroupData($group->first()->groupLabel, $group->values()->all()))
            ->values()
            ->all();
    }

    private function treatmentData(Treatment $treatment): TreatmentData
    {
        return new TreatmentData(
            name: $this->typography->withFrenchSpacing($treatment->name),
            subtitle: $this->spaced($treatment->subtitle),
            description: $this->spaced($treatment->description),
            groupLabel: $treatment->group_label,
            variants: $treatment->variants->map($this->variantData(...))->values()->all(),
        );
    }

    private function variantData(TreatmentVariant $variant): TreatmentVariantData
    {
        return new TreatmentVariantData(
            label: $this->spaced($variant->label),
            totalDuration: $variant->total_duration_minutes === null ? null : new Duration($variant->total_duration_minutes),
            careDuration: $variant->care_duration_minutes === null ? null : new Duration($variant->care_duration_minutes),
            price: new Price($variant->price_cents),
        );
    }

    /**
     * An optional text with French spacing, or null when there is none.
     */
    private function spaced(?string $text): ?string
    {
        return $text === null ? null : $this->typography->withFrenchSpacing($text);
    }
}
