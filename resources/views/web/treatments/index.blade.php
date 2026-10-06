@extends('layouts.web', [
    'title' => 'Carte des soins et tarifs à Sciez · Racines & Lumière',
    'description' => 'Carte des soins de Racines & Lumière à Sciez : rituels corps et visage, Kobido, massage facial, soins Comfort Zone, épilations. Tarifs et réservation en ligne.',
    'breadcrumb' => 'Nos soins',
])

@section('styles')
    @vite('resources/css/web/treatments/index.css')
@endsection

@section('content')
    @include('web.treatments.partials.intro')
    <x-web.institute.launch-offer heading="h2" class="rl-wrap rl-menu-offer" />
    @include('web.treatments.partials.signature')

    <div class="rl-wrap rl-category-group">
        @foreach ($menu->signatureCategories as $category)
            @include('web.treatments.partials.category', ['category' => $category])
        @endforeach
    </div>

    @if ($menu->featuredCategories !== [])
        <div class="rl-panel rl-category-panel">
            <x-web.media.texture />
            <div class="rl-wrap rl-category-group">
                @foreach ($menu->featuredCategories as $category)
                    @include('web.treatments.partials.category', ['category' => $category, 'isDetailedMenu' => true])
                @endforeach
            </div>
        </div>
    @endif

    <div class="rl-wrap rl-category-group rl-category-group-last">
        @foreach ($menu->additionalCategories as $category)
            @include('web.treatments.partials.category', ['category' => $category])
        @endforeach
    </div>
@endsection
