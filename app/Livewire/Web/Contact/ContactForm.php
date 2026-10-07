<?php

declare(strict_types=1);

namespace App\Livewire\Web\Contact;

use App\Data\Contact\ContactMessageData;
use App\Exceptions\Contact\ContactDeliveryException;
use App\Services\Contact\ContactMessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public bool $consent = false;

    public string $website = '';

    #[Locked]
    public int $openedAt;

    #[Locked]
    public bool $isSent = false;

    public function mount(): void
    {
        $this->openedAt = now()->getTimestamp();
    }

    public function send(ContactMessageService $messages): void
    {
        if ($this->isSent) {
            return;
        }

        $this->resetErrorBag('delivery');

        $this->name = trim($this->name);
        $this->email = trim($this->email);
        $this->phone = trim($this->phone);
        $this->message = trim($this->message);
        $validated = $this->validate();

        if ($this->website !== '' || now()->getTimestamp() - $this->openedAt < 3) {
            $this->confirm();

            return;
        }

        $rateKey = 'contact:'.hash('sha256', (string) request()->ip());

        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            $this->addError('delivery', 'Vous avez déjà envoyé plusieurs messages. Réessayez dans une heure, ou écrivez-nous directement à');

            return;
        }

        RateLimiter::hit($rateKey, 3600);
        $this->deliver($messages, new ContactMessageData(
            name: trim($validated['name']),
            email: trim($validated['email']),
            phone: trim($validated['phone'] ?? '') === '' ? null : trim($validated['phone']),
            message: trim($validated['message']),
        ));
    }

    private function deliver(ContactMessageService $messages, ContactMessageData $contact): void
    {
        try {
            $messages->send($contact);
        } catch (ContactDeliveryException $failure) {
            Log::error($failure->getMessage(), ['transport_error' => $failure->transportError]);
            $this->addError('delivery', $failure->getUserMessage());

            return;
        }

        $this->confirm();
    }

    private function confirm(): void
    {
        $this->reset('name', 'email', 'phone', 'message', 'consent', 'website');
        $this->isSent = true;
    }

    /** @return array<string, list<string>> */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[^\\r\\n]+$/u'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9 +.\\-]+$/'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'consent' => ['accepted'],
        ];
    }

    /** @return array<string, string> */
    protected function messages(): array
    {
        return [
            'name.required' => 'Indiquez votre nom.',
            'name.string' => 'Indiquez votre nom.',
            'name.min' => 'Votre nom doit compter entre 2 et 100 caractères.',
            'name.max' => 'Votre nom doit compter entre 2 et 100 caractères.',
            'name.regex' => 'Indiquez votre nom sur une seule ligne.',
            'email.required' => 'Indiquez votre adresse e-mail.',
            'email.email' => "Cette adresse e-mail n'est pas valide.",
            'email.max' => "Cette adresse e-mail n'est pas valide.",
            'phone.string' => "Ce numéro de téléphone n'est pas valide.",
            'phone.max' => "Ce numéro de téléphone n'est pas valide.",
            'phone.regex' => "Ce numéro de téléphone n'est pas valide.",
            'message.required' => 'Écrivez votre message.',
            'message.string' => 'Écrivez votre message.',
            'message.min' => 'Votre message doit compter entre 10 et 2 000 caractères.',
            'message.max' => 'Votre message doit compter entre 10 et 2 000 caractères.',
            'consent.accepted' => 'Cochez la case pour que nous puissions vous répondre.',
        ];
    }

    public function render(): View
    {
        return view('livewire.web.contact.contact-form');
    }
}
