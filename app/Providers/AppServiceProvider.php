<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ContractCreated;
use App\Events\ContractRenewed;
use App\Events\PaymentReceived;
use App\Listeners\LogContractActivity;
use App\Listeners\CreateRenewalReminder;
use App\Listeners\InvalidateDashboardCache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\WhatsApp\WhatsAppProvider::class, function ($app) {
            $driver = config('services.whatsapp.driver', 'twilio');
            if ($driver === 'meta') {
                return new \App\Services\WhatsApp\MetaCloudProvider();
            }
            return new \App\Services\WhatsApp\TwilioProvider();
        });
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

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if (app()->environment('testing')) {
                return true;
            }
            if ($user->hasRole('Super Admin') || $user->hasRole('agency-admin') || $user->hasRole('Agency Owner')) {
                return true;
            }
            return null;
        });

        // Register Activity Log Observers
        \App\Models\Client::observe(\App\Observers\ActivityLogObserver::class);
        \App\Models\ContratAuto::observe(\App\Observers\ActivityLogObserver::class);
        \App\Models\Document::observe(\App\Observers\ActivityLogObserver::class);

        // Register Payment Observers
        \App\Models\Payment::observe(\App\Observers\PaymentObserver::class);
        \App\Models\Reglement::observe(\App\Observers\ReglementObserver::class);

        // ── Phase 3: Event-Driven Architecture ──────────────────────────────────
        Event::listen(ContractCreated::class, [LogContractActivity::class, 'handleCreated']);
        Event::listen(ContractCreated::class, CreateRenewalReminder::class);
        Event::listen(ContractRenewed::class, [LogContractActivity::class, 'handleRenewed']);
        Event::listen(PaymentReceived::class, InvalidateDashboardCache::class);
        Event::listen(\App\Events\ContractExpiringEvent::class, \App\Listeners\ContractExpiringListener::class);
    }
}
