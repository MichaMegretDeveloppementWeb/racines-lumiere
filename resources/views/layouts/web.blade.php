<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <x-web.layout.head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <x-web.seo.metadata :title="$title" :description="$description" />

        <link rel="icon" href="/favicon.ico" sizes="32x32">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#FCF8EC">

        @vite(['resources/css/web.css', 'resources/css/components/web/layout/header.css', 'resources/css/components/web/layout/footer.css'])
        @hasSection('scripts')
            @yield('scripts')
        @else
            @vite('resources/js/web.js')
        @endif
        @yield('head')
        @yield('styles')
        <link rel="preload" href="{{ Vite::asset('resources/fonts/cinzel-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

        <x-web.seo.structured-data :page-type="$pageType ?? 'WebPage'" :title="$title" :description="$description" :breadcrumb="$breadcrumb ?? null" :nodes="$pageStructuredData ?? []" />
    </x-web.layout.head>
    <body class="antialiased">
        <x-web.media.icons />
        <x-web.layout.skip-link />
        <x-web.layout.header />

        <main id="content" tabindex="-1" class="focus:outline-none">
            @yield('content')
        </main>

        <x-web.layout.footer />
        @yield('body-end')
    </body>
</html>
