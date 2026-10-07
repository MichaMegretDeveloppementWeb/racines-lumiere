<?php

declare(strict_types=1);

use App\Data\Contact\ContactMessageData;
use App\Mail\Contact\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

it('embeds the logo in the actual email and preserves a usable plain text alternative and reply address', function (): void {
    $contact = new ContactMessageData('Camille & Sam', 'camille@example.com', '06 00 00 00 00', "Bonjour,\n\nUn soin pour Camille & Sam ?");
    $sent = (new ContactMessageMail($contact))->to('team@example.com')->send(Mail::mailer('array'));
    $email = $sent->getOriginalMessage();
    $image = $email->getAttachments()[0];
    $mime = $email->toString();

    expect($email->getAttachments())->toHaveCount(1)
        ->and($image->getMediaType())->toBe('image')
        ->and($image->getMediaSubtype())->toBe('png')
        ->and($image->getDisposition())->toBe('inline')
        ->and($image->getBody())->toBe(file_get_contents(public_path('images/brand/logo-email.png')))
        ->and($mime)->toContain('Content-ID: <'.$image->getContentId().'>', 'cid:'.$image->getContentId(), 'multipart/related', 'multipart/alternative')
        ->and($email->getHtmlBody())->toContain('mailto:camille@example.com', 'Camille &amp; Sam', '06 00 00 00 00', "Bonjour,<br />\n<br />")
        ->not->toMatch('/<img[^>]+src=["\']https?:/i')
        ->and($email->getTextBody())->toContain($contact->message, $contact->name, $contact->phone)
        ->not->toContain('cid:', '<html')
        ->and($email->getReplyTo()[0]->getAddress())->toBe('camille@example.com');
});

it('escapes submitted markup in html and omits an absent phone', function (): void {
    $contact = new ContactMessageData('<b>Camille</b>', 'camille@example.com', null, '<img src=x onerror=alert(1)>');
    $sent = (new ContactMessageMail($contact))->to('team@example.com')->send(Mail::mailer('array'));
    $email = $sent->getOriginalMessage();

    expect($email->getHtmlBody())->toContain('&lt;b&gt;Camille&lt;/b&gt;', '&lt;img src=x onerror=alert(1)&gt;')
        ->not->toContain('<b>Camille</b>', '<img src=x')
        ->and($email->getTextBody())->not->toContain('Téléphone :');
});
