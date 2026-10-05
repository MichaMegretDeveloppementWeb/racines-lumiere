<?php

declare(strict_types=1);

it('tells the story of the house in the order of the brief, in both opening states', function (bool $isOpen): void {
    config(['institute.is_open' => $isOpen]);

    $this->get(route('story'))
        ->assertOk()
        ->assertViewIs('web.story.index')
        ->assertSee('<h1 id="story-title">Notre histoire</h1>', false)
        ->assertSeeInOrder([
            'Une&nbsp;rencontre, une&nbsp;amitié, une&nbsp;même&nbsp;vision.',
            'Bienvenue dans notre Maison du Mieux-Être&nbsp;!',
            'Ici, pas de carte à suivre à la lettre, pas de protocole répété.',
            'Vous repartez ancrée, apaisée, rechargée',
            "L'esprit du lieu",
            'Le sens de notre nom',
            '<h3>Racines</h3>',
            '<h3>Lumière</h3>',
            'Entre les deux, nos rituels.',
            'Qui sommes-nous',
            '<h3 id="aurore-title">Aurore</h3>',
            "L'esthétique m'a toujours attirée par sa façon de prendre soin des autres.",
            'pour des rituels vraiment faits pour vous.',
            '<h3 id="lorie-title">Lorie</h3>',
            'Après mon bac, je ne savais pas encore quel métier je voulais exercer.',
            "C'est au spa, où nous avons travaillé ensemble plusieurs années",
            'Aurore &amp; Lorie',
        ], false);
})->with(['before the opening' => false, 'after the opening' => true]);

it('closes on a booking button worded for the moment, and a way to the menu', function (bool $isOpen, string $label): void {
    config(['institute.is_open' => $isOpen]);

    $this->get(route('story'))
        ->assertOk()
        ->assertSeeInOrder([
            'Aurore &amp; Lorie',
            'href="'.config('institute.booking_url').'" target="_blank" rel="noopener"',
            $label,
            'href="'.route('treatments').'"',
            'Découvrir nos soins',
        ], false);
})->with([
    'before the opening' => [false, 'Réserver dès maintenant'],
    'after the opening' => [true, 'Réserver mon rituel'],
]);

it('shows the place through its materials, each picture served with the page', function (): void {
    $response = $this->get(route('story'))->assertOk();

    $response->assertSeeInOrder(['Le lin', 'Le béton ciré', 'La pierre', 'Le bois']);

    foreach (['aurore-480w', 'lorie-480w', 'place-1-320w', 'place-2-320w', 'place-3-320w', 'place-4-320w'] as $picture) {
        $response->assertSee('/images/story/'.$picture.'.jpg', false);
        expect(public_path('images/story/'.$picture.'.jpg'))->toBeFile();
    }
});

it('presents no picture as a portrait of the founders', function (): void {
    preg_match_all('#<img\s+src="[^"]*/images/story/[^"]+"[^>]*alt="([^"]*)"#', $this->get(route('story'))->assertOk()->getContent(), $pictures);

    expect($pictures[1])->toHaveCount(6)->each->toBe('');
});

it('renders the story in one query', function (): void {
    // Whether the trusted circle has a visible partner, for the menu: the story itself is written in the page.
    expect(queryCount(fn () => $this->get(route('story'))->assertOk()))->toBe(1);
});
