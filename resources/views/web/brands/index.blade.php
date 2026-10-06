@extends('layouts.web', [
    'title' => 'Nos marques de soin partenaires · Racines & Lumière',
    'description' => 'Altearah Bio, Comfort Zone, Laboté, Gingerly, ILSE, Skin Diligent, Demain Beauty : les marques choisies par Racines & Lumière à Sciez.',
    'breadcrumb' => 'Nos marques partenaires',
    'pageType' => 'CollectionPage',
])

@section('styles')
    @vite('resources/css/web/brands/index.css')
@endsection

@section('scripts')
    @vite('resources/js/web/brands/index.js')
@endsection

@section('content')
    @include('web.brands.partials.opening')
    @include('web.brands.partials.gallery')
@endsection
