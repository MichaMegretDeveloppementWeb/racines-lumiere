<?php

declare(strict_types=1);

namespace Database\Seeders\Content;

use App\Models\Brand;
use Illuminate\Database\Seeder;

/**
 * The partner brands, in their display order.
 *
 * @phpstan-type BrandRow array{slug: string, name: string, tagline: ?string, long_text: string, short_text: ?string, role_text: ?string, logo_path: ?string, products_url: ?string, position: int, is_visible: bool}
 */
class BrandSeeder extends Seeder
{
    use BuildsRows;

    private const array UPDATED_COLUMNS = [
        'name', 'tagline', 'long_text', 'short_text', 'role_text', 'logo_path', 'products_url', 'position', 'is_visible',
    ];

    /**
     * Seed the partner brands.
     */
    public function run(): void
    {
        $this->writeBrands($this->brands());
    }

    /**
     * Write every brand in a single upsert.
     *
     * @param  list<BrandRow>  $brands
     */
    public function writeBrands(array $brands): void
    {
        Brand::query()->upsert($this->rowsFor(Brand::class, $brands), uniqueBy: ['slug'], update: self::UPDATED_COLUMNS);
    }

    /**
     * @return list<BrandRow>
     */
    private function brands(): array
    {
        return [
            $this->brand(
                10,
                'altearah-bio',
                'Altearah Bio',
                tagline: 'une couleur, une émotion',
                longText: $this->paragraphs(
                    'Marque éthique et innovante, Altearah repose sur un concept inédit qui réunit chromothérapie, aromathérapie et olfactothérapie. Au cœur de la marque, 14 synergies d\'huiles essentielles codées par couleur, chacune porteuse d\'une promesse de bien-être émotionnel.',
                    'Les couleurs nous accompagnent depuis toujours et résonnent avec nos émotions, nos souvenirs, nos choix. Altearah s\'en sert comme d\'une porte d\'entrée : on choisit la couleur qui nous attire à cet instant, et elle révèle le besoin du moment. Chaque huile essentielle est sélectionnée pour ses bienfaits, sa qualité et sa traçabilité, puis assemblée en synergie avec un équilibre subtil d\'extraits de plantes.',
                    'Le résultat est une expérience de soin multisensorielle, un voyage intérieur où les senteurs, les couleurs et le toucher aident les émotions à retrouver leur juste place. Chez Altearah, la clé du bien-être se trouve en vous : vos émotions en sont la clé.',
                ),
                shortText: 'Altearah Bio associe couleurs, huiles essentielles et senteurs pour prendre soin du corps par les émotions. 14 synergies, 14 promesses de bien-être : vous choisissez la couleur qui vous attire, nous composons le rituel.',
                roleText: 'Chez Racines & Lumière, Altearah signe nos massages et soins corps, pour des rituels enveloppants, sensoriels et entièrement personnalisés selon votre état du moment.',
            ),
            $this->brand(
                20,
                'comfort-zone',
                'Comfort Zone',
                tagline: 'Conscious. Skin. Science.',
                longText: $this->paragraphs(
                    'Comfort Zone est une marque de soins professionnels fondée sur une idée simple : la beauté n\'est pas une surface, mais un système, où la peau, la science, la nature et la durabilité sont reliées. Ses textures biomimétiques associent de puissants extraits botaniques, issus de la pharmacognosie, à des actifs biotechnologiques de pointe. Chaque formule est testée cliniquement et dermatologiquement, pour une efficacité et une sécurité garanties.',
                    'La marque parle d\'état de la peau plutôt que de type de peau, car l\'équilibre cutané évolue avec l\'âge, le mode de vie, les voyages et l\'environnement. Chaque soin commence par une observation attentive et le Skin Test 2.0, un diagnostic avancé qui révèle ce que l\'œil le plus expert ne peut pas voir. Vient ensuite le pouvoir du toucher : des formules intensives appliquées par des professionnelles formées et certifiées, pour un moment de beauté, de relaxation et de régénération profonde.',
                    'Certifiée B Corp depuis 2016, Comfort Zone défend une beauté qui régénère la peau comme les sols, et qui crée de la valeur sur le long terme.',
                ),
                shortText: 'Comfort Zone allie science, nature et expertise du geste au service de la longévité de la peau. Formules biomimétiques testées cliniquement, diagnostic de peau Skin Test 2.0, marque B Corp depuis 2016 : une beauté performante et responsable.',
                roleText: 'Chez Racines & Lumière, Comfort Zone signe nos soins visage experts, avec analyse de peau et consultation experte.',
            ),
            $this->brand(
                30,
                'labote',
                'Laboté',
                tagline: 'le laboratoire parisien des soins de précision',
                longText: $this->paragraphs(
                    'Laboté conçoit et fabrique tous ses soins dans son propre laboratoire, en plein cœur du 11e arrondissement de Paris. Le développement se fait de A à Z en interne, en petites séries. Cette proximité entre la formule, la fabrication et la vente permet des soins sans compromis, avec des concentrations d\'actifs uniques.',
                    'Les actifs sont frais, sourcés en circuit court et choisis selon leur niveau d\'objectivation scientifique. Une production à froid brevetée préserve leur fraîcheur et leur pouvoir antioxydant, dont l\'efficacité a été prouvée par l\'Institut Européen des Antioxydants, un laboratoire indépendant.',
                    'Derrière chaque formule, une équipe d\'ingénieurs chimistes, de biologistes, d\'experts en cosmétique et de docteurs en pharmacie. Forte de 150 000 diagnostics de peau, elle développe des soins adaptés à chaque peau, même les plus fragiles. Plus de 20 000 clients lui confient leur peau au quotidien.',
                ),
                shortText: 'Laboté formule et fabrique ses soins dans son laboratoire parisien : actifs frais, production à froid brevetée, efficacité antioxydante prouvée. Un travail d\'orfèvre, développé par des docteurs en pharmacie et nourri de 150 000 diagnostics de peau.',
                roleText: 'Chez Racines & Lumière, Laboté signe nos soins visage botaniques et nourrit nos rituels signature de 2h, 2h30 et 3h : des formules fraîches et concentrées, imaginées par des docteurs en pharmacie.',
            ),
            $this->brand(
                40,
                'gingerly',
                'Gingerly',
                tagline: 'plus qu\'une infusion, une expérience sensorielle',
                longText: 'Gingerly propose des thés, tisanes et infusions bio d\'inspiration ayurvédique, pensés comme un rituel plus que comme un produit. Sa fondatrice, Aurélie Bachelet, a quitté quinze ans de textile après avoir découvert le yoga puis l\'ayurvéda lors de voyages en Inde. Formée à l\'ayurvéda et au yoga, elle imagine en 2020 le rituel « 10 minutes pour moi » : le temps d\'une infusion, on ralentit, on respire, on ressent. La gamme, certifiée bio Ecocert, se choisit selon vos émotions du moment ou votre profil ayurvédique (Vata, Pitta, Kapha).',
                shortText: 'Des infusions bio d\'inspiration ayurvédique pour ralentir, respirer et ressentir, en dix minutes par jour.',
                roleText: 'Chez Racines & Lumière, Gingerly signe la tisane rituel de fin de soin : un moment pour ralentir, respirer et prolonger l\'apaisement. Les infusions sont aussi disponibles à la boutique pour prolonger le rituel à la maison.',
            ),
            $this->brand(
                50,
                'ilse',
                'ILSE',
                tagline: 'la beauté par le mouvement',
                longText: 'ILSE regarde la nature comme un modèle d\'intelligence plutôt que comme une matière première. Son approche s\'inspire du biomimétisme : le vivant ne cherche pas la perfection mais l\'équilibre, il circule, s\'ajuste, se régénère. Ses cures de plantes, ses massages et ses outils drainants accompagnent les rythmes naturels du corps et soutiennent la circulation. Une beauté fonctionnelle, fluide et respectueuse du rythme de chacune.',
                shortText: 'Cures de plantes et gestes drainants pour une beauté qui s\'inspire du vivant et remet le mouvement au cœur du soin.',
                roleText: 'Chez Racines & Lumière, ILSE accompagne le soin par des cures de plantes à choisir selon votre besoin du moment : drainer, retrouver de la vitalité, se recharger. Votre praticienne vous conseille la cure qui vous correspond, à retrouver à la boutique.',
            ),
            $this->brand(
                60,
                'skin-diligent',
                'Skin Diligent',
                tagline: 'la beauté commence par la santé cellulaire',
                longText: 'Skin Diligent se présente comme un laboratoire indépendant de recherche appliquée, cofondé en 2021 par Tule Park. Ses soins épigénétiques agissent au niveau des cellules pour les aider à se réparer, se régénérer et se défendre, afin de révéler grain lissé, fermeté et éclat. La marque, fabriquée en France, revendique d\'être le premier laboratoire au monde à tester l\'absence d\'activité de perturbation hormonale dans ses formules finales. Une science de pointe au service de la longévité de la peau.',
                shortText: 'Des soins épigénétiques, fabriqués en France, qui prennent soin de la peau à l\'échelle de la cellule.',
                roleText: 'Chez Racines & Lumière, Skin Diligent rejoint la boutique skincare comme référence de la science de pointe.',
            ),
            $this->brand(
                70,
                'demain-beauty',
                'Demain Beauty',
                tagline: 'préserver la peau aujourd\'hui, mieux vieillir demain',
                longText: 'Demain Beauty est une marque française née de la conviction qu\'une belle peau est une peau en bonne santé. Plutôt que de corriger les signes du temps, elle prend soin de l\'équilibre de la peau et de son microbiome pour préserver sa beauté sur la durée. Ses soins sont 100 % d\'origine naturelle, certifiés bio et vegan, et son approche « inside & out » les associe à des compléments alimentaires. Fabrication française, routines essentielles, esprit « moins mais mieux » : une longévité cutanée simple et durable.',
                shortText: 'Des soins bio et des compléments pour préserver l\'équilibre de la peau et mieux vieillir, en respectant son microbiome.',
                roleText: 'Chez Racines & Lumière, Demain Beauty rejoint la boutique skincare, en particulier pour ses compléments et ses soins des peaux fragiles.',
            ),
        ];
    }

    /**
     * @return BrandRow
     */
    private function brand(
        int $position,
        string $slug,
        string $name,
        string $tagline,
        string $longText,
        string $shortText,
        string $roleText,
    ): array {
        return [
            'slug' => $slug,
            'name' => $name,
            'tagline' => $tagline,
            'long_text' => $longText,
            'short_text' => $shortText,
            'role_text' => $roleText,
            'logo_path' => null,
            'products_url' => null,
            'position' => $position,
            'is_visible' => true,
        ];
    }

    /**
     * Join paragraphs the way long texts are stored: separated by a blank line.
     */
    private function paragraphs(string ...$paragraphs): string
    {
        return implode("\n\n", $paragraphs);
    }
}
