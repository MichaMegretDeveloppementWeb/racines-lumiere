{{-- Booking has its own destination --}}
<section class="rl-wrap rl-contact-booking" aria-labelledby="contact-booking-title">
    <div>
        <h2 id="contact-booking-title" class="rl-title">Votre prochain rendez-vous&nbsp;?</h2>
        <p class="rl-prose">Choisissez votre rituel et votre créneau directement sur Booksy.</p>
    </div>
    <x-web.booking.button>{{ $institute->isOpen ? 'Réserver mon rituel' : 'Réserver dès maintenant' }}</x-web.booking.button>
</section>
