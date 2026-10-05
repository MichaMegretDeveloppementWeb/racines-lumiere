<div class="rl-panel rl-signature">
    <div class="rl-wrap rl-signature-inner">
        <p class="rl-signature-names">Aurore &amp; Lorie</p>
        <div class="rl-signature-actions">
            <x-web.booking.button>{{ $institute->isOpen ? 'Réserver mon rituel' : 'Réserver dès maintenant' }}</x-web.booking.button>
            <a href="{{ route('treatments') }}" class="rl-text-link">Découvrir nos soins</a>
        </div>
    </div>
</div>
