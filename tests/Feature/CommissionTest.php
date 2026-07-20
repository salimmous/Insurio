<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employe;
use App\Models\Client;
use App\Models\Vehicule;
use App\Models\Compagnie;
use App\Models\ContratAuto;
use App\Models\AgenceBranch;
use App\Models\CommissionEmploye;
use Livewire\Livewire;
use App\Livewire\Admin\GestionCommissions;
use Spatie\Permission\Models\Role;

class CommissionTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $responsable;
    private $employe;
    private $contrat;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        $respRole = Role::firstOrCreate(['name' => 'responsable-succursale', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        $this->responsable = User::factory()->create();
        $this->responsable->assignRole($respRole);

        $branch = AgenceBranch::create([
            'nom' => 'Branch Casa',
            'statut' => 'active',
        ]);

        $succursale = \App\Models\Succursale::create([
            'code_succursale' => 'CASA01',
            'nom' => 'Succursale Casa',
            'statut' => 'active',
        ]);

        $this->employe = Employe::create([
            'matricule_employe' => 'EMP-001',
            'nom' => 'El Alami',
            'prenom' => 'Mohamed',
            'email' => 'mohamed@test.com',
            'poste' => 'commercial',
            'taux_commission_defaut' => 10.00,
            'statut' => 'actif',
            'succursale_id' => $succursale->id,
        ]);

        // Link user to employee for auth actions
        $this->admin->employe()->save($this->employe);

        $client = Client::create([
            'nom' => 'Client A',
            'prenom' => 'Jean',
            'cin' => 'AB123456',
            'type' => 'particulier',
        ]);

        $vehicule = Vehicule::create([
            'matricule' => '12345-A-26',
            'marque' => 'Dacia',
            'modele' => 'Logan',
        ]);

        $compagnie = Compagnie::create([
            'nom' => 'AXA',
            'code' => 'AXA',
        ]);

        $this->contrat = ContratAuto::create([
            'numero_contrat' => 'REF-COM-001',
            'client_id' => $client->id,
            'vehicule_id' => $vehicule->id,
            'compagnie_id' => $compagnie->id,
            'branch_id' => $branch->id,
            'succursale_id' => $succursale->id,
            'police' => 'POL-123',
            'date_effet' => '2026-07-19',
            'date_echeance' => '2027-07-19',
            'date_production' => '2026-07-19',
            'prime_rc' => 2000,
            'commission_auto' => 200.00,
            'commission_pta' => 50.00,
            'employe_id' => $this->employe->id,
            'statut' => 'actif',
        ]);
    }

    public function test_admin_can_validate_and_pay_commissions()
    {
        $this->actingAs($this->admin);

        // Generate commission first
        app(\App\Services\CommissionService::class)->calculerCommission($this->contrat);
        $commission = CommissionEmploye::first();
        $this->assertEquals('calculee', $commission->statut);

        // Validate
        Livewire::test(GestionCommissions::class)
            ->call('validerCommission', $commission->id)
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertEquals('validee', $commission->fresh()->statut);

        // Pay
        Livewire::test(GestionCommissions::class)
            ->call('payerCommission', $commission->id)
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertEquals('payee', $commission->fresh()->statut);
    }

    public function test_responsable_cannot_validate_or_pay_commissions()
    {
        $this->actingAs($this->responsable);

        // Generate commission first
        app(\App\Services\CommissionService::class)->calculerCommission($this->contrat);
        $commission = CommissionEmploye::first();
        $this->assertEquals('calculee', $commission->statut);

        // Attempt validate
        Livewire::test(GestionCommissions::class)
            ->call('validerCommission', $commission->id)
            ->assertDispatched('swal:error');

        $this->assertEquals('calculee', $commission->fresh()->statut);

        // Attempt pay
        Livewire::test(GestionCommissions::class)
            ->call('payerCommission', $commission->id)
            ->assertDispatched('swal:error');

        $this->assertEquals('calculee', $commission->fresh()->statut);
    }
}
