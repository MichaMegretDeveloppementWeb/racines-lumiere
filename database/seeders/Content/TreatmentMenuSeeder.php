<?php

declare(strict_types=1);

namespace Database\Seeders\Content;

use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * The treatment menu: categories, treatments and their price lines.
 *
 * @phpstan-type CategoryRow array{slug: string, name: string, subtitle: ?string, description: ?string, is_signature: bool, is_featured: bool, booking_url: ?string, position: int, is_visible: bool}
 * @phpstan-type VariantRow array{label: ?string, total_duration_minutes: ?int, care_duration_minutes: ?int, price_cents: int, position: int, is_visible: bool}
 * @phpstan-type TreatmentRow array{category: string, slug: string, name: string, subtitle: ?string, description: ?string, group_label: ?string, position: int, is_visible: bool, variants: list<VariantRow>}
 */
class TreatmentMenuSeeder extends Seeder
{
    use BuildsRows;

    private const array CATEGORY_UPDATED_COLUMNS = [
        'name', 'subtitle', 'description', 'is_signature', 'is_featured', 'booking_url', 'position', 'is_visible',
    ];

    private const array TREATMENT_UPDATED_COLUMNS = [
        'treatment_category_id', 'name', 'subtitle', 'description', 'group_label', 'position', 'is_visible',
    ];

    private const array VARIANT_UPDATED_COLUMNS = [
        'label', 'total_duration_minutes', 'care_duration_minutes', 'price_cents', 'is_visible',
    ];

    /**
     * Seed the menu of the institute.
     */
    public function run(): void
    {
        $this->writeMenu($this->categories(), [...$this->treatments(), ...$this->waxingTreatments()]);
    }

    /**
     * Write the menu in five queries: each upsert is followed by the slug lookup the next one needs.
     *
     * @param  list<CategoryRow>  $categories
     * @param  list<TreatmentRow>  $treatments
     */
    public function writeMenu(array $categories, array $treatments): void
    {
        $categoryIds = $this->writeCategories($categories, array_column($treatments, 'category'));
        $treatmentIds = $this->writeTreatments($treatments, $categoryIds);
        $this->writeVariants($treatments, $treatmentIds);
    }

    /**
     * Upsert the categories, then read back the ids of those the treatments refer to.
     *
     * @param  list<CategoryRow>  $categories
     * @param  list<string>  $referencedSlugs
     * @return Collection<string, int>
     */
    private function writeCategories(array $categories, array $referencedSlugs): Collection
    {
        TreatmentCategory::query()->upsert(
            $this->rowsFor(TreatmentCategory::class, $categories),
            uniqueBy: ['slug'],
            update: self::CATEGORY_UPDATED_COLUMNS,
        );

        return TreatmentCategory::query()->whereIn('slug', $referencedSlugs)->pluck('id', 'slug');
    }

    /**
     * Upsert the treatments under their categories, then read back their ids.
     *
     * @param  list<TreatmentRow>  $treatments
     * @param  Collection<string, int>  $categoryIds
     * @return Collection<string, int>
     */
    private function writeTreatments(array $treatments, Collection $categoryIds): Collection
    {
        Treatment::query()->upsert(
            $this->rowsFor(Treatment::class, array_map(fn (array $treatment): array => [
                ...array_diff_key($treatment, ['category' => true, 'variants' => true]),
                'treatment_category_id' => $categoryIds[$treatment['category']],
            ], $treatments)),
            uniqueBy: ['slug'],
            update: self::TREATMENT_UPDATED_COLUMNS,
        );

        return Treatment::query()->whereIn('slug', array_column($treatments, 'slug'))->pluck('id', 'slug');
    }

    /**
     * Upsert every price line, keyed by its treatment and its position.
     *
     * @param  list<TreatmentRow>  $treatments
     * @param  Collection<string, int>  $treatmentIds
     */
    private function writeVariants(array $treatments, Collection $treatmentIds): void
    {
        $variants = [];
        foreach ($treatments as $treatment) {
            foreach ($treatment['variants'] as $variant) {
                $variants[] = [...$variant, 'treatment_id' => $treatmentIds[$treatment['slug']]];
            }
        }

        TreatmentVariant::query()->upsert(
            $this->rowsFor(TreatmentVariant::class, $variants),
            uniqueBy: ['treatment_id', 'position'],
            update: self::VARIANT_UPDATED_COLUMNS,
        );
    }

    /**
     * @return list<CategoryRow>
     */
    private function categories(): array
    {
        return [
            $this->category(10, 'rituels-corps', 'Nos rituels corps', subtitle: 'Revenir à soi', isSignature: true, isFeatured: true),
            $this->category(20, 'rituel-visage-et-ame', 'Nos rituels visage', subtitle: 'Visage & Âme', isSignature: true, isFeatured: true),
            $this->category(30, 'rituels-complets', 'Nos rituels complets Corps & Visage', subtitle: "L'Absolu", isSignature: true, isFeatured: true),
            $this->category(
                40,
                'traitements-visage',
                'Nos traitements visage',
                subtitle: 'Expertise & Haute Beauté',
                isFeatured: true,
                description: 'Des soins visage by [Comfort Zone] sur mesure, alliant expertise cutanée, technologies douces et cosmétique naturelle hautement performante. Chaque traitement est pensé selon les besoins profonds de votre peau, votre rythme de vie et l\'état global de votre organisme, dans une approche globale du bien vieillir. Les temps de soin incluent le diagnostic de peau avec le Skin Test 2.0.',
            ),
            $this->category(
                50,
                'singuliers',
                'Nos singuliers',
                isFeatured: true,
                description: 'Des soins à part, chacun avec sa technique et son intention. Des gestes précis, pour répondre à un besoin singulier.',
            ),
            $this->category(
                60,
                'supplements-d-ame',
                'Les suppléments d\'Âme',
                description: 'Pour sublimer votre soin, sans rien retirer à sa durée : une attention en plus pour votre regard, votre visage, vos lèvres, vos mains ou vos pieds, à ajouter selon vos envies.',
            ),
            $this->category(
                70,
                'epilation',
                'L\'art de l\'épilation',
                description: "Pour une peau douce et nette, nous vous proposons l'épilation à la cire naturelle, au sucre ou au fil, selon vos préférences et la sensibilité de votre peau. Des forfaits sont également disponibles selon les zones souhaitées.\n\nRetrouvez toutes nos épilations et leurs tarifs directement sur notre page de réservation en ligne.",
            ),
        ];
    }

    /**
     * @return list<TreatmentRow>
     */
    private function treatments(): array
    {
        return [
            $this->treatment('rituels-corps', 10, 'pause-essentielle', 'Pause essentielle', [$this->variant(10, 9500, 75, 45)], description: "Une parenthèse pour relâcher les tensions et revenir à soi, le temps d'un massage enveloppant."),
            $this->treatment('rituels-corps', 20, 'reconnexion-profonde', 'Reconnexion profonde', [$this->variant(10, 12000, 90, 60)], description: 'Une heure de soin pour délier le corps en profondeur et retrouver un souffle apaisé.'),
            $this->treatment('rituels-corps', 30, 'lacher-prise-integral', 'Lâcher-prise intégral', [$this->variant(10, 16000, 120, 90)], description: "Le plus ample de nos rituels corps : une heure et demie de soin pour tout déposer, du corps à l'esprit."),
            $this->treatment('rituel-visage-et-ame', 10, 'aura-botanica', 'Aura Botanica', [$this->variant(10, 15000, 105, 75)], description: "Un soin du visage sur mesure aux plantes, pour révéler l'éclat de la peau et apaiser l'esprit."),
            $this->treatment('rituel-visage-et-ame', 20, 'aura-botanica-lumina', 'Aura Botanica Lumina', [$this->variant(10, 18000, 135, 105)], description: 'Aura Botanica dans sa version la plus généreuse, avec une demi-heure de soin en plus.'),
            $this->treatment('rituels-complets', 10, 'l-absolu', 'L\'absolu', [$this->variant(10, 21000, 150, 120)], description: 'Le corps et le visage réunis dans un même rituel, composé avec vous le jour même.'),
            $this->treatment('rituels-complets', 20, 'l-absolu-profond', 'L\'absolu Profond', [$this->variant(10, 26000, 180, 150)], description: 'Un rituel complet plus ample, pour aller plus loin dans le soin du corps comme du visage.'),
            $this->treatment('rituels-complets', 30, 'l-absolu-infini', 'L\'absolu Infini', [$this->variant(10, 29000, 210, 180)], description: "Le plus long de nos rituels : trois heures de soin, du corps jusqu'au visage."),
            $this->treatment('traitements-visage', 10, 'the-joy-of-beauty', 'The Joy of Beauty', [
                $this->variant(10, 12500, 60),
                $this->variant(20, 16000, 90),
            ], description: 'Un soin visage by [Comfort Zone] composé selon le diagnostic de votre peau.'),
            $this->treatment('singuliers', 10, 'kobido', 'Kobido', [
                $this->variant(10, 12000, 60),
                $this->variant(20, 15000, 90, label: 'avec soin visage'),
            ], description: 'Art ancestral japonais du massage facial, transmis selon la méthode de Maître Shogo Mochizuki. Des gestes précis et rythmés pour un visage sculpté, lumineux et profondément détendu.'),
            $this->treatment('singuliers', 20, 'massage-facial-signature', 'Le massage facial signature', [$this->variant(10, 12000, 60)], description: 'Véritable art du toucher, ce massage visage sur mesure associe gestes experts et outils de facialisme (gua sha, ventouses, pochons chauds aux plantes, travail intra-oral) pour relâcher les tensions profondes et révéler l\'éclat naturel du visage. Les traits se défroissent, le corps entier lâche prise.'),
            $this->treatment('singuliers', 30, 'drainage-osmotic', 'Le Drainage Osmotic', [$this->variant(10, 12000, 60)], subtitle: 'by [Comfort Zone]', description: 'Le soin drainant profond qui aide à réduire la stagnation des fluides, offrant légèreté et vitalité à votre organisme. Il agira en même temps sur les muscles, offrant un effet immédiat de tonus et de fermeté, avec un effet liftant et des résultats visibles immédiatement.'),
            $this->treatment('singuliers', 40, 'pro-sleep-massage', 'Le Pro-Sleep Massage', [$this->variant(10, 14000, 75)], subtitle: 'by [Comfort Zone]', description: 'Un rituel corps pensé pour apaiser le système nerveux et inviter au lâcher-prise. Des gestes très doux et enveloppants rappellent le bercement des premiers instants de la vie, dans les bras ou au creux du ventre maternel. Une musique aux battements de cœur et une fréquence apaisante accompagnent le soin, tandis qu\'une senteur composée pour éveiller les souvenirs heureux vous enveloppe de réconfort. Tous vos sens sont bercés, le corps et l\'esprit se déposent, pour retrouver le chemin d\'un sommeil profond et serein.'),
            $this->treatment('supplements-d-ame', 10, 'supplement-yeux', 'Yeux', [$this->variant(10, 1000)], description: 'Pour cibler cette zone si spécifique qu\'est le contour de l\'œil. Les rides sont lissées, les poches soufflées et les cernes envolés. Triple action pour un regard magnifié.'),
            $this->treatment('supplements-d-ame', 20, 'supplement-visage', 'Visage', [$this->variant(10, 1200)], description: 'Pendant que nos mains prennent soin de votre corps, votre visage n\'est pas oublié. Un masque choisi pour votre type de peau se pose et agit en silence, pour que vous repartiez le corps détendu et le visage éclatant.'),
            $this->treatment('supplements-d-ame', 30, 'supplement-levres', 'Lèvres', [$this->variant(10, 1200)], description: 'Un gommage tout en douceur, un masque à l\'acide hyaluronique et à la figue de Barbarie, puis un massage dédié : vos lèvres retrouvent souplesse, confort et éclat, comme naturellement repulpées.'),
            $this->treatment('supplements-d-ame', 40, 'supplement-mains-ou-pieds', 'Mains ou pieds', [$this->variant(10, 1500)], description: 'Vos mains ou vos pieds se glissent dans des gants ou des chaussettes infusés de collagène, d\'huile d\'argan et de beurre de karité. Un cocktail nourrissant qui hydrate en profondeur : la peau retrouve son élasticité, elle est lissée, repulpée et douce.'),
        ];
    }

    /**
     * The hair removal menu, grouped by technique and audience.
     *
     * @return list<TreatmentRow>
     */
    private function waxingTreatments(): array
    {
        $waxing = [
            ['Épilations femmes', 10, 'epilation-femme-creation-ligne-sourcil', 'Création de la ligne du sourcil', 20, 1900],
            ['Épilations femmes', 20, 'epilation-femme-sourcils', 'Sourcils', 15, 1300],
            ['Épilations femmes', 30, 'epilation-femme-levres', 'Lèvres', 10, 1300],
            ['Épilations femmes', 40, 'epilation-femme-nez', 'Nez', 10, 1300],
            ['Épilations femmes', 50, 'epilation-femme-visage-complet', 'Visage complet', 20, 2900],
            ['Épilations femmes', 60, 'epilation-femme-aisselles', 'Aisselles', 10, 1500],
            ['Épilations femmes', 70, 'epilation-femme-demi-bras', 'Demi-bras', 15, 2200],
            ['Épilations femmes', 80, 'epilation-femme-bras', 'Bras', 20, 2700],
            ['Épilations femmes', 90, 'epilation-femme-maillot-simple', 'Maillot simple', 15, 1800],
            ['Épilations femmes', 100, 'epilation-femme-maillot-bresilien', 'Maillot brésilien', 20, 2500],
            ['Épilations femmes', 110, 'epilation-femme-maillot-semi-integral', 'Maillot semi-intégral', 25, 3100],
            ['Épilations femmes', 120, 'epilation-femme-maillot-integral', 'Maillot intégral', 30, 3600],
            ['Épilations femmes', 130, 'epilation-femme-demi-jambes', 'Demi-jambes', 20, 2700],
            ['Épilations femmes', 140, 'epilation-femme-cuisses', 'Cuisses', 20, 2800],
            ['Épilations femmes', 150, 'epilation-femme-jambes-completes', 'Jambes complètes', 35, 3900],
            ['Forfaits femmes', 160, 'forfait-aisselles-ou-sourcils-levres', 'Aisselles ou sourcils, et lèvres', 10, 2000],
            ['Forfaits femmes', 170, 'forfait-supplement-sourcils-ou-aisselles', 'Supplément sourcils ou aisselles', 10, 1200],
            ['Forfaits femmes', 180, 'forfait-supplement-jambes-completes', 'Supplément jambes complètes', 15, 1800],
            ['Forfaits femmes', 190, 'forfait-demi-jambes-maillot-simple', 'Aisselles ou sourcils, demi-jambes et maillot simple', 40, 5000],
            ['Forfaits femmes', 200, 'forfait-demi-jambes-maillot-bresilien', 'Aisselles ou sourcils, demi-jambes et maillot brésilien', 45, 5500],
            ['Forfaits femmes', 210, 'forfait-demi-jambes-maillot-semi-integral', 'Aisselles ou sourcils, demi-jambes et maillot semi-intégral', 55, 6500],
            ['Forfaits femmes', 220, 'forfait-demi-jambes-maillot-integral', 'Aisselles ou sourcils, demi-jambes et maillot intégral', 60, 7000],
            ['Épilations hommes', 230, 'epilation-homme-creation-ligne-sourcil', 'Création de la ligne du sourcil', 20, 1900],
            ['Épilations hommes', 240, 'epilation-homme-sourcils', 'Sourcils', 15, 1300],
            ['Épilations hommes', 250, 'epilation-homme-nez', 'Nez', 10, 1300],
            ['Épilations hommes', 260, 'epilation-homme-aisselles', 'Aisselles', 10, 1500],
            ['Épilations hommes', 270, 'epilation-homme-demi-jambes', 'Demi-jambes', 20, 2700],
            ['Épilations hommes', 280, 'epilation-homme-jambes-completes', 'Jambes complètes', 45, 4900],
            ['Épilations hommes', 290, 'epilation-homme-dos', 'Dos', 25, 2800],
            ['Épilations hommes', 300, 'epilation-homme-torse', 'Torse', 25, 2800],
            ['Épilations au fil', 310, 'epilation-fil-sourcils', 'Sourcils', 15, 1500],
            ['Épilations au fil', 320, 'epilation-fil-levres', 'Lèvres', 10, 1500],
            ['Épilations au fil', 330, 'epilation-fil-forfait-sourcils-levres', 'Forfait sourcils et lèvres', 15, 2000],
            ['Épilations au fil', 340, 'epilation-fil-menton-ou-bas-joues', 'Menton ou bas-joues', 10, 1500],
            ['Épilations au fil', 350, 'epilation-fil-visage-complet', 'Visage complet', 30, 3500],
            ['Épilations au sucre', 360, 'epilation-sucre-sourcils', 'Sourcils', 15, 1500],
            ['Épilations au sucre', 370, 'epilation-sucre-levres-ou-nez', 'Lèvres ou nez', 10, 1300],
            ['Épilations au sucre', 380, 'epilation-sucre-aisselles', 'Aisselles', 15, 1800],
            ['Épilations au sucre', 390, 'epilation-sucre-demi-bras', 'Demi-bras', 20, 2400],
            ['Épilations au sucre', 400, 'epilation-sucre-bras', 'Bras', 25, 2900],
            ['Épilations au sucre', 410, 'epilation-sucre-maillot-simple', 'Maillot simple', 15, 2000],
            ['Épilations au sucre', 420, 'epilation-sucre-maillot-bresilien', 'Maillot brésilien', 20, 2700],
            ['Épilations au sucre', 430, 'epilation-sucre-maillot-semi-integral', 'Maillot semi-intégral', 35, 3300],
            ['Épilations au sucre', 440, 'epilation-sucre-maillot-integral', 'Maillot intégral', 40, 3800],
            ['Épilations au sucre', 450, 'epilation-sucre-demi-jambes', 'Demi-jambes', 25, 3000],
            ['Épilations au sucre', 460, 'epilation-sucre-cuisses', 'Cuisses', 20, 3000],
            ['Épilations au sucre', 470, 'epilation-sucre-jambes-completes', 'Jambes complètes', 40, 4500],
            ['Épilations au sucre', 480, 'epilation-sucre-torse', 'Torse', 25, 3000],
            ['Épilations au sucre', 490, 'epilation-sucre-dos', 'Dos', 25, 3000],
        ];

        return array_map(
            fn (array $line): array => $this->treatment(
                'epilation',
                $line[1],
                $line[2],
                $line[3],
                [$this->variant(10, $line[5], $line[4])],
                groupLabel: $line[0],
            ),
            $waxing,
        );
    }

    /**
     * @return CategoryRow
     */
    private function category(
        int $position,
        string $slug,
        string $name,
        ?string $subtitle = null,
        bool $isSignature = false,
        bool $isFeatured = false,
        ?string $description = null,
    ): array {
        return [
            'slug' => $slug,
            'name' => $name,
            'subtitle' => $subtitle,
            'description' => $description,
            'is_signature' => $isSignature,
            'is_featured' => $isFeatured,
            'booking_url' => null,
            'position' => $position,
            'is_visible' => true,
        ];
    }

    /**
     * @param  list<VariantRow>  $variants
     * @return TreatmentRow
     */
    private function treatment(
        string $category,
        int $position,
        string $slug,
        string $name,
        array $variants,
        ?string $subtitle = null,
        ?string $description = null,
        ?string $groupLabel = null,
    ): array {
        return [
            'category' => $category,
            'slug' => $slug,
            'name' => $name,
            'subtitle' => $subtitle,
            'description' => $description,
            'group_label' => $groupLabel,
            'position' => $position,
            'is_visible' => true,
            'variants' => $variants,
        ];
    }

    /**
     * @return VariantRow
     */
    private function variant(
        int $position,
        int $priceCents,
        ?int $totalMinutes = null,
        ?int $careMinutes = null,
        ?string $label = null,
    ): array {
        return [
            'label' => $label,
            'total_duration_minutes' => $totalMinutes,
            'care_duration_minutes' => $careMinutes,
            'price_cents' => $priceCents,
            'position' => $position,
            'is_visible' => true,
        ];
    }
}
