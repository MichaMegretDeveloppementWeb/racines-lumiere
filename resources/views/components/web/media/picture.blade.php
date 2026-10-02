<picture>
    @if ($portraitName !== null)
        @foreach ($portraitFormats() as $format)
            <source media="(orientation: portrait)" type="{{ $mediaTypeOf($format) }}" srcset="{{ $srcsetFor($format, true) }}" sizes="100vw">
        @endforeach
    @endif
    @foreach ($sourceFormats() as $format)
        <source type="{{ $mediaTypeOf($format) }}" srcset="{{ $srcsetFor($format) }}" sizes="{{ $sizes }}">
    @endforeach
    <img
        src="{{ $fallbackUrl() }}"
        srcset="{{ $srcsetFor($fallback) }}"
        sizes="{{ $sizes }}"
        alt="{{ $alt }}"
        width="{{ $width }}"
        height="{{ $height }}"
        @if ($isPriority)
            fetchpriority="high"
        @else
            loading="lazy"
        @endif
        decoding="async"
        {{ $attributes }}
    >
</picture>
