<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Services\TenantOnboardingService;
use App\Models\Landlord\PlatformActivityLog;
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
        $tenants = Tenant::with('domains')->latest()->get();

        foreach ($tenants as $tenant) {
            try {
                $tenant->stats = $tenant->run(function () {
                    return [
                        'clients' => \Illuminate\Support\Facades\Schema::hasTable('clients') ? \App\Models\Client::withoutGlobalScopes()->count() : 0,
                        'contrats' => \Illuminate\Support\Facades\Schema::hasTable('contrats_auto') ? \App\Models\ContratAuto::withoutGlobalScopes()->count() : 0,
                        'primes' => \Illuminate\Support\Facades\Schema::hasTable('contrats_auto') ? \App\Models\ContratAuto::withoutGlobalScopes()->sum('prime_totale') : 0,
                        'employes' => \Illuminate\Support\Facades\Schema::hasTable('employes') ? \App\Models\Employe::withoutGlobalScopes()->count() : 0,
                        'commissions' => \Illuminate\Support\Facades\Schema::hasTable('commissions_employes') ? \App\Models\CommissionEmploye::withoutGlobalScopes()->sum('montant_commission') : 0,
                    ];
                });
            } catch (\Exception $e) {
                $tenant->stats = [
                    'clients' => 0,
                    'contrats' => 0,
                    'primes' => 0,
                    'employes' => 0,
                    'commissions' => 0,
                ];
            }
        }

        $logs = PlatformActivityLog::latest()->limit(15)->get();

        return view('platform.dashboard', compact('tenants', 'logs'));
    }

    public function create()
    {
        return view('platform.tenants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'string', 'alpha_num', 'unique:tenants,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'plan' => ['required', 'string', 'in:gratuit,premium,entreprise'],
            'subscription_start_date' => ['nullable', 'date'],
            'subscription_end_date' => ['nullable', 'date'],
            'rent_amount' => ['nullable', 'numeric', 'min:0'],
            'custom_domain' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $tenant = $this->onboardingService->createTenant([
                'company_name' => $validated['name'],
                'admin_name' => 'Admin ' . $validated['name'],
                'admin_email' => $validated['email'],
                'admin_password' => 'password', // Default password
                'subdomain' => $validated['id'],
                'plan' => $validated['plan'],
                'subscription_start_date' => ($validated['subscription_start_date'] ?? null) ? \Carbon\Carbon::parse($validated['subscription_start_date'])->format('Y-m-d') : null,
                'subscription_end_date' => ($validated['subscription_end_date'] ?? null) ? \Carbon\Carbon::parse($validated['subscription_end_date'])->format('Y-m-d') : null,
                'rent_amount' => isset($validated['rent_amount']) ? (float) $validated['rent_amount'] : null,
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
        
        $centralDomain = config('tenancy.central_domains.2') ?? 'sc7mosa1422.universe.wf';
        $subdomainName = $tenant->id . '.' . $centralDomain;
        
        $customDomainRecord = $tenant->domains()->where('domain', '!=', $subdomainName)->first();
        $customDomain = $customDomainRecord ? $customDomainRecord->domain : null;
        
        // Fetch existing branches for this tenant
        tenancy()->initialize($tenant);
        $succursales = \App\Models\Succursale::all();
        tenancy()->end();
        
        return view('platform.tenants.edit', compact('tenant', 'customDomain', 'succursales'));
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'plan' => ['required', 'string', 'in:gratuit,premium,entreprise'],
            'status' => ['required', 'string', 'in:active,suspended,trial'],
            'subscription_start_date' => ['nullable', 'date'],
            'subscription_end_date' => ['nullable', 'date'],
            'rent_amount' => ['nullable', 'numeric', 'min:0'],
            'custom_domain' => ['nullable', 'string', 'max:255'],
        ]);
        
        $tenant->update([
            'name' => $validated['name'],
            'plan' => $validated['plan'],
            'status' => $validated['status'],
        ]);
        
        $tenant->subscription_start_date = ($validated['subscription_start_date'] ?? null) ? \Carbon\Carbon::parse($validated['subscription_start_date'])->format('Y-m-d') : null;
        $tenant->subscription_end_date = ($validated['subscription_end_date'] ?? null) ? \Carbon\Carbon::parse($validated['subscription_end_date'])->format('Y-m-d') : null;
        $tenant->rent_amount = isset($validated['rent_amount']) ? (float) $validated['rent_amount'] : null;
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

    /**
     * Delete a directory recursively.
     *
     * @param string $dirPath
     * @return void
     */
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
            // Check if domain exists f central domains table
            if ($tenant->domains()->where('domain', $validated['domain'])->exists() || \Stancl\Tenancy\Database\Models\Domain::where('domain', $validated['domain'])->exists()) {
                return redirect()->back()->withErrors(['domain' => 'Ce nom de domaine est déjà enregistré sur la plateforme.']);
            }
        }

        // Initialize tenant database context
        tenancy()->initialize($tenant);
        
        // Check if code_succursale is unique inside this tenant
        if (\App\Models\Succursale::where('code_succursale', $validated['code_succursale'])->exists()) {
            tenancy()->end();
            return redirect()->back()->withErrors(['code_succursale' => 'Ce code de succursale existe déjà pour ce cabinet.']);
        }

        // Check if domain is unique inside this tenant
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

        // Create the domain record centrally so Stancl Tenancy resolves it to this tenant
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
}
