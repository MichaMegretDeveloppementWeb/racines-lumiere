@extends('layouts.web', ['title' => 'Page introuvable · Racines & Lumière', 'isErrorPage' => true])

@section('styles')
    @vite('resources/css/components/shared/layout/error-message.css')
@endsection

@section('content')
    <x-shared.layout.error-message code="404" title="Cette page n’existe pas, ou plus.">
        <p>Retrouvez notre maison et les soins que nous imaginons pour vous.</p>
        <x-slot:actions>
            <a class="rl-button rl-button-terracotta" href="{{ route('home') }}">Revenir à l’accueil</a>
            <a class="rl-text-link" href="{{ route('treatments') }}">Voir nos soins</a>
        </x-slot:actions>
    </x-shared.layout.error-message>
@endsection
