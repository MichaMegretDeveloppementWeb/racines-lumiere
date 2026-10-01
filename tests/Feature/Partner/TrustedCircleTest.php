<?php

declare(strict_types=1);

use App\Models\Partner;
use App\Services\Partner\TrustedCircleService;

it('has no trusted circle while every partner is hidden', function (): void {
    Partner::factory()->count(2)->create(['is_visible' => false]);

    expect(app(TrustedCircleService::class)->hasVisiblePartners())->toBeFalse();

    $this->get(route('trusted-circle'))->assertNotFound();
    $this->get(route('home'))->assertOk()->assertDontSee(route('trusted-circle'));
});

it('opens the trusted circle as soon as one partner is visible', function (): void {
    Partner::factory()->create(['is_visible' => false]);
    Partner::factory()->create(['is_visible' => true]);

    expect(app(TrustedCircleService::class)->hasVisiblePartners())->toBeTrue();

    $this->get(route('trusted-circle'))->assertOk()->assertSee('<main id="content"', false);
    $this->get(route('home'))->assertOk()->assertSee('href="'.route('trusted-circle').'"', false);
});
