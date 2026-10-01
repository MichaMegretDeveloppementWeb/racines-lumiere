<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        @vite(['resources/css/web.css', 'resources/js/web.js'])
    </head>
    <body class="bg-cream text-forest antialiased">
        <main id="content">
            @yield('content')
        </main>
    </body>
</html>
