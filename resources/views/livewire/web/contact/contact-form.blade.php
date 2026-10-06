<div class="rl-contact-form" aria-live="polite">
    @if ($isSent)
        <div class="rl-contact-success" role="status">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true"><circle cx="24" cy="24" r="22" stroke="currentColor"/><path d="m14 24 7 7 14-15" stroke="currentColor" stroke-width="1.5"/></svg>
            <h3>Merci pour votre message.</h3>
            <p>Merci, votre message est bien parti. Nous vous répondons au plus vite.</p>
            <a href="{{ route('treatments') }}" class="rl-text-link">Découvrir nos soins</a>
        </div>
    @else
        <form wire:submit="send" novalidate aria-label="Écrire à Racines & Lumière">
            <div class="rl-contact-form-heading">
                <h3>Nous écrire</h3>
                <p>Tous les champs sont nécessaires, sauf le téléphone.</p>
            </div>
            <div class="rl-contact-fields">
                <div class="rl-contact-field">
                    <label for="contact-name">Votre nom</label>
                    <input id="contact-name" name="name" type="text" wire:model.blur="name" autocomplete="name" maxlength="100" required @error('name') aria-invalid="true" aria-describedby="contact-name-error" @enderror>
                    @error('name')<p id="contact-name-error" class="rl-contact-error">{{ $message }}</p>@enderror
                </div>
                <div class="rl-contact-field">
                    <label for="contact-email">Votre adresse e-mail</label>
                    <input id="contact-email" name="email" type="email" wire:model.blur="email" autocomplete="email" maxlength="255" required @error('email') aria-invalid="true" aria-describedby="contact-email-error" @enderror>
                    @error('email')<p id="contact-email-error" class="rl-contact-error">{{ $message }}</p>@enderror
                </div>
                <div class="rl-contact-field rl-contact-phone">
                    <label for="contact-phone">Votre téléphone <span>(facultatif)</span></label>
                    <input id="contact-phone" name="phone" type="tel" wire:model.blur="phone" autocomplete="tel" maxlength="30" @error('phone') aria-invalid="true" aria-describedby="contact-phone-error" @enderror>
                    @error('phone')<p id="contact-phone-error" class="rl-contact-error">{{ $message }}</p>@enderror
                </div>
                <div class="rl-contact-field rl-contact-message">
                    <label for="contact-message">Votre message</label>
                    <textarea id="contact-message" name="message" wire:model.blur="message" rows="4" maxlength="2000" required @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror></textarea>
                    @error('message')<p id="contact-message-error" class="rl-contact-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div hidden aria-hidden="true">
                <label for="contact-website">Site internet</label>
                <input id="contact-website" type="text" name="website" wire:model="website" tabindex="-1" autocomplete="off">
            </div>
            <label class="rl-contact-consent" for="contact-consent">
                <input id="contact-consent" type="checkbox" wire:model="consent" required @error('consent') aria-invalid="true" aria-describedby="contact-consent-error" @enderror>
                <span>J’accepte que ces informations soient utilisées pour répondre à ma demande.</span>
            </label>
            @error('consent')<p id="contact-consent-error" class="rl-contact-error">{{ $message }}</p>@enderror
            <a href="{{ route('legal.privacy') }}" class="rl-contact-privacy">Politique de confidentialité</a>
            @error('delivery')
                <p class="rl-contact-error rl-contact-delivery-error" role="alert">{{ $message }} <a href="mailto:{{ $institute->email }}">{{ $institute->email }}</a>.</p>
            @enderror
            <div class="rl-contact-form-end">
                <button type="submit" class="rl-button rl-button-terracotta" wire:loading.attr="disabled" wire:target="send">
                    <span wire:loading.remove wire:target="send">Envoyer</span>
                    <span wire:loading wire:target="send">Envoi en cours…</span>
                </button>
                <p>Votre message est transmis uniquement à notre équipe.</p>
            </div>
        </form>
        <noscript><p>Activez JavaScript pour utiliser ce formulaire, ou écrivez-nous à <a href="mailto:{{ $institute->email }}">{{ $institute->email }}</a>.</p></noscript>
    @endif
</div>
