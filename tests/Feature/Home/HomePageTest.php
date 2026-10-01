<?php

declare(strict_types=1);

dataset('opening states', [
    'before the opening' => [
        false,
        ['Ouverture le mardi 3 novembre · puis sur rendez-vous, du lundi au samedi', 'Réserver dès maintenant'],
        ['Réserver mon rituel'],
    ],
    'after the opening' => [
        true,
        ['Sur rendez-vous, du lundi au samedi', 'Réserver mon rituel'],
        ['Ouverture le mardi 3 novembre', 'Réserver dès maintenant'],
    ],
]);

it('renders the home page within its layout', function (bool $isOpen, array $shownTexts, array $hiddenTexts): void {
    config(['institute.is_open' => $isOpen]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<html lang="fr">', false)
        ->assertSee('<title>Racines &amp; Lumière · Institut de beauté holistique à Sciez</title>', false)
        ->assertSee('<h1', false)
        ->assertSee('href="'.config('institute.booking_url').'"', false)
        ->assertSeeInOrder($shownTexts)
        ->assertDontSee($hiddenTexts);
})->with('opening states');
