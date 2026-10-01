<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('treatment_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('treatment_id')
                ->constrained('treatments', indexName: 'treatment_variants_treatment_id_foreign');
            $table->string('label')->nullable();
            $table->unsignedSmallInteger('total_duration_minutes')->nullable();
            $table->unsignedSmallInteger('care_duration_minutes')->nullable();
            $table->unsignedInteger('price_cents');
            $table->unsignedSmallInteger('position');
            $table->boolean('is_visible')->default(true);

            $table->unique(['treatment_id', 'position'], 'treatment_variants_treatment_position_unique');
        });

        // A table-level check: it compares two columns, which a column-level check cannot do.
        DB::statement(
            'ALTER TABLE treatment_variants ADD CONSTRAINT treatment_variants_care_within_total CHECK ('
            .'care_duration_minutes IS NULL OR (total_duration_minutes IS NOT NULL AND care_duration_minutes < total_duration_minutes)'
            .')'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_variants');
    }
};
