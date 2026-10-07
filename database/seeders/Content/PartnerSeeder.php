<?php

declare(strict_types=1);

namespace Database\Seeders\Content;

use App\Models\Partner;
use Illuminate\Database\Seeder;

/**
 * The trusted circle. Publication consent for all five practitioners was confirmed on 2026-10-07.
 *
 * @phpstan-type PartnerRow array{slug: string, name: string, organization_name: ?string, specialty: string, town: ?string, website_url: ?string, position: int, is_visible: bool}
 */
class PartnerSeeder extends Seeder
{
    use BuildsRows;

    private const array UPDATED_COLUMNS = [
        'name', 'organization_name', 'specialty', 'town', 'website_url', 'position', 'is_visible',
    ];

    /**
     * Seed the trusted circle.
     */
    public function run(): void
    {
        $this->writePartners($this->partners());
    }

    /**
     * Write every practitioner in a single upsert.
     *
     * @param  list<PartnerRow>  $partners
     */
    public function writePartners(array $partners): void
    {
        Partner::query()->upsert($this->rowsFor(Partner::class, $partners), uniqueBy: ['slug'], update: self::UPDATED_COLUMNS);
    }

    /**
     * @return list<PartnerRow>
     */
    private function partners(): array
    {
        return [
            $this->partner(10, 'alice-peillex', 'Alice Peillex', 'Kinésiologue', 'Thonon-les-Bains', 'https://terapiz.com/kinesiologue/thonon-les-bains/alice-peillex'),
            $this->partner(20, 'julie-deage-martinez', 'Julie Déage-Martinez', 'Ostéopathe', 'Allinges', 'https://ame-s.co/'),
            $this->partner(30, 'camille-gouyon', 'Camille Gouyon', 'Thérapeute énergétique, ostéo douce', 'Perrignier', 'https://www.osteodouce.fr/'),
            $this->partner(40, 'marie-christine-gosetto', 'Marie-Christine Gosetto', 'Thérapeute intuitive', 'Perrignier', 'https://namaste-energie.fr/'),
            $this->partner(50, 'joelle-plantaz', 'Joëlle Plantaz', 'Conseillère en image', 'Margencel', 'https://jojolesbasbleus.fr/', organizationName: 'Jojo les Bas Bleus'),
        ];
    }

    /**
     * @return PartnerRow
     */
    private function partner(
        int $position,
        string $slug,
        string $name,
        string $specialty,
        string $town,
        string $websiteUrl,
        ?string $organizationName = null,
    ): array {
        return [
            'slug' => $slug,
            'name' => $name,
            'organization_name' => $organizationName,
            'specialty' => $specialty,
            'town' => $town,
            'website_url' => $websiteUrl,
            'position' => $position,
            'is_visible' => true,
        ];
    }
}
