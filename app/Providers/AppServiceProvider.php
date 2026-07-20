<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!app()->environment('local', 'testing')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::addPersistentMiddleware([
                \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
                \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
            ]);
        }
    }
}
