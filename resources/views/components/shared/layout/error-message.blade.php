@props(['code', 'title'])

<section class="rl-panel rl-panel-wide rl-error-message" aria-labelledby="error-title">
    <img class="rl-error-texture" src="{{ asset('images/home/linen-960w.webp') }}" alt="" width="960" height="640">
    <div class="rl-error-copy">
        <p class="rl-error-code">Erreur {{ $code }}</p>
        <h1 id="error-title">{{ $title }}</h1>
        <div class="rl-error-description">{{ $slot }}</div>
        <div class="rl-error-actions">{{ $actions }}</div>
    </div>
</section>
