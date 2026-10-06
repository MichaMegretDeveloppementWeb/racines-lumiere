<?php

declare(strict_types=1);

namespace App\Data\Contact;

final readonly class ContactMessageData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public string $message,
    ) {}
}
