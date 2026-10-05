@props(['href' => null, 'tone' => 'terracotta'])

<a href="{{ $href ?? $institute->bookingUrl }}" target="_blank" rel="noopener" {{ $attributes->class(['rl-button', 'rl-button-'.$tone]) }}>{{ $slot }}<span class="sr-only"> sur Booksy (nouvel onglet)</span></a>
