@props(['current' => null])

@if (config('preview.enabled'))
    <nav {{ $attributes->merge(['class' => 'home-version-switch', 'aria-label' => 'Comparer les accueils']) }}>
        <a href="{{ route('home') }}" @if ($current === 'home') aria-current="page" @endif>Accueil</a>
        <a href="{{ route('home.alternative') }}" @if ($current === 'alternative') aria-current="page" @endif>Accueil alternatif</a>
    </nav>
@endif
