@props(['current'])

<nav aria-label="Fil d’Ariane" {{ $attributes->class(['rl-breadcrumb']) }}>
    <ol>
        <li><a href="{{ route('home') }}">Accueil</a></li>
        <li aria-hidden="true">/</li>
        <li aria-current="page">{{ $current }}</li>
    </ol>
</nav>
