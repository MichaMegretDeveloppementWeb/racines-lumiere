<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\Partner;
use App\Models\Review;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use Database\Seeders\Content\ReviewSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Seeder;

/**
 * Run the database seeder the way db:seed does, letting any failure surface.
 */
function seedDatabase(): void
{
    app(DatabaseSeeder::class)->setContainer(app())->__invoke();
}

it('seeds the whole content in eight queries', function (): void {
    // Menu: five queries · brands, partners and reviews: one upsert each.
    $count = queryCount(fn () => seedDatabase());

    expect($count)->toBe(8)
        ->and(TreatmentCategory::query()->count())->toBe(7)
        ->and(Treatment::query()->count())->toBe(66)
        ->and(Brand::query()->count())->toBe(7)
        ->and(Partner::query()->count())->toBe(5)
        ->and(Review::query()->count())->toBe(6);
});

it('writes nothing at all when one content seeder fails', function (): void {
    $this->app->bind(ReviewSeeder::class, fn (): Seeder => new class extends Seeder
    {
        public function run(): void
        {
            throw new RuntimeException('Simulated seeding failure.');
        }
    });

    expect(fn () => seedDatabase())->toThrow(RuntimeException::class, 'Simulated seeding failure.')
        ->and(TreatmentCategory::query()->exists())->toBeFalse()
        ->and(Brand::query()->exists())->toBeFalse()
        ->and(Partner::query()->exists())->toBeFalse();
});
