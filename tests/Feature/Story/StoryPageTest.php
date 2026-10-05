<?php

declare(strict_types=1);

it('tells the story of the house in the order of the brief, in both opening states', function (bool $isOpen): void {
    config(['institute.is_open' => $isOpen]);

    $this->get(route('story'))
        ->assertOk()
        ->assertViewIs('web.story.index')
        ->assertSeeInOrder([
            '<h1 id="story-title">',
            'Notre histoire',
            'Aurore <span class="rl-story-opening-ampersand">&amp;</span> Lorie',
            '</h1>',
            'Une&nbsp;rencontre, une&nbsp;amitié, une&nbsp;même&nbsp;vision.',
            'Maison de beauté holistique à Sciez',
            'Bienvenue dans notre Maison du Mieux-Être&nbsp;!',
            'Ici, pas de carte à suivre à la lettre, pas de protocole répété.',
            'Vous repartez ancrée, apaisée, rechargée',
            'Le sens de notre nom',
            '<h3>Racines</h3>',
            '<h3>Lumière</h3>',
            'Qui sommes-nous',
            '<h3 id="aurore-title">Aurore</h3>',
            "L'esthétique m'a toujours attirée par sa façon de prendre soin des autres.",
            'pour des rituels vraiment faits pour vous.',
            '<h3 id="lorie-title">Lorie</h3>',
            'Après mon bac, je ne savais pas encore quel métier je voulais exercer.',
            "<p>Et je n'ai pas fini d'apprendre.",
            'Notre rencontre',
            "C'est au spa, où nous avons travaillé ensemble plusieurs années, que nous nous sommes rencontrées",
            'là où vous en êtes.',
            'Aurore &amp; Lorie',
        ], false);
})->with(['before the opening' => false, 'after the opening' => true]);

it('tells the meeting in both their voices, apart from the portraits, which now weigh the same', function (): void {
    $content = $this->get(route('story'))->assertOk()->getContent();

    preg_match_all('#<article aria-labelledby="(aurore|lorie)-title".*?<div class="rl-prose">(.*?)</div>#s', $content, $portraits);
    [$aurore, $lorie] = array_map(fn (string $text): int => count(preg_split('/\s+/', trim(strip_tags(html_entity_decode($text))))), $portraits[2]);

    expect($portraits[1])->toBe(['aurore', 'lorie'])
        ->and(abs($aurore - $lorie))->toBeLessThan(20)
        ->and($content)->not->toContain("qu'Aurore et moi")
        ->and($portraits[2][1])->not->toContain("C'est au spa");
});

it('sets a sentence of each founder apart, hidden from screen readers since the text says it again', function (): void {
    $this->get(route('story'))
        ->assertOk()
        ->assertSeeInOrder([
            '<h3 id="aurore-title">Aurore</h3>',
            'aria-hidden="true">«&nbsp;L\'exigence du spa de luxe rencontre la profondeur du soin holistique.&nbsp;»',
            '<h3 id="lorie-title">Lorie</h3>',
            'aria-hidden="true">«&nbsp;Pour moi, un soin est plus qu\'un massage.&nbsp;»',
        ], false);
});

it('closes on a booking button worded for the moment, and a way to the menu', function (bool $isOpen, string $label): void {
    config(['institute.is_open' => $isOpen]);

    $this->get(route('story'))
        ->assertOk()
        ->assertSeeInOrder([
            'Notre rencontre',
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

it('carries the atmosphere of the house through its pictures, with no section listing materials', function (): void {
    $response = $this->get(route('story'))->assertOk();

    $response->assertDontSee("L'esprit du lieu", false)
        ->assertDontSee('Le béton ciré')
        ->assertDontSee('/images/story/place-', false);

    foreach (['aurore-480w', 'lorie-480w', 'meeting-1280w'] as $picture) {
        $response->assertSee('/images/story/'.$picture.'.jpg', false);
        expect(public_path('images/story/'.$picture.'.jpg'))->toBeFile();
    }
});

it('presents no picture as a portrait of the founders', function (): void {
    preg_match_all('#<img\s+src="[^"]*/images/story/[^"]+"[^>]*alt="([^"]*)"#', $this->get(route('story'))->assertOk()->getContent(), $pictures);

    expect($pictures[1])->toHaveCount(3)->each->toBe('');
});

it('renders the story in one query', function (): void {
    // Whether the trusted circle has a visible partner, for the menu: the story itself is written in the page.
    expect(queryCount(fn () => $this->get(route('story'))->assertOk()))->toBe(1);
});
