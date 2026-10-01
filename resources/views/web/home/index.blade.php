@extends('layouts.web')

@section('title', 'Racines & Lumière · Institut de beauté holistique à Sciez')

@section('content')
    <div class="mx-auto flex min-h-screen max-w-xl flex-col items-center justify-center gap-6 px-6 text-center">
        <h1 class="text-4xl tracking-widest text-terracotta uppercase">Racines &amp; Lumière</h1>

        @if ($institute->isOpen)
            <p>Sur rendez-vous, du lundi au samedi</p>
        @else
            <p>Ouverture le mardi 3 novembre · puis sur rendez-vous, du lundi au samedi</p>
        @endif

        <a
            href="{{ $institute->bookingUrl }}"
            class="inline-flex min-h-11 items-center bg-terracotta px-6 py-3 text-cream"
        >{{ $institute->isOpen ? 'Réserver mon rituel' : 'Réserver dès maintenant' }}</a>
    </div>
@endsection
