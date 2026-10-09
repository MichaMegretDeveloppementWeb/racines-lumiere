<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Opening State
    |--------------------------------------------------------------------------
    |
    | Whether the institute has opened its doors. It switches the texts that
    | differ before and after the opening, and it is the only value here read
    | from the environment.
    |
    */

    'is_open' => (bool) env('INSTITUTE_OPEN', false),

    'opening_date' => '2026-11-03',

    /*
    |--------------------------------------------------------------------------
    | Booking
    |--------------------------------------------------------------------------
    |
    | Every booking happens on Booksy. The profile page carries the reviews,
    | and the gift cards link stays null while no direct link is known.
    |
    */

    'booking_url' => 'https://racines-lumiere-rituels.booksy.com/a/',

    'booksy_profile_url' => 'https://booksy.com/fr-fr/67263_racines-lumiere_instituts-de-beaute_121598_veigy-foncenex',

    'gift_cards_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Launch Offer
    |--------------------------------------------------------------------------
    |
    | Shown before the opening only, and only once its discount is known.
    |
    */

    'launch_offer' => [
        'discount' => null,
        'conditions' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    */

    'contact' => [
        'email' => 'contact@racines-lumiere.fr',
        'form_recipient' => 'contact@racines-lumiere.fr',
        'phone' => null,
        'instagram_url' => 'https://www.instagram.com/racinesetlumiere.sciez/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Location And Opening Hours
    |--------------------------------------------------------------------------
    */

    'address' => [
        'street' => '205 avenue des Charmes',
        'postal_code' => '74140',
        'city' => 'Sciez',
        'access_note' => "Derrière le Leclerc, à l'entrée de la nouvelle résidence « Rive Sud ».",
    ],

    'opening_hours' => [
        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
        'opens' => '09:00',
        'closes' => '19:00',
    ],

    'geo' => [
        'latitude' => 46.330093,
        'longitude' => 6.375758,
    ],

    /*
    |--------------------------------------------------------------------------
    | Treatment Menu Brochure
    |--------------------------------------------------------------------------
    |
    | Public path of the downloadable treatment menu, null while it is missing.
    |
    */

    'brochure_path' => null,

];
