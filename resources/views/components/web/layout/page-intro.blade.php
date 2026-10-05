@props(['labelledby'])

<section aria-labelledby="{{ $labelledby }}" {{ $attributes->class(['rl-panel', 'rl-panel-wide', 'rl-page-intro']) }}>
    <div class="rl-page-intro-texture" aria-hidden="true">
        <x-web.media.picture name="home/linen" :widths="[480, 960]" sizes="100vw" alt="" :width="480" :height="320" is-priority class="size-full object-cover" />
    </div>
    <div class="rl-wrap rl-page-intro-inner">
        {{ $slot }}
    </div>
</section>
