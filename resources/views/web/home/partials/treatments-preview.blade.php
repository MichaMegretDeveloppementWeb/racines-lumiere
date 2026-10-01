<section class="bg-sand/35 px-4 py-20 lg:py-28" aria-labelledby="treatments-preview-title">
    <div class="mx-auto max-w-6xl">
        <h2 id="treatments-preview-title" class="text-center text-2xl text-terracotta sm:text-3xl">Nos soins</h2>

        <ul class="mt-12 flex flex-wrap justify-center gap-4">
            @foreach ($featuredCategories as $category)
                <li class="w-full sm:w-[calc(50%-0.5rem)] lg:w-[calc(33.333%-0.75rem)]">
                    <a
                        href="{{ route('treatments') }}#{{ $category->slug }}"
                        class="flex h-full min-h-32 flex-col justify-center gap-2 border border-sand bg-cream px-6 py-8 text-center transition-colors hover:border-terracotta"
                    >
                        <span class="font-display text-lg tracking-[0.04em] text-terracotta">{{ $category->name }}</span>
                        @if ($category->subtitle !== null)
                            <span class="text-olive">{{ $category->subtitle }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <p class="mt-10 text-center">
            <a href="{{ route('treatments') }}" class="inline-flex min-h-11 items-center px-3 font-semibold text-terracotta underline underline-offset-4">Découvrir la carte des soins</a>
        </p>
    </div>
</section>
