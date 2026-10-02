@props(['title'])

<section class="mx-auto flex max-w-3xl flex-col items-center px-6 pt-44 pb-28 text-center lg:pt-52 lg:pb-40">
    <x-web.media.icon name="sun" class="size-7 text-gold" />
    <h1 class="mt-6 text-4xl leading-tight lg:text-5xl">{{ $title }}</h1>
    <p class="mt-5 text-olive">Cette page est en préparation.</p>
    <x-web.navigation.arrow-link :href="route('home')" class="mt-6">Revenir à l'accueil</x-web.navigation.arrow-link>
</section>
