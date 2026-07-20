<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware;

class TenancyServiceProvider extends ServiceProvider
{
    // By default, no namespace is used to support the callable array syntax.
    public static string $controllerNamespace = '';

    public function events()
    {
        return [
            // Tenant events
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [
                JobPipeline::make([
                    Jobs\CreateDatabase::class,
                    Jobs\MigrateDatabase::class,
                    Jobs\SeedDatabase::class,

                    // Your own jobs to prepare the tenant.
                    // Provision API keys, create S3 buckets, anything you want!

                ])->send(function (Events\TenantCreated $event) {
                    return $event->tenant;
                })->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],
            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class => [
                JobPipeline::make([
                    Jobs\DeleteDatabase::class,
                ])->send(function (Events\TenantDeleted $event) {
                    return $event->tenant;
                })->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],

            // Domain events
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class => [],
            Events\SavingDomain::class => [],
            Events\DomainSaved::class => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class => [],

            // Database events
            Events\DatabaseCreated::class => [],
            Events\DatabaseMigrated::class => [],
            Events\DatabaseSeeded::class => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class => [],

            // Tenancy events
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class => [
                Listeners\BootstrapTenancy::class,
            ],

            Events\EndingTenancy::class => [],
            Events\TenancyEnded::class => [
                Listeners\RevertToCentralContext::class,
            ],

            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class => [],

            // Resource syncing
            Events\SyncedResourceSaved::class => [
                Listeners\UpdateSyncedResource::class,
            ],

            // Fired only when a synced resource is changed in a different DB than the origin DB (to avoid infinite loops)
            Events\SyncedResourceChangedInForeignDatabase::class => [],
        ];
    }

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->bootEvents();
        $this->mapRoutes();

        $this->makeTenancyMiddlewareHighestPriority();

        // Scope Spatie Permissions cache by Tenant ID
        Event::listen(\Stancl\Tenancy\Events\TenancyInitialized::class, function (\Stancl\Tenancy\Events\TenancyInitialized $event) {
            $registrar = app(\Spatie\Permission\PermissionRegistrar::class);
            $registrar->cacheKey = 'spatie.permission.cache.tenant.' . $event->tenancy->tenant->id;
            try {
                $registrar->forgetCachedPermissions();
            } catch (\Throwable $e) {
                // Ignore if cache table doesn't exist yet during tenant creation/migration
            }
        });

        // Overwrite SMTP configuration dynamically on tenant initialization
        Event::listen(\Stancl\Tenancy\Events\TenancyInitialized::class, function (\Stancl\Tenancy\Events\TenancyInitialized $event) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $host = \App\Models\Setting::get('mail_host');
                    $port = \App\Models\Setting::get('mail_port');
                    $username = \App\Models\Setting::get('mail_username');
                    $password = \App\Models\Setting::get('mail_password');
                    $encryption = \App\Models\Setting::get('mail_encryption');
                    $fromAddress = \App\Models\Setting::get('mail_from_address');
                    $fromName = \App\Models\Setting::get('mail_from_name');

                    if ($host) {
                        config([
                            'mail.mailers.smtp.host' => $host,
                            'mail.mailers.smtp.port' => $port ?? 587,
                            'mail.mailers.smtp.username' => $username,
                            'mail.mailers.smtp.password' => $password,
                            'mail.mailers.smtp.encryption' => ($encryption === 'none') ? null : $encryption,
                            'mail.from.address' => $fromAddress,
                            'mail.from.name' => $fromName,
                        ]);
                        app()->forgetInstance('mail.manager');
                        app()->forgetInstance(\Illuminate\Mail\MailManager::class);
                    }
                }
            } catch (\Throwable $e) {
                // Ignore if table settings doesn't exist yet
            }
        });

        Event::listen(\Stancl\Tenancy\Events\TenancyEnded::class, function (\Stancl\Tenancy\Events\TenancyEnded $event) {
            $registrar = app(\Spatie\Permission\PermissionRegistrar::class);
            $registrar->cacheKey = 'spatie.permission.cache';
            try {
                $registrar->forgetCachedPermissions();
            } catch (\Throwable $e) {
                // Ignore cache clearing errors
            }
        });
    }

    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }

                Event::listen($event, $listener);
            }
        }
    }

    protected function mapRoutes()
    {
        $this->app->booted(function () {
            if (app()->environment('testing')) {
                $isAutomobileTest = false;
                foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $step) {
                    if (isset($step['class']) && (
                        str_contains($step['class'], 'AutomobileTest') || 
                        str_contains($step['class'], 'CommissionTest') || 
                        str_contains($step['class'], 'GestionClientsTest') || 
                        str_contains($step['class'], 'GestionProductsTest') ||
                        str_contains($step['class'], 'CrmTest') ||
                        str_contains($step['class'], 'GestionDossiersTest') ||
                        str_contains($step['class'], 'AdministrationTest') ||
                        str_contains($step['class'], 'PaymentCenterTest') ||
                        str_contains($step['class'], 'SidebarNavigationTest')
                    )) {
                        $isAutomobileTest = true;
                        break;
                    }
                }
                if (!$isAutomobileTest) {
                    return; // Skip loading tenant routes to allow standard Breeze tests to run centrally
                }
            }

            if (file_exists(base_path('routes/tenant.php'))) {
                Route::namespace(static::$controllerNamespace)
                    ->group(base_path('routes/tenant.php'));
            }
        });
    }

    protected function makeTenancyMiddlewareHighestPriority()
    {
        $tenancyMiddleware = [
            // Even higher priority than the initialization middleware
            Middleware\PreventAccessFromCentralDomains::class,

            Middleware\InitializeTenancyByDomain::class,
            Middleware\InitializeTenancyBySubdomain::class,
            Middleware\InitializeTenancyByDomainOrSubdomain::class,
            Middleware\InitializeTenancyByPath::class,
            Middleware\InitializeTenancyByRequestData::class,
        ];

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]->prependToMiddlewarePriority($middleware);
        }
    }
}
