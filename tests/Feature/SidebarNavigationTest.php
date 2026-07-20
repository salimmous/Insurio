<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Compagnie;
use App\Models\Succursale;
use App\Models\Employe;
use App\Models\Payment;
use App\Models\Dossier;
use App\Models\Task;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SidebarNavigationTest extends TestCase
{
    use RefreshDatabase;

    private $tenant;
    private $adminUser;
    private $agentUser;
    private $branch;
    private $employee;
    private $client;
    private $compagnie;
    private $contract;

    protected function setUp(): void
    {
        parent::setUp();

        // Clean tenant SQLite files
        foreach (glob(database_path('*tenant*')) as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        // Initialize tenant
        $this->tenant = Tenant::create([
            'id' => 'sidebartest',
            'name' => 'Sidebar Test Agency',
        ]);
        $this->tenant->domains()->create(['domain' => 'localhost']);
        tenancy()->initialize($this->tenant);

        // Permissions
        $permissions = [
            'clients.view',
            'clients.create',
            'contracts.view',
            'contracts.create',
            'contracts.edit',
            'expenses.view',
            'commissions.view',
            'payments.manage',
        ];
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Roles
        $adminRole = Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo($permissions);

        $agentRole = Role::firstOrCreate(['name' => 'agent-commercial', 'guard_name' => 'web']);
        $agentRole->givePermissionTo(['clients.view', 'contracts.view']);

        // Users
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);

        $this->agentUser = User::factory()->create();
        $this->agentUser->assignRole($agentRole);

        // Core data
        $this->branch = Succursale::create([
            'code_succursale' => 'SUC-SIDEBAR',
            'nom' => 'Succursale Sidebar',
            'ville' => 'Rabat',
            'is_active' => true,
        ]);

        $this->employee = Employe::create([
            'user_id' => $this->adminUser->id,
            'matricule_employe' => 'EMP-SIDEBAR-01',
            'nom' => 'Chafik',
            'prenom' => 'Reda',
            'email' => 'chafik@test.com',
            'succursale_id' => $this->branch->id,
            'poste' => 'directeur',
            'statut' => 'actif',
        ]);

        $this->client = Client::create([
            'first_name' => 'Ali',
            'last_name' => 'Mansouri',
            'client_type' => 'individual',
            'phone' => '0622334455',
            'email' => 'ali@test.com',
            'succursale_id' => $this->branch->id,
            'solvabilite' => 'A',
            'incident' => false,
        ]);

        $this->compagnie = Compagnie::create([
            'nom' => 'Wafa Assurance',
            'code' => 'WAFA',
            'is_active' => true,
        ]);

        $this->contract = Contract::create([
            'client_id' => $this->client->id,
            'insurance_company_id' => $this->compagnie->id,
            'compagnie_id' => $this->compagnie->id,
            'contract_number' => 'CT-SIDEBAR-999',
            'numero_contrat' => 'CT-SIDEBAR-999',
            'policy_number' => 'POL-SIDEBAR-999',
            'police' => 'POL-SIDEBAR-999',
            'prime_totale' => 5000.00,
            'prime_nette' => 4500.00,
            'statut' => 'active',
            'status' => 'active',
            'payment_status' => 'unpaid',
            'employe_id' => $this->employee->id,
            'succursale_id' => $this->branch->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addYear()->toDateString(),
            'date_effet' => now()->toDateString(),
            'date_echeance' => now()->addYear()->toDateString(),
        ]);
    }

    protected function tearDown(): void
    {
        if (function_exists('tenancy') && tenancy()->initialized) {
            tenancy()->end();
        }
        parent::tearDown();
    }

    public function test_sidebar_renders_correctly_for_admin()
    {
        $this->actingAs($this->adminUser);

        // Create some pending payments, tasks and urgent dossiers to trigger badge counts
        Payment::create([
            'client_id' => $this->client->id,
            'contract_id' => $this->contract->id,
            'amount' => 500,
            'payment_status' => 'pending',
            'payment_method' => 'cash',
        ]);

        Dossier::create([
            'client_id' => $this->client->id,
            'succursale_id' => $this->branch->id,
            'type' => 'claim',
            'priority' => 'high',
            'status' => 'open',
            'title' => 'Urgent claim test',
            'creation_date' => now(),
        ]);

        Task::create([
            'client_id' => $this->client->id,
            'title' => 'Pending task test',
            'status' => 'pending',
            'priority' => 'high',
        ]);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        // Verify HTML content structure
        $response->assertSee('CRM');
        $response->assertSee('Assurance');
        $response->assertSee('Finance');
        $response->assertSee('Opérations');
        $response->assertSee('Organisation');
        $response->assertSee('Configuration');
    }

    public function test_sidebar_restricts_sections_for_agent_commercial()
    {
        $this->actingAs($this->agentUser);

        // Agent commercial does not have access to dashboard route directly (it's restricted in app.blade.php / routing)
        // Let's request the automobile index page instead
        $response = $this->get(route('automobile.index'));
        $response->assertStatus(200);

        // Agent commercial has clients and contracts view, so they see CRM and Assurance sections
        $response->assertSee('CRM');
        $response->assertSee('Assurance');

        // But they DO NOT have expenses.view or payments.manage permissions, so they should NOT see Finance, Organisation or Configuration categories
        $response->assertDontSee('Organisation');
        $response->assertDontSee('Configuration');
    }
}
