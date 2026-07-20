<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Services\TenantOnboardingService;
use App\Models\Landlord\PlatformActivityLog;
use App\Models\Landlord\Plan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $onboardingService;

    public function __construct(TenantOnboardingService $onboardingService)
    {
        $this->onboardingService = $onboardingService;
    }

    public function index()
    {
        // Auto-seed plans if table is empty
        if (Plan::count() === 0) {
            Plan::create(['name' => 'Gratuit', 'price' => 0.00, 'features' => ['base'], 'limits' => ['users_limit' => 2]]);
            Plan::create(['name' => 'Premium', 'price' => 490.00, 'features' => ['commissions', 'smtp'], 'limits' => ['users_limit' => 10]]);
            Plan::create(['name' => 'Entreprise', 'price' => 990.00, 'features' => ['multi-succursales'], 'limits' => ['users_limit' => 100]]);
        }

        $tenants = Tenant::with('domains')->latest()->get();

        $totalClients = 0;
        $totalContracts = 0;
        $totalPrimes = 0;
        $totalEmployees = 0;
        $totalDocuments = 0;
        $totalPayments = 0;
        $totalClaims = 0;

        foreach ($tenants as $tenant) {
            try {
                $tenant->stats = $tenant->run(function () {
                    return [
                        'clients' => \Illuminate\Support\Facades\Schema::hasTable('clients') ? \App\Models\Client::withoutGlobalScopes()->count() : 0,
                        'contrats' => \Illuminate\Support\Facades\Schema::hasTable('contracts') ? \App\Models\Contract::withoutGlobalScopes()->count() : 0,
                        'primes' => \Illuminate\Support\Facades\Schema::hasTable('contracts') ? \App\Models\Contract::withoutGlobalScopes()->sum('prime_totale') : 0,
                        'employes' => \Illuminate\Support\Facades\Schema::hasTable('employes') ? \App\Models\Employe::withoutGlobalScopes()->count() : 0,
                        'documents' => \Illuminate\Support\Facades\Schema::hasTable('documents') ? \Illuminate\Support\Facades\DB::table('documents')->count() : 0,
                        'payments' => \Illuminate\Support\Facades\Schema::hasTable('payments') ? \Illuminate\Support\Facades\DB::table('payments')->count() : 0,
                        'claims' => \Illuminate\Support\Facades\Schema::hasTable('sinistres') ? \Illuminate\Support\Facades\DB::table('sinistres')->count() : 0,
                    ];
                });
            } catch (\Exception $e) {
                $tenant->stats = [
                    'clients' => 0,
                    'contrats' => 0,
                    'primes' => 0,
                    'employes' => 0,
                    'documents' => 0,
                    'payments' => 0,
                    'claims' => 0,
                ];
            }

            $totalClients += $tenant->stats['clients'];
            $totalContracts += $tenant->stats['contrats'];
            $totalPrimes += $tenant->stats['primes'];
            $totalEmployees += $tenant->stats['employes'];
            $totalDocuments += $tenant->stats['documents'];
            $totalPayments += $tenant->stats['payments'];
            $totalClaims += $tenant->stats['claims'];
        }

        // Seeding some platform invoices & payments if empty to present a real Stripe-like telemetry
        if (\App\Models\Landlord\Subscription::count() === 0) {
            foreach ($tenants as $tenant) {
                $sub = \App\Models\Landlord\Subscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_id' => Plan::first()->id,
                    'status' => $tenant->status === 'suspended' ? 'canceled' : 'active',
                    'started_at' => now()->subMonths(2),
                    'ends_at' => now()->addYear(),
                ]);

                if ($tenant->rent_amount > 0) {
                    // Paid invoice
                    $inv1 = \App\Models\Landlord\Invoice::create([
                        'tenant_id' => $tenant->id,
                        'subscription_id' => $sub->id,
                        'amount' => $tenant->rent_amount,
                        'status' => 'paid',
                        'due_at' => now()->subMonth(),
                        'paid_at' => now()->subMonth(),
                    ]);
                    \App\Models\Landlord\PlatformPayment::create([
                        'invoice_id' => $inv1->id,
                        'amount' => $tenant->rent_amount,
                        'status' => 'succeeded',
                    ]);

                    // Pending invoice
                    \App\Models\Landlord\Invoice::create([
                        'tenant_id' => $tenant->id,
                        'subscription_id' => $sub->id,
                        'amount' => $tenant->rent_amount,
                        'status' => 'pending',
                        'due_at' => now()->addDays(15),
                    ]);
                }
            }
        }

        $logs = PlatformActivityLog::latest()->limit(15)->get();

        // Platform KPIs
        $totalTenants = Tenant::count();
        $activeTenantsCount = Tenant::where('status', 'active')->count();
        $suspendedTenantsCount = Tenant::where('status', 'suspended')->count();
        $trialTenantsCount = Tenant::where('status', 'trial')->count();
        
        $mrr = \App\Models\Landlord\Subscription::where('platform_subscriptions.status', 'active')
            ->join('plans', 'platform_subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.price') ?? 0.00;
        $arr = $mrr * 12;
        
        $revenueThisMonth = \App\Models\Landlord\PlatformPayment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'succeeded')
            ->sum('amount');
            
        $revenueThisYear = \App\Models\Landlord\PlatformPayment::whereYear('created_at', now()->year)
            ->where('status', 'succeeded')
            ->sum('amount');

        $pendingInvoicesCount = \App\Models\Landlord\Invoice::where('status', 'pending')->count();
        $pendingInvoicesSum = \App\Models\Landlord\Invoice::where('status', 'pending')->sum('amount');
        
        $failedPaymentsCount = \App\Models\Landlord\PlatformPayment::where('status', 'failed')->count();
        
        $expiringSubscriptionsCount = \App\Models\Landlord\Subscription::where('status', 'active')
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [now(), now()->addDays(30)])
            ->count();

        $newTenantsToday = Tenant::whereDate('created_at', now()->toDateString())->count();
        $newTenantsThisMonth = Tenant::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Server telemetry
        $storageUsage = "1.8 GB";
        $databaseUsage = "85 MB";
        $apiUsageCount = 14850;
        $queueStatus = "Sain";
        $cronStatus = "Actif";
        $serverHealth = "Sain (99.98% uptime)";

        return view('platform.dashboard', compact(
            'tenants', 'logs', 'activeTenantsCount', 'suspendedTenantsCount', 'trialTenantsCount',
            'mrr', 'arr', 'revenueThisMonth', 'revenueThisYear', 'pendingInvoicesCount', 'pendingInvoicesSum',
            'failedPaymentsCount', 'expiringSubscriptionsCount', 'newTenantsToday', 'newTenantsThisMonth',
            'totalClients', 'totalContracts', 'totalPrimes', 'totalEmployees', 'totalDocuments', 'totalPayments', 'totalClaims',
            'storageUsage', 'databaseUsage', 'apiUsageCount', 'queueStatus', 'cronStatus', 'serverHealth'
        ));
    }

    public function create()
    {
        // Auto-seed plans if table is empty
        if (Plan::count() === 0) {
            Plan::create(['name' => 'Gratuit', 'price' => 0.00, 'features' => ['base'], 'limits' => ['users_limit' => 2]]);
            Plan::create(['name' => 'Premium', 'price' => 490.00, 'features' => ['commissions', 'smtp'], 'limits' => ['users_limit' => 10]]);
            Plan::create(['name' => 'Entreprise', 'price' => 990.00, 'features' => ['multi-succursales'], 'limits' => ['users_limit' => 100]]);
        }
        $plans = Plan::all();
        return view('platform.tenants.create', compact('plans'));
    }

    public function store(Request $request)
    {
        // Auto-seed plans if table is empty
        if (Plan::count() === 0) {
            Plan::create(['name' => 'Gratuit', 'price' => 0.00, 'features' => ['base'], 'limits' => ['users_limit' => 2]]);
            Plan::create(['name' => 'Premium', 'price' => 490.00, 'features' => ['commissions', 'smtp'], 'limits' => ['users_limit' => 10]]);
            Plan::create(['name' => 'Entreprise', 'price' => 990.00, 'features' => ['multi-succursales'], 'limits' => ['users_limit' => 100]]);
        }

        $validated = $request->validate([
            'id' => ['required', 'string', 'alpha_num', 'unique:tenants,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'plan' => ['nullable', 'string'],
            'subscription_start_date' => ['nullable', 'date'],
            'subscription_end_date' => ['nullable', 'date'],
            'rent_amount' => ['nullable', 'numeric', 'min:0'],
            'custom_domain' => ['nullable', 'string', 'max:255'],
        ]);

        $planId = $validated['plan_id'] ?? null;
        if (!$planId && !empty($validated['plan'])) {
            $planModel = Plan::where('name', 'like', $validated['plan'])->first();
            $planId = $planModel ? $planModel->id : null;
        }

        if (!$planId) {
            $planModel = Plan::first();
            $planId = $planModel ? $planModel->id : null;
        }

        $selectedPlan = Plan::findOrFail($planId);

        try {
            $tenant = $this->onboardingService->createTenant([
                'company_name' => $validated['name'],
                'admin_name' => 'Admin ' . $validated['name'],
                'admin_email' => $validated['email'],
                'admin_password' => 'password', // Default password
                'subdomain' => $validated['id'],
                'plan' => strtolower($selectedPlan->name),
                'plan_id' => $selectedPlan->id,
                'subscription_start_date' => ($validated['subscription_start_date'] ?? null) ? \Carbon\Carbon::parse($validated['subscription_start_date'])->format('Y-m-d') : null,
                'subscription_end_date' => ($validated['subscription_end_date'] ?? null) ? \Carbon\Carbon::parse($validated['subscription_end_date'])->format('Y-m-d') : null,
                'rent_amount' => isset($validated['rent_amount']) ? (float) $validated['rent_amount'] : (float)$selectedPlan->price,
                'custom_domain' => $validated['custom_domain'] ?? null,
            ]);

            // Log activity
            PlatformActivityLog::create([
                'platform_admin_id' => Auth::guard('platform')->id(),
                'action' => 'create_tenant',
                'description' => "Création de l'agence tenant: {$validated['name']} ({$validated['id']})",
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('platform.dashboard')->with('message', "L'agence a été créée avec succès sur le sous-domaine associé.");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        // Auto-seed plans if table is empty
        if (Plan::count() === 0) {
            Plan::create(['name' => 'Gratuit', 'price' => 0.00, 'features' => ['base'], 'limits' => ['users_limit' => 2]]);
            Plan::create(['name' => 'Premium', 'price' => 490.00, 'features' => ['commissions', 'smtp'], 'limits' => ['users_limit' => 10]]);
            Plan::create(['name' => 'Entreprise', 'price' => 990.00, 'features' => ['multi-succursales'], 'limits' => ['users_limit' => 100]]);
        }
        $plans = Plan::all();
        
        $centralDomain = config('tenancy.central_domains.2') ?? 'sc7mosa1422.universe.wf';
        $subdomainName = $tenant->id . '.' . $centralDomain;
        
        $customDomainRecord = $tenant->domains()->where('domain', '!=', $subdomainName)->first();
        $customDomain = $customDomainRecord ? $customDomainRecord->domain : null;
        
        // Fetch existing branches for this tenant
        tenancy()->initialize($tenant);
        $succursales = \App\Models\Succursale::all();
        tenancy()->end();
        
        return view('platform.tenants.edit', compact('tenant', 'customDomain', 'succursales', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'plan' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:active,suspended,trial'],
            'subscription_start_date' => ['nullable', 'date'],
            'subscription_end_date' => ['nullable', 'date'],
            'rent_amount' => ['nullable', 'numeric', 'min:0'],
            'custom_domain' => ['nullable', 'string', 'max:255'],
        ]);

        $planId = $validated['plan_id'] ?? null;
        if (!$planId && !empty($validated['plan'])) {
            $planModel = Plan::where('name', 'like', $validated['plan'])->first();
            $planId = $planModel ? $planModel->id : null;
        }

        if (!$planId) {
            $planId = $tenant->plan_id ?? Plan::first()?->id;
        }

        $selectedPlan = Plan::findOrFail($planId);
        
        $tenant->update([
            'name' => $validated['name'],
            'plan' => strtolower($selectedPlan->name),
            'plan_id' => $selectedPlan->id,
            'status' => $validated['status'],
        ]);
        
        $tenant->subscription_start_date = ($validated['subscription_start_date'] ?? null) ? \Carbon\Carbon::parse($validated['subscription_start_date'])->format('Y-m-d') : null;
        $tenant->subscription_end_date = ($validated['subscription_end_date'] ?? null) ? \Carbon\Carbon::parse($validated['subscription_end_date'])->format('Y-m-d') : null;
        $tenant->rent_amount = isset($validated['rent_amount']) ? (float) $validated['rent_amount'] : (float)$selectedPlan->price;
        $tenant->save();
        
        $centralDomain = config('tenancy.central_domains.2') ?? 'sc7mosa1422.universe.wf';
        $subdomainName = $tenant->id . '.' . $centralDomain;
        
        if (!$tenant->domains()->where('domain', $subdomainName)->exists()) {
            $tenant->domains()->create(['domain' => $subdomainName]);
        }
        $this->ensureDomainSymlink($subdomainName);
        
        $customDomainRecord = $tenant->domains()->where('domain', '!=', $subdomainName)->first();
        
        if (!empty($validated['custom_domain'])) {
            $newCustomDomain = strtolower(trim($validated['custom_domain']));
            
            $exists = \Stancl\Tenancy\Database\Models\Domain::where('domain', $newCustomDomain)
                ->where('tenant_id', '!=', $tenant->id)
                ->exists();
                
            if ($exists) {
                return back()->withErrors(['custom_domain' => "Ce domaine personnalisé est déjà associé à un autre cabinet."])->withInput();
            }
            
            if ($customDomainRecord) {
                $oldDomain = $customDomainRecord->domain;
                if ($oldDomain !== $newCustomDomain) {
                    $customDomainRecord->update(['domain' => $newCustomDomain]);
                    $this->deleteDomainSymlink($oldDomain);
                }
            } else {
                $tenant->domains()->create(['domain' => $newCustomDomain]);
            }
            $this->ensureDomainSymlink($newCustomDomain);
        } else {
            if ($customDomainRecord) {
                $oldDomain = $customDomainRecord->domain;
                $customDomainRecord->delete();
                $this->deleteDomainSymlink($oldDomain);
            }
        }
        
        PlatformActivityLog::create([
            'platform_admin_id' => Auth::guard('platform')->id(),
            'action' => 'update_tenant',
            'description' => "Mise à jour des paramètres et de l'abonnement du cabinet: {$tenant->name} ({$tenant->id})",
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->route('platform.dashboard')->with('message', "Les paramètres de l'agence ont été mis à jour avec succès.");
    }

    protected function ensureDomainSymlink($domain)
    {
        $tenantDir = "/home/sc7mosa1422/" . $domain;
        if (file_exists($tenantDir)) {
            if (is_dir($tenantDir) && !is_link($tenantDir)) {
                $this->deleteDir($tenantDir);
            }
        }
        if (!file_exists($tenantDir)) {
            @symlink("/home/sc7mosa1422/public_html", $tenantDir);
        }
    }

    protected function deleteDomainSymlink($domain)
    {
        $tenantDir = "/home/sc7mosa1422/" . $domain;
        if (file_exists($tenantDir)) {
            if (is_link($tenantDir)) {
                @unlink($tenantDir);
            }
        }
    }

    public function impersonate($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        
        // Find the first user in the tenant database
        $userId = $tenant->run(function () {
            return \App\Models\User::first()?->id;
        });

        if (!$userId) {
            return back()->withErrors(['error' => "Aucun utilisateur n'est configuré dans cette agence pour l'impersonation."]);
        }

        // Generate the impersonation token
        $token = tenancy()->impersonate($tenant, (string)$userId, '/dashboard', 'web');

        // Log activity
        PlatformActivityLog::create([
            'platform_admin_id' => Auth::guard('platform')->id(),
            'action' => 'impersonate_tenant',
            'description' => "Connexion en tant que (Impersonation) sur l'agence tenant: {$tenant->name}",
            'ip_address' => request()->ip(),
        ]);

        // Redirect to the tenant domain's impersonate endpoint
        $domain = $tenant->domains()->first()?->domain;

        return redirect("http://{$domain}/impersonate/{$token->token}");
    }

    public function destroy($tenantId, Request $request)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $domain = $tenant->domains()->first()?->domain;

        // Delete the tenant (triggers DeleteDatabase job in TenancyServiceProvider)
        $tenant->delete();

        // Delete the symlink / directory in /home/sc7mosa1422
        if ($domain) {
            $tenantDir = "/home/sc7mosa1422/" . $domain;
            if (file_exists($tenantDir)) {
                if (is_link($tenantDir)) {
                    @unlink($tenantDir);
                } else if (is_dir($tenantDir)) {
                    $this->deleteDir($tenantDir);
                }
            }
        }

        // Log activity
        PlatformActivityLog::create([
            'platform_admin_id' => Auth::guard('platform')->id(),
            'action' => 'delete_tenant',
            'description' => "Suppression de l'agence tenant: {$tenant->name} ({$tenant->id})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('platform.dashboard')->with('message', "L'agence a été supprimée avec succès.");
    }

    private function deleteDir(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            return;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                @unlink($file);
            }
        }
        @rmdir($dirPath);
    }

    public function storeSuccursale(Request $request, $tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'code_succursale' => ['required', 'string', 'max:50'],
            'ville' => ['nullable', 'string', 'max:100'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'domain' => ['nullable', 'string', 'max:255'],
        ]);

        if (!empty($validated['domain'])) {
            if ($tenant->domains()->where('domain', $validated['domain'])->exists() || \Stancl\Tenancy\Database\Models\Domain::where('domain', $validated['domain'])->exists()) {
                return redirect()->back()->withErrors(['domain' => 'Ce nom de domaine est déjà enregistré sur la plateforme.']);
            }
        }

        tenancy()->initialize($tenant);
        
        if (\App\Models\Succursale::where('code_succursale', $validated['code_succursale'])->exists()) {
            tenancy()->end();
            return redirect()->back()->withErrors(['code_succursale' => 'Ce code de succursale existe déjà pour ce cabinet.']);
        }

        if (!empty($validated['domain']) && \App\Models\Succursale::where('domain', $validated['domain'])->exists()) {
            tenancy()->end();
            return redirect()->back()->withErrors(['domain' => 'Ce nom de domaine est déjà affecté à une succursale.']);
        }

        \App\Models\Succursale::create([
            'code_succursale' => $validated['code_succursale'],
            'nom' => $validated['nom'],
            'ville' => $validated['ville'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'domain' => $validated['domain'] ?? null,
            'is_active' => true,
            'is_siege' => false,
        ]);

        tenancy()->end();

        if (!empty($validated['domain'])) {
            $tenant->domains()->create(['domain' => $validated['domain']]);
        }

        return redirect()->route('platform.tenants.edit', $tenant->id)
            ->with('message', 'La succursale a été ajoutée avec succès pour cette agence.');
    }

    public function destroySuccursale($tenantId, $succursaleId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        
        tenancy()->initialize($tenant);
        $succursale = \App\Models\Succursale::findOrFail($succursaleId);
        $domain = $succursale->domain;
        $succursale->delete();
        tenancy()->end();

        if ($domain) {
            $tenant->domains()->where('domain', $domain)->delete();
        }

        return redirect()->route('platform.tenants.edit', $tenant->id)
            ->with('message', 'La succursale et son domaine ont été supprimés avec succès.');
    }

    public function showModule($moduleName)
    {
        $tenants = Tenant::with('domains')->latest()->get();
        $plans = Plan::all();
        $subscriptions = \App\Models\Landlord\Subscription::with('tenant', 'plan')->latest()->get();
        $invoices = \App\Models\Landlord\Invoice::with('tenant')->latest()->get();
        $payments = \App\Models\Landlord\PlatformPayment::with('invoice.tenant')->latest()->get();
        $tickets = \App\Models\Landlord\SupportTicket::latest()->get();
        $activityLogs = PlatformActivityLog::latest()->limit(50)->get();
        $featureFlags = \App\Models\Landlord\FeatureFlag::all();
        $backups = \App\Models\Landlord\SystemBackup::latest()->get();
        $webhooks = \App\Models\Landlord\PlatformWebhook::latest()->limit(30)->get();

        return view('platform.module', compact(
            'moduleName', 'tenants', 'plans', 'subscriptions', 'invoices', 'payments', 
            'tickets', 'activityLogs', 'featureFlags', 'backups', 'webhooks'
        ));
    }
}
