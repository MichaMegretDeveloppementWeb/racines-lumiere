<?php

declare(strict_types=1);

use App\Models\Review;
use Database\Seeders\DatabaseSeeder;

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('renders the main home in both opening states', function (bool $isOpen): void {
    config(['institute.is_open' => $isOpen]);

    $response = $this->get(route('home'))
        ->assertOk()
        ->assertViewIs('web.home.index')
        ->assertSee('<main id="content"', false)
        ->assertSee('href="mailto:'.config('institute.contact.email').'"', false)
        ->assertSeeTextInOrder([
            "Votre rituel n'est pas écrit,",
            'nous le créons avec vous.',
            'maison de soin holistique à Sciez.',
            'Nos soins',
            'Une adresse confidentielle',
            'Ce que vous en dites',
            'Avant votre rituel',
            'Offrir un rituel',
            'Nous trouver',
        ]);

    if ($isOpen) {
        $response->assertSeeText('Réserver mon rituel')
            ->assertSeeText('Sur rendez-vous, du lundi au samedi')
            ->assertDontSeeText('Prochainement')
            ->assertDontSeeText('Réserver dès maintenant')
            ->assertDontSeeText('Ouverture le mardi 3 novembre');
    } else {
        $response->assertSeeText("Prochainement, l'ouverture de notre Maison du Mieux-Être", false)
            ->assertSeeText('Réserver dès maintenant')
            ->assertSeeText('Ouverture le mardi 3 novembre')
            ->assertDontSeeText('Réserver mon rituel');
    }
})->with(['before the opening' => false, 'after the opening' => true]);

it('renders the featured categories and visible reviews from the database', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['Nos rituels corps', 'Nos rituels visage', 'Nos rituels complets Corps &amp; Visage', 'Nos traitements visage', 'Nos singuliers'], false)
        ->assertDontSee('Les suppléments d&#039;Âme', false)
        ->assertSee(route('treatments').'#rituels-corps', false)
        ->assertSeeText('Sampaio')
        ->assertSee('href="#reviews-title"', false)
        ->assertSeeText('5/5')
        ->assertSeeText('6 avis Booksy');
});

it('presents the featured categories as aligned arches, each with its photo and its anchor', function (): void {
    $slugs = ['rituels-corps', 'rituel-visage-et-ame', 'rituels-complets', 'traitements-visage', 'singuliers'];
    $expected = [];
    foreach ($slugs as $slug) {
        $expected[] = 'href="'.route('treatments').'#'.$slug.'"';
        $expected[] = 'src="'.asset("images/treatments/{$slug}-320w.jpg").'"';
    }

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('class="rl-arches"', false)
        ->assertSeeInOrder($expected, false);
});

it('leaves the review section out when no review is visible', function (): void {
    Review::query()->update(['is_visible' => false]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSeeText('Ce que vous en dites')
        ->assertDontSeeText('avis Booksy')
        ->assertDontSee('href="#reviews-title"', false);
});

it('provides accessible carousel controls while rendering every visible review', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('aria-label="Avis précédents"', false)
        ->assertSee('aria-label="Avis suivants"', false)
        ->assertSee('aria-controls="review-track"', false)
        ->assertSee('aria-live="polite"', false)
        ->assertSee('data-review-clone aria-hidden="true" inert', false)
        ->assertSeeText('Sampaio')
        ->assertSeeText('6 avis Booksy');
});

it('omits carousel controls when there is only one visible review', function (): void {
    Review::query()->update(['is_visible' => false]);
    Review::factory()->create(['is_visible' => true]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('1 avis Booksy')
        ->assertSee('id="review-track"', false)
        ->assertDontSee('data-review-clone', false)
        ->assertDontSee('aria-controls="review-track"', false);
});

it('uses the configured Booksy destinations for booking and gifts', function (): void {
    config([
        'institute.booking_url' => 'https://booksy.com/reservation',
        'institute.gift_cards_url' => 'https://booksy.com/cartes-cadeaux',
        'institute.booksy_profile_url' => 'https://booksy.com/avis',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="https://booksy.com/reservation" target="_blank" rel="noopener"', false)
        ->assertSee('href="https://booksy.com/cartes-cadeaux" target="_blank" rel="noopener"', false)
        ->assertSee('href="https://booksy.com/avis" target="_blank" rel="noopener"', false);
});

it('hides the launch offer until its discount is known', function (): void {
    $this->get(route('home'))->assertOk()->assertDontSeeText('Offre de lancement');
});

it('shows a configured launch offer only before the opening', function (bool $isOpen): void {
    config([
        'institute.is_open' => $isOpen,
        'institute.launch_offer' => ['discount' => '15 %', 'conditions' => 'Valable sur tous les rituels.'],
    ]);

    $response = $this->get(route('home'))->assertOk();

    if ($isOpen) {
        $response->assertDontSeeText('Offre de lancement')->assertDontSeeText('15 %');
    } else {
        $response->assertSeeText('Offre de lancement')
            ->assertSeeText('15 %')
            ->assertSeeText('Valable sur tous les rituels.');
    }
})->with(['before the opening' => false, 'after the opening' => true]);

it('renders the main home in three queries', function (): void {
    // Trusted circle presence, featured categories and visible reviews are loaded once.
    expect(queryCount(fn () => $this->get(route('home'))->assertOk()))->toBe(3);
});
