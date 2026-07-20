<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Livewire\Automobile\ListeContrats;
use App\Livewire\Automobile\FormulaireContrat;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

$tenantMiddleware = [
    InitializeTenancyByDomain::class,
    'web',
    \App\Http\Middleware\CheckTenantSubscription::class,
];

if (!app()->environment('testing')) {
    $tenantMiddleware[] = PreventAccessFromCentralDomains::class;
}

Route::middleware($tenantMiddleware)->group(function () {

    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('tenant.landing');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('dashboard', \App\Livewire\Admin\AdminDashboard::class)->name('dashboard');
        Route::view('profile', 'profile')->name('profile');
        Route::get('settings', \App\Livewire\Admin\GestionAgence::class)->name('settings');
        
        // Automobile Register routes
        Route::get('/automobile', ListeContrats::class)->name('automobile.index');
        Route::get('/automobile/creer', FormulaireContrat::class)->name('automobile.create');
        Route::get('/automobile/modifier/{contratId}', FormulaireContrat::class)->name('automobile.edit');

        Route::get('/admin/succursales', \App\Livewire\Admin\GestionSuccursales::class)->name('admin.succursales');
        Route::get('/admin/employes', \App\Livewire\Admin\GestionEmployes::class)->name('admin.employes');
        Route::get('/admin/commissions', \App\Livewire\Admin\GestionCommissions::class)->name('admin.commissions');
        Route::get('/admin/charges', \App\Livewire\Admin\GestionCharges::class)->name('admin.charges');
        Route::get('/admin/clients', \App\Livewire\Admin\GestionClients::class)->name('admin.clients');
        Route::get('/admin/clients/{clientId}', \App\Livewire\Admin\ClientProfile::class)->name('admin.clients.profile');
        Route::get('/admin/tasks', \App\Livewire\Admin\TaskManager::class)->name('admin.tasks');
        Route::get('/admin/entreprises', \App\Livewire\Admin\GestionEntreprises::class)->name('admin.entreprises');
        Route::get('/admin/produits', \App\Livewire\Admin\GestionProducts::class)->name('admin.products');

        // Agent routes
        Route::get('/mes-commissions', \App\Livewire\Agent\MesCommissions::class)->name('agent.commissions');

        // PDF Generation route
        Route::get('/automobile/pdf/{contratId}/{type}', [\App\Http\Controllers\Tenant\PDFController::class, 'generate'])->name('automobile.pdf');

        // Secure Document Preview route
        Route::get('/documents/preview/{id}', [\App\Http\Controllers\Tenant\DocumentPreviewController::class, 'show'])->name('documents.preview');

        // Logout route
        Route::post('/logout', function () {
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/');
        })->name('logout');
    });

    // Impersonation route
    Route::get('/impersonate/{token}', function ($token) {
        session(['impersonated_by_landlord' => true]);
        return \Stancl\Tenancy\Features\UserImpersonation::makeResponse($token);
    })->name('tenant.impersonate');

    // API v1 routes (Tenant-isolated, token authenticated, rate-limited)
    Route::prefix('api/v1')->middleware(['tenant.api', 'throttle:60,1'])->group(function () {
        Route::get('/clients', [\App\Http\Controllers\Api\v1\ApiClientController::class, 'index']);
        Route::post('/clients', [\App\Http\Controllers\Api\v1\ApiClientController::class, 'store']);

        Route::get('/contracts', [\App\Http\Controllers\Api\v1\ApiContractController::class, 'index']);
        Route::post('/contracts', [\App\Http\Controllers\Api\v1\ApiContractController::class, 'store']);

        Route::post('/documents', [\App\Http\Controllers\Api\v1\ApiDocumentController::class, 'store']);
    });

    // Tenant Authentication Routes (Volt/Breeze)
    require base_path('routes/auth.php');
});
