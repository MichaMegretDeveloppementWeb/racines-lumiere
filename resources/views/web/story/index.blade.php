@extends('layouts.web', [
    'title' => 'Aurore et Lorie, notre Maison du Mieux-Être · Racines & Lumière',
    'description' => 'Aurore et Lorie, co-fondatrices de Racines & Lumière : une rencontre, une amitié et une même vision du soin holistique, à Sciez.',
    'breadcrumb' => 'Notre histoire',
    'pageType' => 'AboutPage',
])

@section('styles')
    @vite('resources/css/web/story/index.css')
@endsection

@section('content')
    @include('web.story.partials.opening')
    @include('web.story.partials.welcome')
    @include('web.story.partials.place')
    @include('web.story.partials.name')
    @include('web.story.partials.founders')
    @include('web.story.partials.signature')
@endsection
