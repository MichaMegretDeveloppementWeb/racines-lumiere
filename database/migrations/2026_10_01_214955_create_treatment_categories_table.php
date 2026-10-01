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
        Schema::create('treatment_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique('treatment_categories_slug_unique');
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_signature')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('booking_url')->nullable();
            $table->unsignedSmallInteger('position');
            $table->boolean('is_visible')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_categories');
    }
};
