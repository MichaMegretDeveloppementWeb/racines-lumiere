<?php

declare(strict_types=1);

namespace App\Services\Contact;

use App\Data\Contact\ContactMessageData;
use App\Exceptions\Contact\ContactDeliveryException;
use App\Mail\Contact\ContactMessageMail;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ContactMessageService
{
    public function __construct(private readonly Repository $config) {}

    public function send(ContactMessageData $contact): void
    {
        try {
            Mail::to((string) $this->config->get('institute.contact.form_recipient'))
                ->queue((new ContactMessageMail($contact))->onConnection('sync'));
        } catch (TransportExceptionInterface $failure) {
            throw new ContactDeliveryException($failure::class);
        }
    }
}
