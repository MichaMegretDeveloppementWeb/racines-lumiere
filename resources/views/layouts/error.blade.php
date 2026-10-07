<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
        <meta name="robots" content="noindex, nofollow">
        <meta name="theme-color" content="#FCF8EC">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        @vite(['resources/css/web.css', 'resources/css/error.css', 'resources/css/components/shared/layout/error-message.css'])
    </head>
    <body>
        <header class="rl-error-header">
            <a href="{{ route('home') }}" aria-label="Racines & Lumière, accueil">
                <img src="/images/brand/monogram-gold-96w.webp" width="64" height="64" alt="">
                <span>Racines &amp; Lumière</span>
            </a>
        </header>
        <main id="content">
            @yield('content')
        </main>
    </body>
</html>
