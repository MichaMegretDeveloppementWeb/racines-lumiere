<?php

declare(strict_types=1);

use App\Livewire\Web\Contact\ContactForm;

it('offers directions and a working contact form without embedding a third party map', function (): void {
    $this->get(route('contact'))->assertOk()
        ->assertSeeLivewire(ContactForm::class)
        ->assertSee('205 avenue des Charmes')
        ->assertSee('74140 Sciez')
        ->assertSee('www.google.com/maps/dir/')
        ->assertSee('www.waze.com/ul?ll=46.330093,6.375758', false)
        ->assertSee('maps.apple.com/')
        ->assertSee('openstreetmap.org/copyright')
        ->assertSee('ContactPage')
        ->assertSee('window.livewireScriptConfig', false)
        ->assertDontSee('<iframe', false)
        ->assertDontSee('Cette page est en préparation.');
});

it('only announces the opening while the institute is not open', function (bool $isOpen): void {
    config(['institute.is_open' => $isOpen]);
    $response = $this->get(route('contact'))->assertOk();

    if ($isOpen) {
        $response->assertDontSee('Ouverture le mardi 3 novembre.');
    } else {
        $response->assertSee('Ouverture le mardi 3 novembre.');
    }
})->with([true, false]);

it('only offers a telephone link when a number has been confirmed', function (?string $phone): void {
    config(['institute.contact.phone' => $phone]);
    $response = $this->get(route('contact'))->assertOk();

    if ($phone === null) {
        $response->assertDontSee('href="tel:', false)
            ->assertSee('Vous préférez nous écrire directement')
            ->assertDontSee('nous appeler');
    } else {
        $response->assertSee('href="tel:0450000000"', false)
            ->assertSee('Vous préférez nous écrire ou nous appeler directement');
    }
})->with([null, '04 50 00 00 00']);

it('writes the name of the institute with its accent on the map', function (): void {
    $map = file_get_contents(public_path('images/contact/map.svg'));

    expect($map)->toContain('Racines &amp; Lumière')
        ->and($map)->toContain('Carte issue des données OpenStreetMap')
        ->and($map)->not->toContain('?');
});

it('versions the address of the map, so that a corrected map reaches browsers that kept the old one', function (): void {
    $version = filemtime(public_path('images/contact/map.svg'));

    $this->get(route('contact'))->assertOk()->assertSee('src="/images/contact/map.svg?v='.$version.'"', false);
});

it('gives the line of the institute', function (): void {
    $this->get(route('contact'))->assertOk()
        ->assertSee('href="tel:0480958509"', false)
        ->assertSee('04 80 95 85 09');
});
