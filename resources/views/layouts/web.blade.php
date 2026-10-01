<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <meta name="description" content="@yield('description')">

        <link rel="icon" href="/favicon.ico" sizes="32x32">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#FCF8EC">

        <link rel="preload" href="{{ Vite::asset('resources/fonts/cinzel-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
        @vite(['resources/css/web.css', 'resources/js/web.js'])
    </head>
    <body class="flex min-h-screen flex-col antialiased">
        <x-web.layout.skip-link />
        <x-web.layout.header />

        <main id="content" tabindex="-1" class="flex-1 focus:outline-none">
            @yield('content')
        </main>

        <x-web.layout.footer />
    </body>
</html>
