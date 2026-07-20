<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Reglement;
use App\Models\ActivityLog;
use App\Models\Renewal;
use App\Models\Compagnie;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;

class OperationalSystemsTest extends TestCase
{
    use RefreshDatabase;

    private $tenant;
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up left-over tenant DBs
        foreach (glob(database_path('*tenant*')) as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        // Setup tenant
        $this->tenant = Tenant::create([
            'id' => 'opsagency',
            'name' => 'Operational Agency',
        ]);
        $this->tenant->domains()->create(['domain' => 'ops.localhost']);
        tenancy()->initialize($this->tenant);

        // Define agency-admin role
        $adminRole = Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'agent-commercial', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'comptable', 'guard_name' => 'web']);

        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        // Create default compagnie
        Compagnie::create([
            'id' => 1,
            'nom' => 'AXA Assurance',
            'statut' => 'active',
        ]);
    }

    protected function tearDown(): void
    {
        tenancy()->end();
        parent::tearDown();
    }

    public function test_roles_and_permissions_exist()
    {
        $this->assertTrue(Role::where('name', 'agency-admin')->exists());
        $this->assertTrue(Role::where('name', 'agent-commercial')->exists());
        $this->assertTrue(Role::where('name', 'comptable')->exists());
        $this->assertTrue($this->admin->hasRole('agency-admin'));
    }

    public function test_client_activity_logs_automatically()
    {
        $client = Client::create([
            'first_name' => 'Adnane',
            'last_name' => 'Berrada',
            'cin' => 'AX112233',
            'client_type' => 'individual',
        ]);

        $this->assertTrue(ActivityLog::where('model_type', Client::class)
            ->where('model_id', $client->id)
            ->where('action', 'created')
            ->exists());
    }

    public function test_payments_sync_with_reglements()
    {
        $client = Client::create([
            'first_name' => 'Mourad',
            'last_name' => 'Tazi',
            'cin' => 'BY998877',
            'client_type' => 'individual',
        ]);

        $contract = Contract::create([
            'client_id' => $client->id,
            'contract_number' => 'POL-SYNC-999',
            'policy_number' => 'POL-SYNC-999',
            'insurer_id' => 1,
            'insurance_product_id' => 1,
            'premium_amount' => 5000.00,
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => 'active',
            'commission_rate' => 10.0,
            'compagnie_id' => 1,
        ]);

        $payment = Payment::create([
            'client_id' => $client->id,
            'contract_id' => $contract->id,
            'amount' => 1500.00,
            'payment_method' => 'bank_transfer',
            'status' => 'paid',
            'reference' => 'TXN-SYNC-888',
        ]);

        $this->assertTrue(Reglement::where('contrat_id', $contract->id)
            ->where('montant', 1500.00)
            ->where('reference_paiement', 'TXN-SYNC-888')
            ->exists());
    }

    public function test_contract_renewal_status_updates()
    {
        $client = Client::create([
            'first_name' => 'Karim',
            'last_name' => 'Bennani',
            'cin' => 'CZ554433',
            'client_type' => 'individual',
        ]);

        $contract = Contract::create([
            'client_id' => $client->id,
            'contract_number' => 'POL-EXP-111',
            'policy_number' => 'POL-EXP-111',
            'insurer_id' => 1,
            'insurance_product_id' => 1,
            'premium_amount' => 3000.00,
            'start_date' => now()->subYear(),
            'end_date' => now()->addDays(7)->toDateString(),
            'status' => 'active',
            'commission_rate' => 10.0,
            'compagnie_id' => 1,
        ]);

        // Run check command
        Artisan::call('contracts:check-expiry');

        $this->assertTrue(Renewal::where('contract_id', $contract->id)->exists());
    }

    public function test_dashboard_caching_and_subqueries()
    {
        $client = Client::create([
            'first_name' => 'Sara',
            'last_name' => 'El',
            'cin' => 'EE112233',
            'client_type' => 'individual',
        ]);

        $contract = Contract::create([
            'client_id' => $client->id,
            'contract_number' => 'POL-DASH-777',
            'policy_number' => 'POL-DASH-777',
            'insurer_id' => 1,
            'insurance_product_id' => 1,
            'premium_amount' => 4000.00,
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => 'active',
            'commission_rate' => 10.0,
            'compagnie_id' => 1,
            'statut' => 'actif',
            'prime_totale' => 4500.00,
        ]);

        // Add a payment (reglement)
        Reglement::create([
            'contrat_id' => $contract->id,
            'montant' => 1000.00,
            'date_reglement' => now(),
            'mode_reglement' => 'especes',
            'reference_paiement' => 'REF-111',
        ]);

        // Test Livewire AdminDashboard execution and KPI computations
        $dashboard = new \App\Livewire\Admin\AdminDashboard();
        $dashboard->render();

        // 4500.00 (prime_totale) - 1000.00 (reglement) = 3500.00 (totalImpayes)
        $this->assertEquals(3500.00, $dashboard->totalImpayes);

        // Verify cache keys exist under non-tagging driver
        $cacheKey = 'dashboard_kpis_' . tenant('id') . '_branch_all';
        $this->assertTrue(\Illuminate\Support\Facades\Cache::has($cacheKey));

        // Test cache buster manually
        $dashboard->refreshDashboard();
        $this->assertFalse(\Illuminate\Support\Facades\Cache::has($cacheKey));

        $dashboard->render();
        $this->assertTrue(\Illuminate\Support\Facades\Cache::has($cacheKey));

        // Simulate new payment that triggers cache bust automatically
        Payment::create([
            'client_id' => $client->id,
            'contract_id' => $contract->id,
            'amount' => 500.00,
            'payment_method' => 'cash',
            'status' => 'paid',
        ]);

        // Dashboard cache should be bust/forgotten after payment is saved
        $this->assertFalse(\Illuminate\Support\Facades\Cache::has($cacheKey));
    }
}
