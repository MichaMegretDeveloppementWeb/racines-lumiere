@props(['name'])

<svg {{ $attributes->merge(['aria-hidden' => 'true', 'focusable' => 'false']) }}><use href="#icon-{{ $name }}"></use></svg>
