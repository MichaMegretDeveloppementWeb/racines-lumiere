<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('treatment_category_id')
                ->index('treatments_treatment_category_id_index')
                ->constrained('treatment_categories', indexName: 'treatments_treatment_category_id_foreign');
            $table->string('slug')->unique('treatments_slug_unique');
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('group_label')->nullable();
            $table->unsignedSmallInteger('position');
            $table->boolean('is_visible')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
