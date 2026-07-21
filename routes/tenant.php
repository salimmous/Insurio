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
        Route::get('/automobile', ListeContrats::class)->name('automobile.index')->middleware('can:contracts.view');
        Route::get('/automobile/creer', FormulaireContrat::class)->name('automobile.create')->middleware('can:contracts.create');
        Route::get('/automobile/modifier/{contratId}', FormulaireContrat::class)->name('automobile.edit')->middleware('can:contracts.edit');

        Route::get('/admin/succursales', \App\Livewire\Admin\GestionSuccursales::class)->name('admin.succursales')->middleware('can:expenses.view');
        Route::get('/admin/employes', \App\Livewire\Admin\GestionEmployes::class)->name('admin.employes')->middleware('can:expenses.view');
        Route::get('/admin/commissions', \App\Livewire\Admin\GestionCommissions::class)->name('admin.commissions')->middleware('can:commissions.view');
        Route::get('/admin/charges', \App\Livewire\Admin\GestionCharges::class)->name('admin.charges')->middleware('can:expenses.view');
        Route::get('/admin/automation', \App\Livewire\Admin\AutomationControl::class)->name('admin.automation')->middleware('can:expenses.view');
        Route::get('/admin/clients', \App\Livewire\Admin\GestionClients::class)->name('admin.clients')->middleware('can:clients.view');
        Route::get('/admin/clients/{clientId}', \App\Livewire\Admin\ClientProfile::class)->name('admin.clients.profile')->middleware('can:clients.view');
        Route::get('/admin/dossiers', \App\Livewire\Admin\GestionDossiers::class)->name('admin.dossiers')->middleware('can:clients.view');
        Route::get('/admin/dossiers/{id}', \App\Livewire\Admin\DossierWorkspace::class)->name('admin.dossiers.workspace')->middleware('can:clients.view');
        Route::get('/admin/tasks', \App\Livewire\Admin\TaskManager::class)->name('admin.tasks')->middleware('can:clients.view');
        Route::get('/admin/entreprises', \App\Livewire\Admin\GestionEntreprises::class)->name('admin.entreprises')->middleware('can:clients.view');
        Route::get('/admin/produits', \App\Livewire\Admin\GestionProducts::class)->name('admin.products')->middleware('can:contracts.view');
        Route::get('/admin/activity-timeline', \App\Livewire\Admin\ActivityTimeline::class)->name('admin.activity-timeline')->middleware('can:clients.view');
        Route::get('/admin/import-manager', \App\Livewire\Admin\ImportManager::class)->name('admin.import-manager')->middleware('can:clients.create');
        Route::get('/admin/compagnies', \App\Livewire\Admin\GestionInsurers::class)->name('admin.compagnies')->middleware('can:contracts.view');
        Route::get('/admin/payments', \App\Livewire\Admin\PaymentManager::class)->name('admin.payments')->middleware('can:payments.manage');
        Route::get('/admin/payments-center', \App\Livewire\Admin\PaymentCenter::class)->name('admin.payments.center')->middleware('can:clients.view');
        Route::get('/admin/payments-center/{id}', \App\Livewire\Admin\PaymentWorkspace::class)->name('admin.payments.workspace')->middleware('can:clients.view');
        Route::get('/admin/vault', \App\Livewire\Admin\DocumentVault::class)->name('admin.vault')->middleware('can:clients.view');
        Route::get('/admin/agenda', \App\Livewire\Admin\AgendaCenter::class)->name('admin.agenda')->middleware('can:clients.view');
        Route::get('/admin/communications', \App\Livewire\Admin\CommunicationCenter::class)->name('admin.communications')->middleware('can:clients.view');

        // Agent routes
        Route::get('/mes-commissions', \App\Livewire\Agent\MesCommissions::class)->name('agent.commissions')->middleware('can:commissions.view');

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

    Route::get('livewire/update', function () {
        return redirect('/');
    });
});
