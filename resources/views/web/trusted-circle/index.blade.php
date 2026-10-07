@extends('layouts.web', [
    'title' => 'Nos praticiens recommandés en Chablais · Racines & Lumière',
    'description' => 'Kinésiologue, ostéopathe, thérapeutes, conseillère en image : les praticiens du Chablais en qui Racines & Lumière a confiance.',
    'breadcrumb' => 'Cercle de confiance',
    'pageType' => 'CollectionPage',
    'mainEntityFragment' => 'practitioners',
])

@section('styles')
    @vite('resources/css/web/trusted-circle/index.css')
@endsection

@section('content')
    @include('web.trusted-circle.partials.opening')
    @include('web.trusted-circle.partials.practitioners')
    @include('web.trusted-circle.partials.invitation')
@endsection
