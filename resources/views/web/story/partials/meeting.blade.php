<section aria-labelledby="meeting-title" class="rl-panel rl-meeting">
    <x-web.media.texture />
    <div class="rl-meeting-image" aria-hidden="true">
        <x-web.media.picture name="story/meeting" :widths="[800, 1280, 1920]" sizes="100vw" alt="" :width="1280" :height="720" portrait-name="story/meeting-portrait" :portrait-widths="[480, 960]" class="size-full object-cover" />
    </div>
    <div class="rl-wrap rl-meeting-inner">
        <div class="rl-meeting-text">
            <h2 id="meeting-title">Notre rencontre</h2>
            <p>C'est au spa, où nous avons travaillé ensemble plusieurs années, que nous nous sommes rencontrées et que notre amitié est née. Nous partagions la même vision du soin, mais le cadre d'un institut classique ne nous permettait pas de la vivre pleinement. L'idée de Racines &amp; Lumière est venue tout naturellement&nbsp;: créer un lieu qui nous ressemble, où nous sommes libres d'imaginer des moments qui vous correspondent, là où vous en êtes.</p>
            <p class="rl-meeting-signature">Aurore &amp; Lorie</p>
            <div class="rl-meeting-actions">
                <x-web.booking.button tone="cream">{{ $institute->isOpen ? 'Réserver mon rituel' : 'Réserver dès maintenant' }}</x-web.booking.button>
                <a href="{{ route('treatments') }}" class="rl-text-link">Découvrir nos soins</a>
            </div>
        </div>
    </div>
</section>
