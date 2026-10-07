@props(['labelledby', 'breadcrumb', 'imageName', 'imageWidths', 'imageWidth', 'imageHeight', 'imagePosition' => '70% 60%', 'mobileImagePosition' => null])

<section aria-labelledby="{{ $labelledby }}" {{ $attributes->class(['rl-panel', 'rl-panel-wide', 'rl-page-intro']) }} style="--rl-intro-image-position: {{ $imagePosition }}; --rl-intro-mobile-image-position: {{ $mobileImagePosition ?? $imagePosition }};">
    <div class="rl-page-intro-texture" aria-hidden="true">
        <x-web.media.picture name="home/linen" :widths="[480, 960]" sizes="100vw" alt="" :width="480" :height="320" is-priority class="size-full object-cover" />
    </div>
    <div class="rl-wrap rl-page-intro-inner">
        <div class="rl-page-intro-copy">
            <h1 id="{{ $labelledby }}">{{ $slot }}</h1>
            <p class="rl-page-intro-lead">{{ $lead }}</p>
        </div>
        <x-web.layout.breadcrumb :current="$breadcrumb" />
    </div>
    <div class="rl-page-intro-image" aria-hidden="true">
        <x-web.media.picture :name="$imageName" :widths="$imageWidths" sizes="(min-width: 48rem) 66vw, 100vw" alt="" :width="$imageWidth" :height="$imageHeight" is-priority class="size-full object-cover" />
    </div>
</section>
