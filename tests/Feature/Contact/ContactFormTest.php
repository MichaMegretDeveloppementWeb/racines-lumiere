<?php

declare(strict_types=1);

use App\Livewire\Web\Contact\ContactForm;
use App\Mail\Contact\ContactMessageMail;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Symfony\Component\Mailer\Exception\TransportException;

beforeEach(function (): void {
    Mail::fake();
    Cache::flush();
    $this->freezeTime();
    config(['institute.contact.form_recipient' => 'team@example.com']);
});

/** @return array<string, string|bool> */
function contactFields(): array
{
    return ['name' => 'Camille Dupont', 'email' => 'camille@example.com', 'phone' => '', 'message' => 'Bonjour, quel soin me conseillez-vous ?', 'consent' => true];
}

it('sends to the team synchronously without storing the message and prevents a second submission', function (): void {
    $form = Livewire::test(ContactForm::class)->set(contactFields());
    $this->travel(3)->seconds();

    expect(queryCount(fn () => $form->call('send')))->toBe(0);
    $form->assertHasNoErrors()->assertSet('isSent', true)->assertSet('message', '')
        ->assertSee('votre message est bien parti')->call('send');

    Mail::assertQueuedCount(1);
    Mail::assertQueued(ContactMessageMail::class, function (ContactMessageMail $mail): bool {
        expect($mail->connection)->toBe('sync')
            ->and($mail->envelope()->replyTo[0]->address)->toBe('camille@example.com')
            ->and($mail->contact->phone)->toBeNull();

        return $mail->hasTo('team@example.com');
    });
});

it('rejects an empty form before confirming regardless of submission speed', function (int $delay): void {
    $form = Livewire::test(ContactForm::class);
    $this->travel($delay)->seconds();

    $form->call('send')
        ->assertHasErrors(['name' => 'required', 'email' => 'required', 'message' => 'required', 'consent' => 'accepted'])
        ->assertSet('isSent', false)
        ->assertSee('Indiquez votre nom.')
        ->assertSee('Indiquez votre adresse e-mail.')
        ->assertSee('Écrivez votre message.')
        ->assertSee('Cochez la case pour que nous puissions vous répondre.')
        ->assertDontSee('votre message est bien parti');

    Mail::assertNothingOutgoing();
})->with([0, 2, 3, 10]);

it('rejects invalid input and retains the message', function (string $field, string|bool $value, string $rule): void {
    $form = Livewire::test(ContactForm::class)->set([...contactFields(), $field => $value]);
    $this->travel(3)->seconds();
    $form->call('send')->assertHasErrors([$field => $rule])->assertSet('isSent', false);
    Mail::assertNothingQueued();
})->with([
    'missing name' => ['name', '', 'required'],
    'short name' => ['name', 'A', 'min'],
    'long name' => ['name', str_repeat('a', 101), 'max'],
    'name header injection' => ['name', "Camille\r\nBcc: other@example.com", 'regex'],
    'blank name' => ['name', '   ', 'required'],
    'missing email' => ['email', '', 'required'],
    'invalid email' => ['email', 'not an email', 'email'],
    'long email' => ['email', str_repeat('a', 250).'@example.com', 'max'],
    'invalid phone' => ['phone', 'bonjour', 'regex'],
    'long phone' => ['phone', str_repeat('1', 31), 'max'],
    'missing message' => ['message', '', 'required'],
    'short message' => ['message', 'Bonjour', 'min'],
    'long message' => ['message', str_repeat('a', 2001), 'max'],
    'missing consent' => ['consent', false, 'accepted'],
]);

it('accepts the inclusive length boundaries and a formatted optional phone', function (int $nameLength, int $messageLength): void {
    $form = Livewire::test(ContactForm::class)->set([
        ...contactFields(), 'name' => str_repeat('a', $nameLength),
        'message' => str_repeat('b', $messageLength), 'phone' => '+33 6.12-34 56 78',
    ]);
    $this->travel(3)->seconds();
    $form->call('send')->assertHasNoErrors()->assertSet('isSent', true);
    Mail::assertQueued(ContactMessageMail::class, fn (ContactMessageMail $mail): bool => $mail->contact->phone === '+33 6.12-34 56 78');
})->with([[2, 10], [100, 2000]]);

it('silently discards submissions caught by the honeypot or minimum delay', function (string $website, int $delay): void {
    $form = Livewire::test(ContactForm::class)->set([...contactFields(), 'website' => $website]);
    $this->travel($delay)->seconds();
    $form->call('send')->assertSet('isSent', true);
    Mail::assertNothingQueued();
})->with([['https://spam.example', 5], ['', 2]]);

it('does not allow the browser to modify protected submission state', function (string $property, int|bool $value): void {
    Livewire::test(ContactForm::class)->set($property, $value);
})->with([['openedAt', 0], ['isSent', true]])->throws(CannotUpdateLockedPropertyException::class);

it('rejects the fourth message from an IP and allows another after the limit expires', function (): void {
    for ($attempt = 1; $attempt <= 4; $attempt++) {
        $form = Livewire::test(ContactForm::class)->set(contactFields());
        $this->travel(3)->seconds();
        $form->call('send');
    }

    $form->assertHasErrors('delivery')->assertSet('message', contactFields()['message']);
    Mail::assertQueuedCount(3);
    $this->travel(1)->hours();
    $form->call('send')->assertHasNoErrors()->assertSet('isSent', true);
    Mail::assertQueuedCount(4);
});

it('preserves the fields and offers direct email when delivery fails without logging personal data', function (): void {
    Mail::shouldReceive('to->queue')->once()->andThrow(new TransportException('SMTP error for camille@example.com'));
    Log::spy();
    $form = Livewire::test(ContactForm::class)->set(contactFields());
    $this->travel(3)->seconds();
    $form->call('send')->assertHasErrors('delivery')->assertSet('isSent', false)
        ->assertSet('message', contactFields()['message'])->assertSee(config('institute.contact.email'));

    Log::shouldHaveReceived('error')->once()->with('Contact message delivery failed.', ['transport_error' => TransportException::class]);
});

it('delivers through the synchronous queue with escaped content and the configured sender', function (): void {
    Mail::swap(new MailManager($this->app));
    config(['mail.default' => 'array', 'mail.from.address' => 'contact@racines-lumiere.fr', 'queue.default' => 'database']);
    $form = Livewire::test(ContactForm::class)->set([...contactFields(), 'message' => '<script>alert("hello")</script> Bonjour !']);
    $this->travel(3)->seconds();
    expect(queryCount(fn () => $form->call('send')))->toBe(0);
    $form->assertHasNoErrors()->assertSet('isSent', true);

    $sent = Mail::mailer()->getSymfonyTransport()->messages();
    expect($sent)->toHaveCount(1);
    $message = $sent->first()->getOriginalMessage();
    expect($message->getFrom()[0]->getAddress())->toBe('contact@racines-lumiere.fr')
        ->and($message->getReplyTo()[0]->getAddress())->toBe('camille@example.com')
        ->and($message->getTo()[0]->getAddress())->toBe('team@example.com')
        ->and($message->getSubject())->toBe('Nouveau message de Camille Dupont via le site')
        ->and($message->getHtmlBody())->toContain('&lt;script&gt;')->not->toContain('<script>');
});
