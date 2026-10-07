<?php

declare(strict_types=1);

namespace App\Mail\Contact;

use App\Data\Contact\ContactMessageData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactMessageMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly ContactMessageData $contact) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->contact->email, $this->contact->name)],
            subject: 'Nouveau message de '.$this->contact->name.' via le site',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact.message',
            text: 'mail.contact.message-text',
        );
    }
}
