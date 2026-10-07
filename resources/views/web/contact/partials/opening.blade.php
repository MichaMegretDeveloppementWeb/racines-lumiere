{{-- A welcome to Sciez --}}
<x-web.layout.page-intro labelledby="contact-title" class="rl-contact-opening">
    <div class="rl-contact-opening-copy">
        <x-web.layout.breadcrumb current="Contact" />
        <h1 id="contact-title">Retrouvons-nous<br>à Sciez.</h1>
        <p class="rl-page-intro-lead">Un premier soin, une question, une envie de prendre du temps pour vous. Nous sommes à votre écoute.</p>
    </div>
    <div class="rl-contact-opening-image" aria-hidden="true">
        <x-web.media.picture name="contact/hero" :widths="[640, 960, 1536]" sizes="(min-width: 48rem) 66vw, 100vw" alt="" :width="1536" :height="1024" is-priority class="size-full object-cover" />
    </div>
</x-web.layout.page-intro>
