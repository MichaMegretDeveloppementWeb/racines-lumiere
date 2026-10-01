@extends('layouts.web')

@section('title', 'Racines & Lumière · Institut de beauté holistique à Sciez')
@section('description', $institute->isOpen
    ? 'Institut de beauté holistique à Sciez, en Chablais : rituels sur mesure pour le corps et le visage, massages et soins experts. Réservation en ligne.'
    : 'Institut de beauté holistique à Sciez : rituels sur mesure pour le corps et le visage. Ouverture le 3 novembre, réservation en ligne dès maintenant.')

@section('content')
    {{-- Hero --}}
    @include('web.home.partials.hero')

    @unless ($institute->isOpen)
        {{-- Opening banner --}}
        @include('web.home.partials.opening-banner')
    @endunless

    {{-- Launch offer --}}
    <x-web.institute.launch-offer class="mx-4 my-10 sm:mx-auto sm:max-w-2xl" />

    {{-- Concept --}}
    @include('web.home.partials.concept')

    {{-- Treatments preview --}}
    @include('web.home.partials.treatments-preview')

    {{-- Reviews --}}
    @include('web.home.partials.reviews')

    {{-- Gift cards --}}
    @include('web.home.partials.gift-cards')

    {{-- Visit --}}
    @include('web.home.partials.visit')
@endsection
