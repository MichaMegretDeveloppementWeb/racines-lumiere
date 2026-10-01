@props(['title'])

<section class="mx-auto flex max-w-3xl flex-col items-center gap-6 px-4 py-24 text-center lg:py-32">
    <h1 class="text-3xl text-terracotta lg:text-4xl">{{ $title }}</h1>
    <p>Cette page est en préparation.</p>
    <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center px-3 font-semibold text-terracotta underline underline-offset-4">Revenir à l'accueil</a>
</section>
