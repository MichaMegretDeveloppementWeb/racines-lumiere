<?php

declare(strict_types=1);

it('asks crawlers not to index any response outside production', function (): void {
    $this->get(route('home'))->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    $this->get('/up')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
});

it('lets crawlers index in production', function (): void {
    app()->detectEnvironment(fn (): string => 'production');

    $this->get(route('home'))
        ->assertHeaderMissing('X-Robots-Tag')
        ->assertDontSee('<meta name="robots" content="noindex, nofollow">', false);
});
