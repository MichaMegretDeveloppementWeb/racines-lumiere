<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Content\BrandSeeder;
use Database\Seeders\Content\PartnerSeeder;
use Database\Seeders\Content\ReviewSeeder;
use Database\Seeders\Content\TreatmentMenuSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the site content, all of it or none of it: a failed deployment never leaves half a menu.
     */
    public function run(): void
    {
        DB::transaction(fn () => $this->call([
            TreatmentMenuSeeder::class,
            BrandSeeder::class,
            PartnerSeeder::class,
            ReviewSeeder::class,
        ]));
    }
}
