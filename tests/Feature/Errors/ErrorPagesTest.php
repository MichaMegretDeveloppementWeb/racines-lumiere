<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

it('keeps a missing page a 404 with navigation and no search metadata for the missing address', function (): void {
    $this->app['env'] = 'production';
    config(['app.debug' => false]);

    $this->get('/page-qui-n-existe-pas')->assertNotFound()
        ->assertSee('Cette page n’existe pas, ou plus.')
        ->assertSee('Revenir à l’accueil')->assertSee('Voir nos soins')
        ->assertSee('Menu principal')->assertSee(config('institute.booking_url'))
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
        ->assertDontSee('application/ld+json', false)->assertDontSee('rel="canonical"', false);
});

it('renders a useful 500 page even when the database connection fails', function (): void {
    config([
        'app.debug' => false,
        'institute.contact.email' => 'help@example.com',
        'database.connections.unavailable' => array_replace(config('database.connections.mariadb'), [
            'url' => null, 'host' => '127.0.0.1', 'port' => 1,
        ]),
    ]);
    Route::get('/test-database-failure', function (): void {
        DB::connection('unavailable')->select('SELECT 1');
    });

    // The failed connection is separate; rendering the fallback must make no query on the site's connection.
    expect(queryCount(function (): void {
        $this->get('/test-database-failure')->assertStatus(500)
            ->assertSee('Une erreur est survenue de notre côté.')
            ->assertSee('help@example.com')->assertSee('Revenir à l’accueil')
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertDontSee('SQLSTATE')->assertDontSee('Stack trace')
            ->assertDontSee('application/ld+json', false)->assertDontSee('Menu principal');
    }))->toBe(0);
});

it('does not expose exception details through the error page', function (): void {
    config(['app.debug' => false]);
    Route::get('/test-server-failure', fn () => throw new RuntimeException('Private diagnostic detail'));

    $this->get('/test-server-failure')->assertStatus(500)
        ->assertSee('Réessayez dans quelques instants')
        ->assertDontSee('Private diagnostic detail')->assertDontSee('RuntimeException');
});

it('preserves JSON error responses for callers that request JSON', function (): void {
    config(['app.debug' => false]);

    $this->getJson('/page-qui-n-existe-pas')->assertNotFound()
        ->assertHeader('Content-Type', 'application/json')
        ->assertDontSee('<!DOCTYPE html>', false);
});
