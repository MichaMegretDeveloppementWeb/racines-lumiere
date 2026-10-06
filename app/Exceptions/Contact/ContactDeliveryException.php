<?php

declare(strict_types=1);

namespace App\Exceptions\Contact;

use RuntimeException;

class ContactDeliveryException extends RuntimeException
{
    public function __construct(public readonly string $transportError)
    {
        parent::__construct('Contact message delivery failed.');
    }

    public function getUserMessage(): string
    {
        return "Votre message n'a pas pu être envoyé. Écrivez-nous directement à";
    }
}
