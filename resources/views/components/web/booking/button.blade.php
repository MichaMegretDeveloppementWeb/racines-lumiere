@props(['label' => 'Réserver', 'href' => null, 'tone' => 'terracotta', 'size' => 'regular', 'hasArrow' => true])

<x-web.navigation.pill-link :href="$href ?? $institute->bookingUrl" :tone="$tone" :size="$size" :has-arrow="$hasArrow" is-external {{ $attributes }}>{{ $label }}</x-web.navigation.pill-link>
