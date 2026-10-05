<?php

declare(strict_types=1);

it('forbids every crawler outside production', function (): void {
    $response = $this->get(route('robots'));

    $response->assertOk()->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    expect($response->getContent())->toBe("User-agent: *\nDisallow: /\n");
});

it('allows every crawler in production, and points them to the sitemap', function (): void {
    config(['app.url' => 'https://racines-lumiere.fr']);
    app()->detectEnvironment(fn (): string => 'production');

    $response = $this->get('http://www.example.test/robots.txt');

    $response->assertOk()->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    expect($response->getContent())->toBe("User-agent: *\nAllow: /\n\nSitemap: https://racines-lumiere.fr/sitemap.xml\n");
});
