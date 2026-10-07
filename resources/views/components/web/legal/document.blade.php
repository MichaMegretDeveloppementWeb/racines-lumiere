@props(['title', 'introduction'])

<header class="rl-panel rl-panel-wide rl-legal-heading">
    <x-web.media.texture />
    <div class="rl-wrap">
        <h1>{{ $title }}</h1>
        <p>{{ $introduction }}</p>
        <x-web.layout.breadcrumb :current="$title" />
    </div>
</header>
<div class="rl-wrap rl-legal-document">
    {{ $slot }}
</div>
