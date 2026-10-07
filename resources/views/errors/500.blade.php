@extends('layouts.error', ['title' => 'Une erreur est survenue · Racines & Lumière'])

@section('content')
    <x-shared.layout.error-message code="500" title="Une erreur est survenue de notre côté.">
        <p>Réessayez dans quelques instants, ou écrivez-nous à <a href="mailto:{{ config('institute.contact.email') }}">{{ config('institute.contact.email') }}</a>.</p>
        <x-slot:actions>
            <a class="rl-button rl-button-terracotta" href="{{ route('home') }}">Revenir à l’accueil</a>
        </x-slot:actions>
    </x-shared.layout.error-message>
@endsection
