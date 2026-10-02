<a href="{{ $href }}"@if ($isExternal) target="_blank" rel="noopener"@endif {{ $attributes->class(['inline-flex items-center justify-center gap-4 rounded-full text-center font-semibold tracking-[0.02em] transition-colors duration-300', $classes()]) }}>
    <span>{{ $slot }}@if ($isExternal)<span class="sr-only"> (nouvel onglet)</span>@endif</span>
    @if ($hasArrow)
        <span @class(['grid size-10 shrink-0 place-items-center rounded-full', $dotClasses()])>
            <x-web.media.icon name="arrow" class="size-4" />
        </span>
    @endif
</a>
