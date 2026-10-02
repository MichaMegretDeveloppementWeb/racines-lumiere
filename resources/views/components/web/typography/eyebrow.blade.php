@props(['as' => 'p', 'onDark' => false, 'hasSun' => true])

<{{ $as }} {{ $attributes->class([
    'flex items-center gap-3 font-display text-xs leading-snug tracking-[0.3em] uppercase',
    'text-terracotta' => ! $onDark,
    'text-honey' => $onDark,
]) }}>
    @if ($hasSun)
        <x-web.media.icon name="sun" class="size-4 shrink-0 text-gold" />
    @endif
    <span>{{ $slot }}</span>
</{{ $as }}>
