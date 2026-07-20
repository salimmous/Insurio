<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\Vehicule;
use App\Models\Compagnie;
use App\Models\ContratAuto;
use App\Models\Apporteur;
use App\Models\AgenceBranch;
use Livewire\Livewire;
use App\Livewire\Automobile\FormulaireContrat;
use App\Livewire\Automobile\ListeContrats;
use App\Models\User;

class AutomobileTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $client;
    private $vehicule;
    private $compagnie;
    private $branch;

    protected function setUp(): void
    {
        parent::setUp();

        // Create initial setup
        $this->user = User::factory()->create();

        $this->branch = AgenceBranch::create([
            'nom' => 'Branch Casa',
            'statut' => 'active',
        ]);

        $this->client = Client::create([
            'nom' => 'El Alami',
            'prenom' => 'Mohamed',
            'cin' => 'AB123456',
            'type' => 'particulier',
        ]);

        $this->vehicule = Vehicule::create([
            'matricule' => '12345-A-26',
            'marque' => 'Dacia',
            'modele' => 'Logan',
        ]);

        $this->compagnie = Compagnie::create([
            'nom' => 'AXA',
            'code' => 'AXA',
        ]);
    }

    public function test_contrat_auto_calculations_on_model_saving()
    {
        $contrat = ContratAuto::create([
            'numero_contrat' => 'REF-001',
            'client_id' => $this->client->id,
            'vehicule_id' => $this->vehicule->id,
            'compagnie_id' => $this->compagnie->id,
            'branch_id' => $this->branch->id,
            'police' => 'POL-123',
            'date_effet' => '2026-07-19',
            'date_echeance' => '2027-07-19',
            'date_production' => '2026-07-19',
            'prime_rc' => 2000,
            'def_rec' => 100,
            'taxe_auto' => 200,
            'timbre' => 20,
            'montant_pta' => 500,
            'montant_taxe_pta' => 50,
            'accessoires' => 10,
        ]);

        // Assert prime_nette = 2000 + 100 = 2100
        $this->assertEquals(2100.00, (float)$contrat->prime_nette);

        // Assert prime_totale = 2100 + 200 + 20 + 500 + 50 + 10 = 2880
        $this->assertEquals(2880.00, (float)$contrat->prime_totale);
    }

    public function test_formulaire_contrat_live_calculations()
    {
        $this->actingAs($this->user);

        Livewire::test(FormulaireContrat::class)
            ->set('prime_rc', 3000)
            ->set('def_rec', 150)
            ->set('taxe_auto', 300)
            ->set('timbre', 25)
            ->set('montant_pta', 600)
            ->set('montant_taxe_pta', 60)
            ->set('accessoires', 15)
            ->assertSet('primeNette', 3150.00)
            ->assertSet('primeTotale', 4150.00);
    }

    public function test_formulaire_contrat_validation_and_creation()
    {
        $this->actingAs($this->user);

        Livewire::test(FormulaireContrat::class)
            ->set('numero_contrat', 'REF-NEW-01')
            ->set('compagnie_id', $this->compagnie->id)
            ->set('police', 'POL-NEW-99')
            ->set('client_id', $this->client->id)
            ->set('branch_id', $this->branch->id)
            ->set('date_effet', '2026-07-19')
            ->set('nbr_mois', 12)
            ->set('prime_rc', 2000)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('automobile.index'));

        $this->assertDatabaseHas('contrats_auto', [
            'numero_contrat' => 'REF-NEW-01',
            'police' => 'POL-NEW-99',
            'prime_totale' => 2000.00, // zero taxes, accessories, pta set yet
        ]);
    }

    public function test_liste_contrats_actions()
    {
        $this->actingAs($this->user);

        $contrat = ContratAuto::create([
            'numero_contrat' => 'REF-001',
            'client_id' => $this->client->id,
            'vehicule_id' => $this->vehicule->id,
            'compagnie_id' => $this->compagnie->id,
            'branch_id' => $this->branch->id,
            'police' => 'POL-123',
            'date_effet' => '2026-07-19',
            'date_echeance' => '2027-07-19',
            'date_production' => '2026-07-19',
            'prime_rc' => 2000,
            'statut' => 'actif',
        ]);

        Livewire::test(ListeContrats::class)
            ->set('selectedContratId', $contrat->id)
            ->call('resilierContrat')
            ->assertHasNoErrors();

        $this->assertEquals('resilie', $contrat->fresh()->statut);
    }

    public function test_liste_contrats_relance_email()
    {
        \Illuminate\Support\Facades\Mail::fake();
        
        // Assign admin role to bypass branch scoping
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        $this->user->assignRole($adminRole);
        
        $this->actingAs($this->user);

        // Configure Mail settings
        \App\Models\Setting::set('mail_host', 'smtp.mailtrap.io');
        \App\Models\Setting::set('mail_port', '2525');
        \App\Models\Setting::set('mail_username', 'user');
        \App\Models\Setting::set('mail_password', 'pass');
        \App\Models\Setting::set('mail_from_address', 'test@insurio.com');
        \App\Models\Setting::set('mail_from_name', 'Insurio App');

        $this->client->update(['email' => 'client@test.com']);

        $contrat = ContratAuto::create([
            'numero_contrat' => 'REF-EMAIL-99',
            'client_id' => $this->client->id,
            'vehicule_id' => $this->vehicule->id,
            'compagnie_id' => $this->compagnie->id,
            'branch_id' => $this->branch->id,
            'police' => 'POL-123',
            'date_effet' => '2026-07-19',
            'date_echeance' => '2027-07-19',
            'date_production' => '2026-07-19',
            'prime_rc' => 2000,
            'statut' => 'actif',
        ]);

        $component = new ListeContrats();
        $component->selectedContratId = $contrat->id;
        $component->relancerParEmail();

        $this->assertEquals(
            "E-mail de rappel envoyé avec succès à {$this->client->email} !",
            session('message')
        );

        \Illuminate\Support\Facades\Mail::assertSentCount(1);
    }

    public function test_liste_contrats_can_manage_reglements()
    {
        $this->actingAs($this->user);

        $contrat = ContratAuto::create([
            'numero_contrat' => 'REF-PAY-777',
            'client_id' => $this->client->id,
            'vehicule_id' => $this->vehicule->id,
            'compagnie_id' => $this->compagnie->id,
            'branch_id' => $this->branch->id,
            'police' => 'POL-PAY-777',
            'date_effet' => '2026-07-19',
            'date_echeance' => '2027-07-19',
            'date_production' => '2026-07-19',
            'prime_rc' => 3000,
            'taxe_auto' => 300,
            'timbre' => 20,
            'statut' => 'actif',
        ]);

        // Total premium is 3000 + 300 + 20 = 3320
        $this->assertEquals(3320.00, $contrat->prime_totale);
        $this->assertEquals(3320.00, $contrat->solde);

        // Record a payment of 1320
        Livewire::test(ListeContrats::class)
            ->set('selectedContratId', $contrat->id)
            ->call('openReglementsModal')
            ->assertSet('reglementMontant', 3320.00)
            ->set('reglementMontant', 1320.00)
            ->set('reglementDate', '2026-07-20')
            ->set('reglementMode', 'cheque')
            ->set('reglementReference', 'CHQ-100200')
            ->call('addReglement')
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertEquals(2000.00, $contrat->fresh()->solde);
        $this->assertEquals(1, $contrat->reglements()->count());

        $reglement = $contrat->reglements->first();
        $this->assertEquals(1320.00, $reglement->montant);
        $this->assertEquals('cheque', $reglement->mode_reglement);
        $this->assertEquals('CHQ-100200', $reglement->reference_paiement);

        // Delete payment
        Livewire::test(ListeContrats::class)
            ->set('selectedContratId', $contrat->id)
            ->call('deleteReglement', $reglement->id)
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertEquals(3320.00, $contrat->fresh()->solde);
        $this->assertEquals(0, $contrat->reglements()->count());
    }
}
