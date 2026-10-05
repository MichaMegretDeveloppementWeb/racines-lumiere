<section aria-labelledby="treatments-title" class="rl-panel rl-treatments">
    <div class="rl-wrap">
        <div class="rl-section-heading">
            <h2 id="treatments-title" class="rl-title">Nos soins</h2>
            <a href="{{ route('treatments') }}" class="rl-text-link">Découvrir la carte des soins</a>
        </div>
        <ul class="rl-arches">
            @foreach ($featuredCategories as $category)
                <li class="rl-arch">
                    <a href="{{ route('treatments') }}#{{ $category->slug }}">
                        <span class="rl-arch-image">
                            <x-web.media.picture :name="'treatments/'.$category->slug" :widths="[320, 640]" sizes="(min-width: 80rem) 224px, (min-width: 64rem) 17vw, (min-width: 40rem) 40vw, 64vw" alt="" :width="320" :height="469" class="size-full object-cover" />
                            <span class="rl-arch-number" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </span>
                        <h3 class="rl-arch-name">{{ $category->name }}</h3>
                        @if ($category->subtitle !== null)
                            <span class="rl-arch-subtitle">{{ $category->subtitle }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
