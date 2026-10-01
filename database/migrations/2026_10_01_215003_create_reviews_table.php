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
        Schema::create('reviews', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique('reviews_slug_unique');
            $table->string('author_name');
            $table->rawColumn(
                'rating',
                'tinyint unsigned constraint reviews_rating_between_1_and_5 check (rating between 1 and 5)'
            );
            $table->text('body');
            $table->date('reviewed_on');
            $table->string('treatment_label')->nullable();
            $table->unsignedSmallInteger('position');
            $table->boolean('is_visible')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
