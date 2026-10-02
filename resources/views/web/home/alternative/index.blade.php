@extends('layouts.web-alternative')

@section('title', 'Racines & Lumière · Institut de beauté holistique à Sciez')
@section('description', $institute->isOpen
    ? 'Institut de beauté holistique à Sciez, en Chablais : rituels sur mesure pour le corps et le visage, massages et soins experts. Réservation en ligne.'
    : 'Institut de beauté holistique à Sciez : rituels sur mesure pour le corps et le visage. Ouverture le 3 novembre, réservation en ligne dès maintenant.')

@section('styles')
    @vite('resources/css/web/home/alternative/index.css')
@endsection

@section('header')
    @include('web.home.alternative.partials.header')
@endsection

@section('content')
    @include('web.home.alternative.partials.hero')
    @unless ($institute->isOpen)
        @include('web.home.alternative.partials.opening')
    @endunless
    @include('web.home.alternative.partials.concept')
    @include('web.home.alternative.partials.ritual')
    @include('web.home.alternative.partials.treatments')
    @include('web.home.alternative.partials.brands')
    @include('web.home.alternative.partials.quote')
    @include('web.home.alternative.partials.founders')
    @if ($reviewSummary !== null)
        @include('web.home.alternative.partials.reviews')
    @endif
    @include('web.home.alternative.partials.gift-cards')
    @include('web.home.alternative.partials.visit')
@endsection

@section('footer')
    @include('web.home.alternative.partials.footer')
    {{-- Temporary local comparison tool: remove its view, CSS, JS, preview fonts and unused images before publication. --}}
    @env('local')
        @include('web.home.alternative.partials.design-preview')
    @endenv
@endsection
