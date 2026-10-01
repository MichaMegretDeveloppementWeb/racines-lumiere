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
        Schema::create('partners', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique('partners_slug_unique');
            $table->string('name');
            $table->string('organization_name')->nullable();
            $table->string('specialty');
            $table->string('town')->nullable();
            $table->string('website_url')->nullable();
            $table->unsignedSmallInteger('position');
            $table->boolean('is_visible')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
