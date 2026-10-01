<?php

declare(strict_types=1);

use Database\Seeders\DatabaseSeeder;

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

dataset('opening states', [
    'before the opening' => [
        false,
        ["Prochainement, l'ouverture de notre Maison du Mieux-Être", 'Réserver dès maintenant', 'Ouverture le mardi 3 novembre · puis sur rendez-vous, du lundi au samedi'],
        ['Réserver mon rituel'],
    ],
    'after the opening' => [
        true,
        ['Réserver mon rituel', 'Sur rendez-vous, du lundi au samedi'],
        ['Prochainement', 'Réserver dès maintenant', 'Ouverture le mardi 3 novembre'],
    ],
]);

it('renders the home page within its layout, in both states', function (bool $isOpen, array $shownTexts, array $hiddenTexts): void {
    config(['institute.is_open' => $isOpen]);

    $response = $this->get(route('home'))
        ->assertOk()
        ->assertSee('<title>Racines &amp; Lumière · Institut de beauté holistique à Sciez</title>', false)
        ->assertSee('<main id="content"', false);

    foreach ($shownTexts as $text) {
        $response->assertSee($text, false);
    }
    foreach ($hiddenTexts as $text) {
        $response->assertDontSee($text, false);
    }

    // What does not depend on the opening stays the same in both states.
    $response->assertSeeInOrder(['Vous ne choisissez pas votre soin.', 'maison de soin holistique', 'Nos soins', 'Ce que vous en dites', 'Offrir un rituel', 'Nous trouver'], false);
})->with('opening states');

it('shows the featured categories and the reviews from the database', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['Nos rituels corps', 'Notre rituel Visage &amp; Âme', 'Nos rituels complets Corps &amp; Visage', 'Nos traitements visage', 'Nos singuliers'], false)
        ->assertDontSee('Les suppléments d&#039;Âme', false)
        ->assertSee(route('treatments').'#rituels-corps', false)
        ->assertSee('Sampaio')
        ->assertSee('href="'.config('institute.booksy_profile_url').'"', false);
});

it('hides the launch offer while its discount is unknown', function (): void {
    $this->get(route('home'))->assertOk()->assertDontSee('Offre de lancement');
});

it('shows the launch offer before the opening, once its discount is known', function (): void {
    config(['institute.launch_offer' => ['discount' => '15 %', 'conditions' => 'Valable sur tous les rituels.']]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Offre de lancement')
        ->assertSee('15 %')
        ->assertSee('Valable sur tous les rituels.');
});

it('never shows the launch offer after the opening', function (): void {
    config(['institute.is_open' => true, 'institute.launch_offer' => ['discount' => '15 %', 'conditions' => null]]);

    $this->get(route('home'))->assertOk()->assertDontSee('Offre de lancement');
});

it('renders the home page in three queries', function (): void {
    // Trusted circle presence (shared by every page), featured categories, visible reviews.
    expect(queryCount(fn () => $this->get(route('home'))->assertOk()))->toBe(3);
});
