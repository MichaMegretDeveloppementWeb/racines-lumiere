<?php

declare(strict_types=1);

use App\Models\Review;
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
        $response->assertSeeText($text, false);
    }
    foreach ($hiddenTexts as $text) {
        $response->assertDontSeeText($text, false);
    }

    // What does not depend on the opening stays the same in both states.
    $response->assertSeeTextInOrder(['Vous ne choisissez pas votre soin.', 'maison de soin holistique', 'Nos soins', 'Ce que vous en dites', 'Offrir un rituel', 'Nous trouver']);
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

it('sums up the visible reviews as their average rating and their number', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('5/5')
        ->assertSeeText('6 avis Booksy');
});

it('leaves the rating out when no review is visible', function (): void {
    Review::query()->update(['is_visible' => false]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSeeText('/5')
        ->assertDontSeeText('avis Booksy');
});

it('loads the main image first, with its vertical crop for upright screens, and the other photos lazily', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<source media="(orientation: portrait)" type="image/avif" srcset="'.asset('images/home/hero-portrait-600w.avif'), false)
        ->assertSeeInOrder([
            'src="'.asset('images/home/hero-960w.jpg').'"',
            'fetchpriority="high"',
            'src="'.asset('images/home/concept-1-240w.jpg').'"',
            'loading="lazy"',
        ], false)
        ->assertSee('src="'.asset('images/treatments/rituels-corps-320w.jpg').'"', false);
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
