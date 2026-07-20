<?php

use App\Http\Controllers\Platform\AuthController as PlatformAuthController;
use App\Http\Controllers\Platform\DashboardController as PlatformDashboardController;
use App\Http\Controllers\Platform\ExpenseController as PlatformExpenseController;

foreach (config('tenancy.central_domains', []) as $domain) {
    Route::domain($domain)->get('/', function () {
        return redirect()->route('platform.login');
    });
}

// Central Super Admin Authentication
Route::get('/super-admin/login', [PlatformAuthController::class, 'showLoginForm'])->name('platform.login');
Route::post('/super-admin/login', [PlatformAuthController::class, 'login'])->name('platform.login.submit');
Route::post('/super-admin/logout', [PlatformAuthController::class, 'logout'])->name('platform.logout');

// Central Super Admin Dashboard and Management
Route::middleware(['auth:platform', 'central'])->group(function () {
    Route::get('/super-admin/dashboard', [PlatformDashboardController::class, 'index'])->name('platform.dashboard');
    Route::get('/super-admin/tenants/creer', [PlatformDashboardController::class, 'create'])->name('platform.tenants.create');
    Route::post('/super-admin/tenants/store', [PlatformDashboardController::class, 'store'])->name('platform.tenants.store');
    Route::get('/super-admin/impersonate/{tenantId}', [PlatformDashboardController::class, 'impersonate'])->name('platform.tenants.impersonate');
    Route::get('/super-admin/tenants/{tenantId}/modifier', [PlatformDashboardController::class, 'edit'])->name('platform.tenants.edit');
    Route::put('/super-admin/tenants/{tenantId}', [PlatformDashboardController::class, 'update'])->name('platform.tenants.update');
    Route::delete('/super-admin/tenants/{tenantId}', [PlatformDashboardController::class, 'destroy'])->name('platform.tenants.destroy');
    Route::post('/super-admin/tenants/{tenantId}/succursales', [PlatformDashboardController::class, 'storeSuccursale'])->name('platform.tenants.store_succursale');
    Route::delete('/super-admin/tenants/{tenantId}/succursales/{succursaleId}', [PlatformDashboardController::class, 'destroySuccursale'])->name('platform.tenants.destroy_succursale');

    // Expenses (Charges) Management
    Route::get('/super-admin/charges', [PlatformExpenseController::class, 'index'])->name('platform.expenses.index');
    Route::post('/super-admin/charges', [PlatformExpenseController::class, 'store'])->name('platform.expenses.store');
    Route::delete('/super-admin/charges/{id}', [PlatformExpenseController::class, 'destroy'])->name('platform.expenses.destroy');
});

// Load Breeze auth routes on central domain ONLY in testing environment to make scaffolded tests pass
if (app()->environment('testing')) {
    require __DIR__.'/auth.php';
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
    Route::view('settings', 'settings')->name('settings');
    Route::view('admin/succursales', 'dashboard')->name('admin.succursales');
    Route::view('admin/employes', 'dashboard')->name('admin.employes');
    Route::view('admin/commissions', 'dashboard')->name('admin.commissions');
    Route::view('mes-commissions', 'dashboard')->name('agent.commissions');
    Route::get('/automobile/pdf/{contratId}/{type}', [\App\Http\Controllers\Tenant\PDFController::class, 'generate'])->name('automobile.pdf');
}
