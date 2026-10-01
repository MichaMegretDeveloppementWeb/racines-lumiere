<?php

declare(strict_types=1);

namespace Database\Seeders\Content;

use App\Models\Review;
use Illuminate\Database\Seeder;

/**
 * The Booksy reviews, copied word for word, typos included.
 *
 * @phpstan-type ReviewRow array{slug: string, author_name: string, rating: int, body: string, reviewed_on: string, treatment_label: ?string, position: int, is_visible: bool}
 */
class ReviewSeeder extends Seeder
{
    use BuildsRows;

    private const array UPDATED_COLUMNS = [
        'author_name', 'rating', 'body', 'reviewed_on', 'treatment_label', 'position', 'is_visible',
    ];

    /**
     * Seed the reviews.
     */
    public function run(): void
    {
        $this->writeReviews($this->reviews());
    }

    /**
     * Write every review in a single upsert.
     *
     * @param  list<ReviewRow>  $reviews
     */
    public function writeReviews(array $reviews): void
    {
        Review::query()->upsert($this->rowsFor(Review::class, $reviews), uniqueBy: ['slug'], update: self::UPDATED_COLUMNS);
    }

    /**
     * @return list<ReviewRow>
     */
    private function reviews(): array
    {
        return [
            $this->review(10, 'le-curieux-belfond-2026-08-05', 'Le Curieux-Belfond', '2026-08-05', 'épilations', 'Je recommande sans hésiter ! Un grand merci pour votre professionnalisme, votre gentillesse et votre bienveillance. On se sent tout de suite en confiance…'),
            $this->review(20, 'sampaio-2026-04-02', 'Sampaio', '2026-04-02', 'Rituel Aura Botanica', 'J\'ai passé un moment exceptionnel entre les mains d\'Aurore. Elle prend le temps d\'offrir une réelle parenthèse et les soins sont au top aussi…'),
            $this->review(30, 'david-2025-12-01', 'David', '2025-12-01', 'massage', 'C\'était une première pour moi, massage très agréable et relaxant, masseuse très agréable…'),
            $this->review(40, 'helene-2025-12-01', 'Hélène', '2025-12-01', 'épilation', 'L\'épilation n\'est jamais un moment très facile à vivre mais j\'avais une super esthéticienne…'),
            $this->review(50, 'alain-2025-12-01', 'Alain', '2025-12-01', null, 'Une excellente adresse ou l on est toujours très bien reçu et chouchouté…'),
            $this->review(60, 'zoee-2025-12-01', 'Zoée', '2025-12-01', null, 'Cet institut est un petit coin de paradis :)'),
        ];
    }

    /**
     * @return ReviewRow
     */
    private function review(
        int $position,
        string $slug,
        string $authorName,
        string $reviewedOn,
        ?string $treatmentLabel,
        string $body,
    ): array {
        return [
            'slug' => $slug,
            'author_name' => $authorName,
            'rating' => 5,
            'body' => $body,
            'reviewed_on' => $reviewedOn,
            'treatment_label' => $treatmentLabel,
            'position' => $position,
            'is_visible' => true,
        ];
    }
}
