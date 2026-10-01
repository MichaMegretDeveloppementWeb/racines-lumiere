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
        Schema::create('brands', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique('brands_slug_unique');
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('long_text');
            $table->text('short_text')->nullable();
            $table->text('role_text')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('products_url')->nullable();
            $table->unsignedSmallInteger('position');
            $table->boolean('is_visible')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
