<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TreatmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Treatment extends Model
{
    /** @use HasFactory<TreatmentFactory> */
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

    /**
     * @return HasMany<TreatmentVariant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(TreatmentVariant::class);
    }
}
