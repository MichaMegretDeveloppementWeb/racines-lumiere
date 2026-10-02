<?php

declare(strict_types=1);

namespace App\Services\Institute;

use App\Data\Institute\InstituteData;
use App\Data\Institute\LaunchOfferData;
use App\Services\Partner\TrustedCircleService;
use Illuminate\Contracts\Config\Repository;

class InstituteService
{
    public function __construct(
        private readonly Repository $config,
        private readonly TrustedCircleService $trustedCircle,
    ) {}

    /**
     * The institute as the pages present it: its configuration, and whether the trusted circle exists.
     */
    public function details(): InstituteData
    {
        $bookingUrl = (string) $this->config->get('institute.booking_url');

        return new InstituteData(
            isOpen: (bool) $this->config->get('institute.is_open'),
            bookingUrl: $bookingUrl,
            giftCardsUrl: $this->config->get('institute.gift_cards_url') ?? $bookingUrl,
            booksyProfileUrl: (string) $this->config->get('institute.booksy_profile_url'),
            email: (string) $this->config->get('institute.contact.email'),
            phone: $this->config->get('institute.contact.phone'),
            instagramUrl: (string) $this->config->get('institute.contact.instagram_url'),
            street: (string) $this->config->get('institute.address.street'),
            postalCode: (string) $this->config->get('institute.address.postal_code'),
            city: (string) $this->config->get('institute.address.city'),
            accessNote: $this->config->get('institute.address.access_note'),
            hasTrustedCircle: $this->trustedCircle->hasVisiblePartners(),
            launchOffer: $this->launchOffer(),
        );
    }

    /**
     * The launch offer, or null while its discount is unknown.
     */
    private function launchOffer(): ?LaunchOfferData
    {
        $discount = $this->config->get('institute.launch_offer.discount');

        if ($discount === null) {
            return null;
        }

        return new LaunchOfferData(
            discount: (string) $discount,
            conditions: $this->config->get('institute.launch_offer.conditions'),
        );
    }
}
