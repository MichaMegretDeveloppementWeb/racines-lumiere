<?php

declare(strict_types=1);

it('forbids every crawler outside production', function (): void {
    $response = $this->get(route('robots'));

    $response->assertOk()->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    expect($response->getContent())->toBe("User-agent: *\nDisallow: /\n");
});

it('allows every crawler in production', function (): void {
    app()->detectEnvironment(fn (): string => 'production');

    $response = $this->get(route('robots'));

    $response->assertOk()->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    expect($response->getContent())->toBe("User-agent: *\nAllow: /\n");
});
