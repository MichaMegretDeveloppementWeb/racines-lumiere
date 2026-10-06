{{-- A welcome to Sciez --}}
<x-web.layout.page-intro labelledby="contact-title" class="rl-contact-opening">
    <div class="rl-contact-opening-copy">
        <p class="rl-contact-opening-label">Contact</p>
        <h1 id="contact-title">Retrouvons-nous<br>à Sciez.</h1>
        <p class="rl-page-intro-lead">Un premier soin, une question, une envie de prendre du temps pour vous. Nous sommes à votre écoute.</p>
        <div class="rl-contact-opening-links">
            <a href="#write-title" class="rl-button rl-button-terracotta">Nous écrire</a>
            <a href="#visit-title" class="rl-text-link">Préparer ma venue</a>
        </div>
    </div>
    <div class="rl-contact-opening-image" aria-hidden="true">
        <x-web.media.picture name="story/hero" :widths="[800, 1280, 1600]" sizes="(min-width: 48rem) 60vw, 100vw" alt="" :width="1280" :height="720" is-priority class="size-full object-cover" />
    </div>
</x-web.layout.page-intro>
