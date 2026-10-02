<section aria-labelledby="treatments-title" class="alt-panel alt-treatments">
    <div class="alt-wrap">
        <div class="alt-section-heading">
            <h2 id="treatments-title" class="alt-title">Nos soins</h2>
            <a href="{{ route('treatments') }}" class="alt-text-link">Découvrir la carte des soins</a>
        </div>
        <ul class="alt-care-grid">
            @foreach ($featuredCategories as $category)
                <li class="alt-care">
                    <a href="{{ route('treatments') }}#{{ $category->slug }}">
                        <span class="alt-care-image">
                            <x-web.media.picture :name="'home/alternative/'.$category->slug" :widths="$loop->iteration <= 3 ? [320, 640] : [160, 320]" :sizes="$loop->iteration <= 3 ? '(min-width: 80rem) 386px, (min-width: 48rem) 30vw, 90vw' : '(min-width: 64rem) 120px, 84px'" alt="" :width="320" :height="$loop->iteration <= 3 ? 240 : 320" class="size-full object-cover" />
                        </span>
                        <div class="alt-care-copy">
                            <h3 class="alt-care-title">{{ $category->name }}</h3>
                            @if ($category->subtitle !== null)
                                <span class="alt-care-subtitle">{{ $category->subtitle }}</span>
                            @endif
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
