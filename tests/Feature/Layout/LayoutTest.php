<?php

declare(strict_types=1);

it('offers a way to skip straight to the content', function (): void {
    $this->get(route('story'))
        ->assertOk()
        ->assertSee('href="#content"', false)
        ->assertSee('Aller au contenu');
});

it('keeps the booking button in the header, opening Booksy in a new tab', function (): void {
    $this->get(route('story'))
        ->assertOk()
        ->assertSee('href="'.config('institute.booking_url').'" target="_blank" rel="noopener"', false)
        ->assertSee('Réserver');
});

it('links the main pages from the menu', function (): void {
    $this->get(route('story'))
        ->assertOk()
        ->assertSeeInOrder([route('treatments'), route('brands'), route('story'), route('contact')], false)
        ->assertSee('aria-current="page"', false)
        ->assertSeeText('Nos marques partenaires')
        ->assertDontSee('>Nos marques<', false);
});

it('gives the footer its contact details and legal links', function (): void {
    $this->get(route('story'))
        ->assertOk()
        ->assertSee('205 avenue des Charmes')
        ->assertSee('href="mailto:'.config('institute.contact.email').'"', false)
        ->assertSee('href="'.config('institute.contact.instagram_url').'"', false)
        ->assertSee(route('legal.notice'), false)
        ->assertSee(route('legal.privacy'), false)
        ->assertSee('© 2026 Racines &amp; Lumière', false);
});

it('points gift cards at the booking page while their direct link is unknown', function (): void {
    $this->get(route('story'))
        ->assertOk()
        ->assertSeeInOrder(['href="'.config('institute.booking_url').'"', 'Offrir une carte cadeau'], false);
});

it('points gift cards at their own page once its link is known', function (): void {
    config(['institute.gift_cards_url' => 'https://example.com/gift-cards']);

    $this->get(route('story'))->assertOk()->assertSee('href="https://example.com/gift-cards"', false);
});

it('declares the favicons', function (): void {
    $this->get(route('story'))
        ->assertOk()
        ->assertSee('<link rel="icon" href="/favicon.svg" type="image/svg+xml">', false)
        ->assertSee('<link rel="apple-touch-icon" href="/apple-touch-icon.png">', false)
        ->assertSee('<link rel="manifest" href="/site.webmanifest">', false);
});

it('leaves the phone number out until the line is active', function (): void {
    $this->get(route('story'))->assertOk()->assertDontSee('href="tel:', false);
});

it('shows the phone number once the line is active', function (): void {
    config(['institute.contact.phone' => '04 50 00 00 00']);

    $this->get(route('story'))
        ->assertOk()
        ->assertSee('href="tel:0450000000"', false)
        ->assertSee('04 50 00 00 00');
});
