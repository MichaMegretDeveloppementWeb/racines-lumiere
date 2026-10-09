<?php

declare(strict_types=1);

use Database\Seeders\DatabaseSeeder;

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('renders the whole menu in both opening states', function (bool $isOpen): void {
    config(['institute.is_open' => $isOpen]);

    $this->get(route('treatments'))
        ->assertOk()
        ->assertViewIs('web.treatments.index')
        ->assertSee('<h1 id="menu-title">Nos soins</h1>', false)
        ->assertSeeInOrder([
            'Nos rituels signature',
            'Chez nous, un soin commence bien avant la cabine et se termine bien après.',
            'Deux rituels ne se ressemblent jamais',
            'Nos rituels corps',
            'Nos rituels visage',
            'Nos rituels complets Corps &amp; Visage',
            'Nos traitements visage',
            'Nos singuliers',
            'Les suppléments d&#039;Âme',
            'L&#039;art de l&#039;épilation',
        ], false)
        ->assertSee('30&nbsp;minutes supplémentaires', false);
})->with(['before the opening' => false, 'after the opening' => true]);

it('writes each price line with its total duration first, the care time within it, then its price', function (): void {
    $response = $this->get(route('treatments'))->assertOk();

    $response->assertSeeInOrder(['Pause essentielle', '1h15', "dont 45\u{00A0}min de soin", "95\u{00A0}€"])
        ->assertSeeInOrder(['Kobido', '1h', "120\u{00A0}€", '1h30', 'avec soin visage', "150\u{00A0}€"])
        ->assertSeeInOrder(['Yeux', "10\u{00A0}€"]);

    expect(substr_count($response->getContent(), '<li class="rl-variant">'))->toBe(68);
});

it('anchors every category, and lists them all at the top of the page', function (): void {
    $response = $this->get(route('treatments'))->assertOk();

    foreach (['rituels-corps', 'rituel-visage-et-ame', 'rituels-complets', 'traitements-visage', 'singuliers', 'supplements-d-ame', 'epilation'] as $slug) {
        $response->assertSee('id="'.$slug.'"', false)->assertSee('href="#'.$slug.'"', false);
    }
});

it('lists the waxing prices in five folded groups, present in the page from the start', function (): void {
    $response = $this->get(route('treatments'))->assertOk();

    expect(substr_count($response->getContent(), '<details class="rl-treatment-group">'))->toBe(5);
    $response->assertSeeText('Retrouvez toutes nos épilations et leurs tarifs directement sur notre page de réservation en ligne.')
        ->assertSeeInOrder(['Épilations femmes', 'Maillot brésilien', "20\u{00A0}min", "25\u{00A0}€", 'Forfaits femmes', 'Épilations hommes', 'Torse', 'Épilations au fil', 'Visage complet', 'Épilations au sucre', 'Maillot intégral', "40\u{00A0}min"]);
});

it('opens Booksy in a new tab from every category', function (): void {
    $content = $this->get(route('treatments'))->assertOk()->getContent();
    $categoryButton = '#<div class="rl-category-booking">\s*<a href="'.preg_quote(config('institute.booking_url'), '#').'" target="_blank" rel="noopener"#';

    expect(preg_match_all($categoryButton, $content))->toBe(7);
});

it('shows the launch offer only before the opening, once its discount is known', function (bool $isOpen): void {
    config([
        'institute.is_open' => $isOpen,
        'institute.launch_offer' => ['discount' => '15 %', 'conditions' => null],
    ]);

    $response = $this->get(route('treatments'))->assertOk();

    if ($isOpen) {
        $response->assertDontSeeText('Offre de lancement');
    } else {
        $response->assertSee('<h2>Offre de lancement</h2>', false)->assertSeeText('15 % de remise');
    }
})->with(['before the opening' => false, 'after the opening' => true]);

it('renders the menu in four queries', function (): void {
    // Trusted circle presence, then the menu: categories, treatments and price lines, one query each.
    expect(queryCount(fn () => $this->get(route('treatments'))->assertOk()))->toBe(4);
});
