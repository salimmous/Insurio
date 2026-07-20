<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Compagnie;
use App\Models\Succursale;
use App\Models\Employe;
use App\Models\Dossier;
use App\Models\Tenant;
use App\Models\Task;
use App\Models\Communication;
use Livewire\Livewire;
use App\Livewire\Admin\GestionDossiers;
use App\Livewire\Admin\DossierWorkspace;
use Spatie\Permission\Models\Role;

class GestionDossiersTest extends TestCase
{
    use RefreshDatabase;

    private $tenant;
    private $admin;
    private $branch;
    private $employee;
    private $client;
    private $contract;
    private $compagnie;

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
            'id' => 'dossiertest',
            'name' => 'Dossier Test Agency',
        ]);
        $this->tenant->domains()->create(['domain' => 'localhost']);
        tenancy()->initialize($this->tenant);

        // Roles
        $adminRole = Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        // Core data
        $this->branch = Succursale::create([
            'code_succursale' => 'SUC-TEST',
            'nom' => 'Succursale Test',
            'ville' => 'Casablanca',
            'is_active' => true,
        ]);

        $this->employee = Employe::create([
            'user_id' => $this->admin->id,
            'matricule_employe' => 'EMP-TEST-001',
            'nom' => 'El Alami',
            'prenom' => 'Karim',
            'email' => 'karim@test.com',
            'succursale_id' => $this->branch->id,
            'poste' => 'conseiller',
            'statut' => 'actif',
        ]);

        $this->client = Client::create([
            'first_name' => 'Tariq',
            'last_name' => 'Benoumar',
            'client_type' => 'individual',
            'cin' => 'EE123456',
            'phone' => '0611223344',
            'succursale_id' => $this->branch->id,
        ]);

        $this->compagnie = Compagnie::create([
            'nom' => 'AXA Assurance',
            'code' => 'AXA',
        ]);

        $this->contract = Contract::create([
            'contract_number' => 'POL-TEST-100',
            'policy_number' => 'POL-TEST-100',
            'client_id' => $this->client->id,
            'insurance_company_id' => $this->compagnie->id,
            'succursale_id' => $this->branch->id,
            'employe_id' => $this->employee->id,
            'start_date' => '2026-01-01',
            'end_date' => '2027-01-01',
        ]);
    }

    protected function tearDown(): void
    {
        tenancy()->end();
        parent::tearDown();
    }

    public function test_can_view_dossiers_list_and_dashboard()
    {
        $this->actingAs($this->admin);

        Livewire::test(GestionDossiers::class)
            ->assertStatus(200)
            ->assertSee("Centre de Gestion des Dossiers")
            ->assertSee("Dossiers Ouverts");
    }

    public function test_can_create_dossier_with_automation()
    {
        $this->actingAs($this->admin);

        // Make request on tenant domain to initialize routing host
        $this->get('http://localhost/admin/dossiers');

        // Create case via Livewire
        Livewire::test(GestionDossiers::class)
            ->set('type', 'claim')
            ->set('client_id', $this->client->id)
            ->set('contract_id', $this->contract->id)
            ->set('compagnie_id', $this->compagnie->id)
            ->set('succursale_id', $this->branch->id)
            ->set('assigned_employee_id', $this->employee->id)
            ->set('priority', 'high')
            ->set('urgency', 'medium')
            ->call('createDossier')
            ->assertHasNoErrors();

        // Verify dossier in DB
        $dossier = Dossier::first();
        $this->assertNotNull($dossier);
        $this->assertEquals('claim', $dossier->type);
        $this->assertEquals('open', $dossier->status);
        $this->assertEquals('high', $dossier->priority);
        $this->assertStringStartsWith('DS-', $dossier->dossier_number);

        // Verify automation side-effects
        // 1. Follow-up created
        $this->assertDatabaseHas('dossier_follow_ups', [
            'dossier_id' => $dossier->id,
            'employee_id' => $this->employee->id,
            'priority' => 'high',
        ]);

        // 2. Timeline Communication log created
        $this->assertDatabaseHas('communications', [
            'dossier_id' => $dossier->id,
            'client_id' => $this->client->id,
            'type' => 'note',
        ]);
    }

    public function test_workspace_tab_switching_and_updates()
    {
        $this->actingAs($this->admin);

        $dossier = Dossier::create([
            'type' => 'complaint',
            'status' => 'open',
            'priority' => 'medium',
            'urgency' => 'medium',
            'succursale_id' => $this->branch->id,
            'client_id' => $this->client->id,
            'creation_date' => now()->toDateString(),
            'progress' => 10,
            'checklist' => [['name' => 'Ouverture', 'completed' => false]],
        ]);

        // Verify tab renders
        Livewire::test(DossierWorkspace::class, ['id' => $dossier->id])
            ->assertStatus(200)
            ->assertSee($dossier->dossier_number)
            ->set('activeTab', 'timeline')
            ->assertSee("Timeline")
            ->set('activeTab', 'tasks')
            ->set('task_title', 'Rappeler le client')
            ->call('addTask')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tasks', [
            'dossier_id' => $dossier->id,
            'title' => 'Rappeler le client',
        ]);
    }

    public function test_can_save_accident_expert_and_garage_details()
    {
        $this->actingAs($this->admin);

        $dossier = Dossier::create([
            'type' => 'claim',
            'status' => 'open',
            'priority' => 'medium',
            'urgency' => 'medium',
            'succursale_id' => $this->branch->id,
            'client_id' => $this->client->id,
            'creation_date' => now()->toDateString(),
            'progress' => 10,
        ]);

        Livewire::test(DossierWorkspace::class, ['id' => $dossier->id])
            ->set('accident_city', 'Rabat')
            ->set('accident_address', 'Avenue Hassan II')
            ->set('police_present', true)
            ->call('saveAccidentDetails')
            ->assertHasNoErrors()
            
            ->set('expert_name', 'Ali Expert')
            ->set('estimated_damage', 5000)
            ->call('saveExpertDetails')
            ->assertHasNoErrors()

            ->set('garage_name', 'Top Garage')
            ->set('garage_status', 'in_progress')
            ->call('saveGarageDetails')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('dossier_accident_details', [
            'dossier_id' => $dossier->id,
            'accident_city' => 'Rabat',
            'police_present' => true,
        ]);

        $this->assertDatabaseHas('dossier_expert_details', [
            'dossier_id' => $dossier->id,
            'expert_name' => 'Ali Expert',
            'estimated_damage' => 5000,
        ]);

        $this->assertDatabaseHas('dossier_garage_details', [
            'dossier_id' => $dossier->id,
            'garage_name' => 'Top Garage',
            'status' => 'in_progress',
        ]);
    }

    public function test_copilot_ai_assistant_returns_mocked_results()
    {
        $this->actingAs($this->admin);

        $dossier = Dossier::create([
            'type' => 'claim',
            'status' => 'open',
            'priority' => 'medium',
            'urgency' => 'medium',
            'succursale_id' => $this->branch->id,
            'client_id' => $this->client->id,
            'creation_date' => now()->toDateString(),
            'progress' => 10,
            'checklist' => [['name' => 'Constat amiable', 'completed' => false]],
        ]);

        Livewire::test(DossierWorkspace::class, ['id' => $dossier->id])
            ->set('activeTab', 'ai_assistant')
            ->call('askAi', 'summary')
            ->assertSee("Analyse Synthétique AI")
            ->call('askAi', 'missing_docs')
            ->assertSee("Détection des pièces manquantes");
    }
}
