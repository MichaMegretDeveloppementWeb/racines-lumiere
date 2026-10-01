<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A practitioner of the trusted circle, published only once they have agreed to it.
 */
class Partner extends Model
{
    /** @use HasFactory<PartnerFactory> */
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
