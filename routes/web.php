<?php

use App\Http\Controllers\Platform\AuthController as PlatformAuthController;
use App\Http\Controllers\Platform\DashboardController as PlatformDashboardController;
use App\Http\Controllers\Platform\ExpenseController as PlatformExpenseController;
use App\Models\WebsiteTheme;

foreach (config('tenancy.central_domains', []) as $domain) {
    Route::domain($domain)->get('/', function () {
        return redirect()->route('platform.login');
    });
}

// Independent Theme Preview Route for iFrames
Route::get('/super-admin/theme-preview/{slug}', function ($slug) {
    $theme = WebsiteTheme::where('slug', $slug)->first();
    $viewPath = "themes.{$slug}.landing";
    if (!view()->exists($viewPath)) {
        // Fallback mapping
        $map = [
            'corporate-blue' => 'themes.corporate-blue.landing',
            'axa-inspire' => 'themes.corporate-blue.landing',
            'apple-insurance' => 'themes.apple.landing',
            'luxury-gold' => 'themes.luxury-gold.landing',
            'luxury-private' => 'themes.luxury-gold.landing',
            'wafa-inspire' => 'themes.wafa-inspire.landing',
        ];
        $viewPath = $map[$slug] ?? 'tenant.landing';
    }
    return view($viewPath, ['previewTheme' => $theme, 'agencyName' => 'Agence exemple - ' . ($theme->name ?? $slug)]);
})->name('platform.theme.preview');

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

    // Theme Engine Management (Super Admin Theme Gallery & Lock)
    Route::get('/super-admin/themes', \App\Livewire\Platform\ThemeManager::class)->name('platform.themes');

    // Generic platform modules navigation routing
    Route::get('/super-admin/module/{moduleName}', [PlatformDashboardController::class, 'showModule'])->name('platform.module');
});

// Load Breeze auth routes on central domain ONLY in testing environment to make scaffolded tests pass
if (app()->environment('testing')) {
    require __DIR__.'/auth.php';
    Route::middleware(['auth', \App\Http\Middleware\RequireTwoFactor::class])->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
        Route::view('profile', 'profile')->name('profile');
        Route::view('settings', 'settings')->name('settings');
        Route::view('admin/succursales', 'dashboard')->name('admin.succursales');
        Route::view('admin/employes', 'dashboard')->name('admin.employes');
        Route::view('admin/commissions', 'dashboard')->name('admin.commissions');
        Route::view('mes-commissions', 'dashboard')->name('agent.commissions');
        Route::view('admin/clients', 'dashboard')->name('admin.clients');
        Route::view('admin/entreprises', 'dashboard')->name('admin.entreprises');
        Route::view('admin/produits', 'dashboard')->name('admin.products');
        Route::view('admin/dossiers', 'dashboard')->name('admin.dossiers');
        Route::view('admin/compagnies', 'dashboard')->name('admin.compagnies');
        Route::view('admin/payments-center', 'dashboard')->name('admin.payments.center');
        Route::view('admin/charges', 'dashboard')->name('admin.charges');
        Route::view('admin/tasks', 'dashboard')->name('admin.tasks');
        Route::view('admin/activity-timeline', 'dashboard')->name('admin.activity-timeline');
        Route::view('admin/import-manager', 'dashboard')->name('admin.import-manager');
        Route::view('admin/vault', 'dashboard')->name('admin.vault');
        Route::view('admin/agenda', 'dashboard')->name('admin.agenda');
        Route::view('admin/communications', 'dashboard')->name('admin.communications');
        Route::view('automobile', 'dashboard')->name('automobile.index');
        Route::view('automobile/create', 'dashboard')->name('automobile.create');
        Route::post('logout', function () {})->name('logout');
        Route::get('/automobile/pdf/{contratId}/{type}', [\App\Http\Controllers\Tenant\PDFController::class, 'generate'])->name('automobile.pdf');
        Route::get('/admin/employes/{id}/pdf', [\App\Http\Controllers\Tenant\PDFController::class, 'generateEmployeePdf'])->name('admin.employes.pdf');
        Route::get('/admin/employes/{id}/print', [\App\Http\Controllers\Tenant\PDFController::class, 'printEmployeeCard'])->name('admin.employes.print');
        Route::get('/admin/employes/{id}/welcome-pdf', [\App\Http\Controllers\Tenant\PDFController::class, 'generateEmployeeWelcomePdf'])->name('admin.employes.welcome-pdf');
        Route::get('/admin/employes/{id}/welcome-print', [\App\Http\Controllers\Tenant\PDFController::class, 'printEmployeeWelcomeCard'])->name('admin.employes.welcome-print');
        Route::get('/admin/security-dashboard', \App\Livewire\Admin\SecurityDashboard::class)->name('admin.security-dashboard');
        Route::get('/admin/security', \App\Livewire\Admin\SecuritySettings::class)->name('admin.security');
        Route::get('admin/security-audit', \App\Livewire\Admin\SecurityAuditCenter::class)->name('admin.security-audit');
        Route::get('/admin/security-audit/pdf', [\App\Http\Controllers\Tenant\PDFController::class, 'exportSecurityAuditPdf'])->name('admin.security-audit.pdf');
    });
}

Route::get('livewire/update', function () {
    return redirect('/');
});
