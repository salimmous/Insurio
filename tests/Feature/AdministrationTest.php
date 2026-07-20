<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Succursale;
use App\Models\Employe;
use App\Models\ContratAuto;
use App\Models\Client;
use App\Models\Vehicule;
use App\Models\Compagnie;
use App\Models\CommissionEmploye;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;
use App\Livewire\Admin\GestionSuccursales;
use App\Livewire\Admin\GestionEmployes;
use App\Livewire\Admin\GestionCommissions;
use App\Livewire\Admin\GestionAgence;
use App\Livewire\Admin\GestionCharges;
use App\Livewire\Admin\AdminDashboard;
use App\Models\AgencyExpense;
use App\Services\CommissionService;
use App\Services\ContractWorkflowService;

class AdministrationTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $responsable;
    private $agent;
    private $succursale1;
    private $succursale2;
    private $empAgent;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Spatie Roles
        $adminRole = Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        $responsableRole = Role::firstOrCreate(['name' => 'responsable-succursale', 'guard_name' => 'web']);
        $commercialRole = Role::firstOrCreate(['name' => 'agent-commercial', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'comptable', 'guard_name' => 'web']);

        // 2. Create Users
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        $this->responsable = User::factory()->create();
        $this->responsable->assignRole($responsableRole);

        $this->agent = User::factory()->create();
        $this->agent->assignRole($commercialRole);

        // 3. Create Succursales
        $this->succursale1 = Succursale::create([
            'code_succursale' => 'SUC-001',
            'nom' => 'Succursale Casa',
            'ville' => 'Casablanca',
            'is_siege' => true,
        ]);

        $this->succursale2 = Succursale::create([
            'code_succursale' => 'SUC-002',
            'nom' => 'Succursale Rabat',
            'ville' => 'Rabat',
        ]);

        // 4. Create Employees
        $empAdmin = Employe::create([
            'user_id' => $this->admin->id,
            'matricule_employe' => 'EMP-001',
            'nom' => 'Admin',
            'prenom' => 'User',
            'succursale_id' => $this->succursale1->id,
            'poste' => 'Administrateur',
            'statut' => 'actif',
        ]);

        $empResp = Employe::create([
            'user_id' => $this->responsable->id,
            'matricule_employe' => 'EMP-002',
            'nom' => 'Responsable',
            'prenom' => 'User',
            'succursale_id' => $this->succursale1->id,
            'poste' => 'Responsable succursale',
            'statut' => 'actif',
        ]);

        $this->empAgent = Employe::create([
            'user_id' => $this->agent->id,
            'matricule_employe' => 'EMP-003',
            'nom' => 'Agent',
            'prenom' => 'User',
            'succursale_id' => $this->succursale2->id,
            'poste' => 'Agent commercial',
            'taux_commission_defaut' => 10.00,
            'statut' => 'actif',
        ]);
    }

    public function test_admin_can_manage_succursales()
    {
        $this->actingAs($this->admin);

        Livewire::test(GestionSuccursales::class)
            ->set('nom', 'New Succursale Tangier')
            ->set('code_succursale', 'SUC-TNG')
            ->set('ville', 'Tangier')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('succursales', [
            'code_succursale' => 'SUC-TNG',
            'nom' => 'New Succursale Tangier',
        ]);
    }

    public function test_admin_can_manage_employes_and_trigger_role_sync()
    {
        $this->actingAs($this->admin);

        $unlinkedUser = User::factory()->create();

        Livewire::test(GestionEmployes::class)
            ->set('nom', 'El Fassi')
            ->set('prenom', 'Anas')
            ->set('matricule_employe', 'EMP-ANAS')
            ->set('succursale_id', $this->succursale1->id)
            ->set('poste', 'Comptable')
            ->set('user_id', $unlinkedUser->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employes', [
            'matricule_employe' => 'EMP-ANAS',
            'poste' => 'Comptable',
        ]);

        $this->assertTrue($unlinkedUser->fresh()->hasRole('comptable'));
    }

    public function test_commission_calculation_validation_and_payout()
    {
        $client = Client::create([
            'nom' => 'Mohamed',
            'prenom' => 'El Alami',
            'cin' => 'AB999999',
        ]);

        $vehicule = Vehicule::create([
            'matricule' => '12345-A-99',
            'marque' => 'Fiat',
            'modele' => 'Punto',
        ]);

        $compagnie = Compagnie::create([
            'nom' => 'AXA',
            'code' => 'AXA',
        ]);

        // Create a contract
        $contrat = ContratAuto::create([
            'numero_contrat' => 'REF-T-01',
            'client_id' => $client->id,
            'vehicule_id' => $vehicule->id,
            'compagnie_id' => $compagnie->id,
            'employe_id' => $this->empAgent->id,
            'succursale_id' => $this->succursale2->id,
            'police' => 'POL-T-01',
            'date_effet' => '2026-07-20',
            'date_echeance' => '2027-07-20',
            'date_production' => '2026-07-20',
            'commission_auto' => 500.00,
            'commission_pta' => 100.00,
            'prime_rc' => 2000.00,
        ]);

        // Calculate commission
        $commission = app(CommissionService::class)->calculerCommission($contrat);

        // Assert calculation: base = 500 + 100 = 600, rate = 10%, commission = 60
        $this->assertEquals(60.00, (float)$commission->montant_commission);
        $this->assertEquals('calculee', $commission->statut);

        // Validate commission
        $this->actingAs($this->admin);
        app(CommissionService::class)->valider($commission, $this->admin->employe->id);
        $this->assertEquals('validee', $commission->fresh()->statut);

        // Pay commission
        app(CommissionService::class)->payer($commission);
        $this->assertEquals('payee', $commission->fresh()->statut);
    }

    public function test_prorated_termination_workflow()
    {
        $client = Client::create([
            'nom' => 'Fatima',
            'prenom' => 'El Alami',
            'cin' => 'CD888888',
        ]);

        $vehicule = Vehicule::create([
            'matricule' => '67890-B-99',
            'marque' => 'Peugeot',
            'modele' => '208',
        ]);

        $compagnie = Compagnie::create([
            'nom' => 'RMA',
            'code' => 'RMA',
        ]);

        $contrat = ContratAuto::create([
            'numero_contrat' => 'REF-T-02',
            'client_id' => $client->id,
            'vehicule_id' => $vehicule->id,
            'compagnie_id' => $compagnie->id,
            'police' => 'POL-T-02',
            'date_effet' => '2026-01-01',
            'date_echeance' => '2026-12-31', // 364 days
            'date_production' => '2026-01-01',
            'prime_rc' => 2000.00,
            'taxe_auto' => 200.00,
        ]);

        // Terminate mid-year (182 days elapsed, approx 50% prorata)
        app(ContractWorkflowService::class)->resilier($contrat, '2026-07-02');

        $this->assertEquals('resilie', $contrat->fresh()->statut);
        $this->assertEquals(1000.00, (float)$contrat->fresh()->prime_rc);
        $this->assertEquals(100.00, (float)$contrat->fresh()->taxe_auto);
    }

    public function test_admin_can_manage_agency_settings()
    {
        $this->actingAs($this->admin);

        Livewire::test(GestionAgence::class)
            ->set('agency_name', 'AXA Maarif New')
            ->set('agency_phone', '+212 5 22 11 11 11')
            ->set('agency_email', 'maarif@axa.ma')
            ->set('agency_address', 'Zerktouni, Casablanca')
            ->set('commission_trigger', 'encaissement')
            ->set('default_apporteur_rate', 15.5)
            ->set('default_agent_rate', 9.0)
            ->call('saveGeneral')
            ->assertHasNoErrors();

        $this->assertEquals('AXA Maarif New', \App\Models\Setting::get('agency_name'));
        $this->assertEquals('encaissement', \App\Models\Setting::get('commission_trigger'));
        $this->assertEquals('15.5', \App\Models\Setting::get('default_apporteur_rate'));
    }

    public function test_admin_can_manage_agency_expenses()
    {
        $this->actingAs($this->admin);

        // 1. Create via Livewire
        Livewire::test(GestionCharges::class)
            ->set('title', 'Loyer Cabinet Casa')
            ->set('category', 'loyer')
            ->set('amount', 5000.00)
            ->set('date_charge', '2026-07-01')
            ->set('description', 'Loyer principal du mois de Juillet')
            ->set('succursale_id', $this->succursale1->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('agency_expenses', [
            'title' => 'Loyer Cabinet Casa',
            'amount' => 5000.00,
            'succursale_id' => $this->succursale1->id,
        ]);

        $expense = AgencyExpense::first();

        // 2. Edit
        Livewire::test(GestionCharges::class)
            ->call('edit', $expense->id)
            ->set('amount', 5500.00)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals(5500.00, (float)$expense->fresh()->amount);

        // 3. Delete
        Livewire::test(GestionCharges::class)
            ->call('delete', $expense->id);

        $this->assertDatabaseMissing('agency_expenses', [
            'id' => $expense->id,
        ]);
    }

    public function test_dashboard_computes_net_cashflow()
    {
        $this->actingAs($this->admin);

        // Create some expenses
        AgencyExpense::create([
            'title' => 'Électricité Rabat',
            'category' => 'electricite',
            'amount' => 1200.00,
            'date_charge' => '2026-07-02',
            'succursale_id' => $this->succursale2->id,
        ]);

        AgencyExpense::create([
            'title' => 'Salaire Secrétaire',
            'category' => 'salaire',
            'amount' => 4000.00,
            'date_charge' => '2026-07-02',
            'succursale_id' => $this->succursale1->id,
        ]);

        // Create a contract to generate production (+)
        $client = Client::create(['nom' => 'Alami', 'prenom' => 'Karim']);
        $vehicule = Vehicule::create(['matricule' => '123-A-15', 'marque' => 'Fiat', 'modele' => '500']);
        $compagnie = Compagnie::create(['nom' => 'Wafa', 'code' => 'WAFA']);

        ContratAuto::create([
            'numero_contrat' => 'REF-C-100',
            'client_id' => $client->id,
            'vehicule_id' => $vehicule->id,
            'compagnie_id' => $compagnie->id,
            'police' => 'POL-C-100',
            'date_effet' => '2026-07-01',
            'date_echeance' => '2027-07-01',
            'date_production' => '2026-07-01',
            'prime_rc' => 10000.00, // Total prime_totale will compute to 10000.00
            'succursale_id' => $this->succursale1->id,
            'statut' => 'actif',
        ]);

        // Test dashboard KPIs
        Livewire::test(AdminDashboard::class)
            ->assertSee('10,000.00')
            ->assertSee('5,200.00') // 1200 + 4000
            ->assertSee('4,800.00'); // 10000 - 5200
    }

    public function test_agency_admin_can_save_whitelabel_branding()
    {
        // Create Tenant with unique randomized ID
        $tenantId = 'axatest' . uniqid();
        $tenant = \App\Models\Tenant::create([
            'id' => $tenantId,
            'name' => 'Axa Test',
        ]);
        app(\Stancl\Tenancy\Tenancy::class)->tenant = $tenant;

        // Upload testing
        \Illuminate\Support\Facades\Storage::fake('public');
        $logoFile = \Illuminate\Http\UploadedFile::fake()->image('logo.png');
        $favFile = \Illuminate\Http\UploadedFile::fake()->image('favicon.ico');

        Livewire::actingAs($this->admin)
            ->test(GestionAgence::class)
            ->set('logo', $logoFile)
            ->set('favicon', $favFile)
            ->set('couleur_primaire', '#112233')
            ->set('couleur_secondaire', '#445566')
            ->call('saveWhiteLabel')
            ->assertHasNoErrors();

        $this->assertEquals('#112233', tenant('couleur_primaire'));
        $this->assertEquals('#445566', tenant('couleur_secondaire'));
        $this->assertNotNull(tenant('logo_path'));
        $this->assertNotNull(tenant('favicon_path'));
        app(\Stancl\Tenancy\Tenancy::class)->tenant = null;
        $tenant->delete();
    }

    public function test_agency_admin_can_save_access_control_permissions()
    {
        $tenant = \App\Models\Tenant::create([
            'id' => 'access-control-test',
            'name' => 'Access Control Test Agency'
        ]);
        $tenant->domains()->create(['domain' => 'access-control-test.localhost']);
        
        app(\Stancl\Tenancy\Tenancy::class)->tenant = $tenant;

        $role = Role::firstOrCreate(['name' => 'agency-admin']);
        $user = \App\Models\User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@accesscontrol.com',
        ]);
        $user->assignRole($role);

        $this->actingAs($user);

        \Livewire\Livewire::test(\App\Livewire\Admin\GestionAgence::class)
            ->set('enabled_pages', ['dashboard', 'automobile'])
            ->set('enabled_roles', ['agent-commercial'])
            ->call('saveAccessControl')
            ->assertHasNoErrors()
            ->assertSee('Les droits d\'accès (Pages & Rôles) ont été enregistrés avec succès.');

        // Assert Settings are correctly persisted in the tenant database
        $this->assertEquals(
            ['dashboard', 'automobile'],
            json_decode(\App\Models\Setting::get('enabled_pages'), true)
        );
        $this->assertEquals(
            ['agent-commercial'],
            json_decode(\App\Models\Setting::get('enabled_roles'), true)
        );

        app(\Stancl\Tenancy\Tenancy::class)->tenant = null;
        $tenant->delete();
    }
}
