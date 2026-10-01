<?php

declare(strict_types=1);

namespace App\Services\Institute;

use App\Data\Institute\InstituteData;
use Illuminate\Contracts\Config\Repository;

class InstituteService
{
    public function __construct(private readonly Repository $config) {}

    /**
     * The institute as the pages present it, read from its configuration.
     */
    public function details(): InstituteData
    {
        return new InstituteData(
            isOpen: (bool) $this->config->get('institute.is_open'),
            bookingUrl: (string) $this->config->get('institute.booking_url'),
        );
    }
}
