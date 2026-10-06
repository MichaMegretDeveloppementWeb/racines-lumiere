{{-- Write to the founders --}}
<section class="rl-panel rl-contact-write" aria-labelledby="write-title">
    <x-web.media.texture />
    <div class="rl-contact-write-inner">
        <div class="rl-contact-write-copy">
            <p class="rl-contact-kicker">À l’écoute de vos envies</p>
            <h2 id="write-title" class="rl-title">Tout commence<br>par un échange.</h2>
            <p>Vous hésitez entre deux soins, souhaitez offrir un rituel ou avez une question&nbsp;? Prenez le temps de nous en parler.</p>
            <p class="rl-contact-signature">Aurore &amp; Lorie</p>
            <div class="rl-contact-direct">
                <p>Vous préférez nous écrire directement&nbsp;?</p>
                <a href="mailto:{{ $institute->email }}">{{ $institute->email }}</a>
                @if ($institute->phone !== null)
                    <a href="tel:{{ str_replace(' ', '', $institute->phone) }}">{{ $institute->phone }}</a>
                @endif
            </div>
        </div>
        <livewire:web.contact.contact-form />
    </div>
</section>
