<x-web.layout.page-intro labelledby="brands-title" class="rl-brands-opening">
    <div class="rl-brands-opening-copy">
        <x-web.layout.breadcrumb current="Nos marques partenaires" />
        <p class="rl-brands-eyebrow">La beauté, bien entourée</p>
        <h1 id="brands-title">Nos marques<br>partenaires.</h1>
        <p class="rl-page-intro-lead">Des savoir-faire singuliers, une même attention portée à vous.</p>
    </div>
    <div class="rl-brands-opening-image" aria-hidden="true">
        <x-web.media.picture name="brands/hero" :widths="[640, 960, 1536]" sizes="(min-width: 48rem) 66vw, 100vw" alt="" :width="1536" :height="1024" is-priority class="size-full object-cover" />
    </div>
</x-web.layout.page-intro>
