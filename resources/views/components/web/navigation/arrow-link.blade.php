@props(['href', 'isExternal' => false])

<a href="{{ $href }}"@if ($isExternal) target="_blank" rel="noopener"@endif {{ $attributes->class('group relative inline-flex min-h-11 items-center gap-2.5 text-[0.9375rem] font-semibold tracking-[0.02em] text-forest transition-colors after:absolute after:inset-x-0 after:bottom-2 after:h-px after:bg-current hover:text-terracotta') }}>
    <span>{{ $slot }}@if ($isExternal)<span class="sr-only"> (nouvel onglet)</span>@endif</span>
    <x-web.media.icon name="arrow" class="size-4 text-terracotta transition-transform group-hover:translate-x-0.5" />
</a>
