<?php

declare(strict_types=1);

namespace App\Data\Institute;

/**
 * What every page knows about the institute: its opening state, its links and how to reach it.
 */
final readonly class InstituteData
{
    public function __construct(
        public bool $isOpen,
        public string $bookingUrl,
        public string $giftCardsUrl,
        public string $booksyProfileUrl,
        public string $email,
        public ?string $phone,
        public string $instagramUrl,
        public string $street,
        public string $postalCode,
        public string $city,
        public ?string $accessNote,
        public bool $hasTrustedCircle,
        public ?LaunchOfferData $launchOffer,
    ) {}
}
