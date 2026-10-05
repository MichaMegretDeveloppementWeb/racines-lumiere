@props(['title'])

<section class="rl-wrap flex min-h-[70svh] flex-col items-center justify-center pt-36 pb-24 text-center lg:pt-44 lg:pb-32">
    <h1 class="rl-title">{{ $title }}</h1>
    <p class="mt-5 text-olive">Cette page est en préparation.</p>
    <a href="{{ route('home') }}" class="rl-text-link mt-3">Revenir à l'accueil</a>
</section>
