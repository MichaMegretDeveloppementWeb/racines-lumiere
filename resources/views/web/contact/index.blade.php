@extends('layouts.web', [
    'title' => 'Nous trouver et nous écrire à Sciez · Racines & Lumière',
    'description' => 'Racines & Lumière, 205 avenue des Charmes à Sciez. Sur rendez-vous du lundi au samedi. Accès, horaires et formulaire de contact.',
    'breadcrumb' => 'Contact',
    'pageType' => 'ContactPage',
])

@section('styles')
    @vite('resources/css/web/contact/index.css')
@endsection

@section('scripts')
    @vite('resources/js/web/contact/index.js')
@endsection

@section('head')
    @livewireStyles
@endsection

@section('content')
    @include('web.contact.partials.opening')
    @include('web.contact.partials.write')
    @include('web.contact.partials.visit')
    @include('web.contact.partials.booking')
@endsection

@section('body-end')
    @livewireScriptConfig
@endsection
