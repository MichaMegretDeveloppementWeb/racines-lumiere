<?php

declare(strict_types=1);

namespace App\Providers;

use App\Data\Institute\InstituteData;
use App\Services\Institute\InstituteService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as RenderedView;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(
            InstituteData::class,
            fn (Application $app): InstituteData => $app->make(InstituteService::class)->details(),
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());

        // Resolved once per request, and only when a page of the site is actually rendered.
        View::composer(['layouts.web', 'components.web.*', 'web.*', 'livewire.web.*'], function (RenderedView $view): void {
            $view->with('institute', $this->app->make(InstituteData::class));
        });
    }
}
