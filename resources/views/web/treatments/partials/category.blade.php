<section id="{{ $category->slug }}" aria-labelledby="{{ $category->slug }}-title" @class([
    'rl-category',
    'rl-category-pictured' => $category->isFeatured,
    'rl-category-compact' => ! $category->isFeatured && $category->treatments !== [],
    'rl-category-note' => $category->treatments === [],
])>
    @if ($category->isFeatured)
        <div class="rl-category-picture" aria-hidden="true">
            <x-web.media.picture :name="'treatments/'.$category->slug" :widths="[320, 640]" sizes="(min-width: 64rem) 240px, (min-width: 48rem) 176px, 42vw" alt="" :width="320" :height="469" class="size-full object-cover" />
            <span class="rl-category-number">{{ sprintf('%02d', $category->number) }}</span>
        </div>
    @elseif ($category->treatments === [])
        <div class="rl-texture" aria-hidden="true">
            <x-web.media.picture name="home/linen" :widths="[480, 960]" sizes="(min-width: 80rem) 1216px, 90vw" alt="" :width="480" :height="320" class="size-full object-cover" />
        </div>
    @endif
    <div class="rl-category-body">
        <header class="rl-category-header">
            <h2 id="{{ $category->slug }}-title" class="rl-category-name">{{ $category->name }}</h2>
            @if ($category->subtitle !== null)
                <p class="rl-category-subtitle">{{ $category->subtitle }}</p>
            @endif
        </header>
        @if ($category->descriptionParagraphs !== [])
            <div class="rl-category-description">
                @foreach ($category->descriptionParagraphs as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        @endif
        @if ($category->treatments !== [])
            <ul class="rl-treatment-list">
                @foreach ($category->treatments as $treatment)
                    @include('web.treatments.partials.treatment', ['treatment' => $treatment])
                @endforeach
            </ul>
        @endif
        <div class="rl-category-booking">
            <x-web.booking.button :tone="($isOnDarkGround ?? false) || $category->treatments === [] ? 'cream' : 'terracotta'">Réserver</x-web.booking.button>
        </div>
    </div>
</section>
