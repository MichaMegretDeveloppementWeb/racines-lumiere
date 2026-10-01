<section class="px-4 py-20 text-center lg:py-28" aria-labelledby="visit-title">
    <div class="mx-auto flex max-w-xl flex-col items-center gap-5">
        <h2 id="visit-title" class="text-2xl text-terracotta sm:text-3xl">Nous trouver</h2>
        <address class="text-lg not-italic">
            {{ $institute->street }}, {{ $institute->postalCode }} {{ $institute->city }}
            @if ($institute->accessNote !== null)
                <span class="mt-1 block text-base text-olive">{{ $institute->accessNote }}</span>
            @endif
        </address>
        <x-web.institute.opening-hours />
        <a href="{{ route('contact') }}" class="inline-flex min-h-11 items-center px-3 font-semibold text-terracotta underline underline-offset-4">Accès et contact</a>
    </div>
</section>
