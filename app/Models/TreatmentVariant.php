<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TreatmentVariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One price line of a treatment: an optional label, its durations and its price.
 */
class TreatmentVariant extends Model
{
    /** @use HasFactory<TreatmentVariantFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }
}
