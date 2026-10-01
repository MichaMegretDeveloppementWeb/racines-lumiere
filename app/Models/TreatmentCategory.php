<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TreatmentCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentCategory extends Model
{
    /** @use HasFactory<TreatmentCategoryFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_signature' => 'boolean',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }
}
