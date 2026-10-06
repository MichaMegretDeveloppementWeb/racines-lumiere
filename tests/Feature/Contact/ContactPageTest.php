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
        $response->assertDontSee('href="tel:', false);
    } else {
        $response->assertSee('href="tel:0450000000"', false);
    }
})->with([null, '04 50 00 00 00']);
