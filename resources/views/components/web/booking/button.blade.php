@props(['label' => 'Réserver', 'href' => null, 'onDark' => false])

<a href="{{ $href ?? $institute->bookingUrl }}" target="_blank" rel="noopener" {{ $attributes->class([
    'inline-flex min-h-11 items-center justify-center px-5 py-2.5 text-center text-sm font-semibold tracking-[0.12em] uppercase transition-colors',
    'bg-terracotta text-cream hover:bg-forest' => ! $onDark,
    'bg-gold text-forest hover:bg-sand' => $onDark,
]) }}>{{ $label }}<span class="sr-only"> (nouvel onglet)</span></a>
