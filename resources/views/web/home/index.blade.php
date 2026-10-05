@extends('layouts.web')

@section('title', 'Racines & Lumière · Institut de beauté holistique à Sciez')
@section('description', $institute->isOpen
    ? 'Institut de beauté holistique à Sciez, en Chablais : rituels sur mesure pour le corps et le visage, massages et soins experts. Réservation en ligne.'
    : 'Institut de beauté holistique à Sciez : rituels sur mesure pour le corps et le visage. Ouverture le 3 novembre, réservation en ligne dès maintenant.')

@section('styles')
    @vite('resources/css/web/home/index.css')
@endsection

@section('content')
    @include('web.home.partials.hero')
    @unless ($institute->isOpen)
        @include('web.home.partials.opening')
    @endunless
    @include('web.home.partials.concept')
    @include('web.home.partials.ritual')
    @include('web.home.partials.treatments')
    @include('web.home.partials.brands')
    @include('web.home.partials.quote')
    @include('web.home.partials.founders')
    @if ($reviewSummary !== null)
        @include('web.home.partials.reviews')
    @endif
    @include('web.home.partials.faq')
    @include('web.home.partials.gift-cards')
    @include('web.home.partials.visit')
@endsection
