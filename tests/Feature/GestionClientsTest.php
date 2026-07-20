<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Livewire\Livewire;
use App\Livewire\Admin\GestionClients;
use App\Livewire\Admin\GestionEntreprises;
use Spatie\Permission\Models\Role;

class GestionClientsTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);
    }

    public function test_can_manage_individual_clients()
    {
        $this->actingAs($this->admin);

        // 1. Create client
        Livewire::test(GestionClients::class)
            ->set('nom', 'El Omari')
            ->set('prenom', 'Ali')
            ->set('email', 'ali@example.com')
            ->set('telephone', '0612345678')
            ->set('cin', 'C123456')
            ->set('adresse', 'Casa')
            ->set('solvabilite', 'solvable')
            ->set('incident', false)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $client = Client::first();
        $this->assertNotNull($client);
        $this->assertEquals('El Omari', $client->nom);
        $this->assertEquals('Ali', $client->prenom);
        $this->assertEquals('particulier', $client->type);

        // 2. Edit client
        Livewire::test(GestionClients::class)
            ->call('openModal', $client->id)
            ->set('nom', 'El Omari Mod')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertEquals('El Omari Mod', $client->fresh()->nom);

        // 3. Search client
        Livewire::test(GestionClients::class)
            ->set('search', 'Omari')
            ->assertViewHas('clients', function ($clients) {
                return $clients->count() === 1;
            });

        // 4. Delete client
        Livewire::test(GestionClients::class)
            ->call('delete', $client->id)
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertNull($client->fresh());
    }

    public function test_can_manage_corporate_clients()
    {
        $this->actingAs($this->admin);

        // 1. Create company
        Livewire::test(GestionEntreprises::class)
            ->set('nom', 'AXA Assurance SARL')
            ->set('email', 'contact@axa.ma')
            ->set('telephone', '0522123456')
            ->set('cin', 'ICE-987654321')
            ->set('adresse', 'Rabat')
            ->set('solvabilite', 'solvable')
            ->set('incident', false)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $company = Client::first();
        $this->assertEquals('AXA Assurance SARL', $company->nom);
        $this->assertEquals('', $company->prenom); // Must be empty string
        $this->assertEquals('entreprise', $company->type);

        // 2. Edit company
        Livewire::test(GestionEntreprises::class)
            ->call('openModal', $company->id)
            ->set('nom', 'AXA Assurance SARL Mod')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertEquals('AXA Assurance SARL Mod', $company->fresh()->nom);

        // 3. Search company
        Livewire::test(GestionEntreprises::class)
            ->set('search', 'SARL')
            ->assertViewHas('entreprises', function ($entreprises) {
                return $entreprises->count() === 1;
            });

        // 4. Delete company
        Livewire::test(GestionEntreprises::class)
            ->call('delete', $company->id)
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertNull($company->fresh());
    }

    public function test_can_link_individual_client_to_corporate_client()
    {
        $this->actingAs($this->admin);

        // 1. Create company client
        $company = Client::create([
            'nom' => 'OCP Group',
            'prenom' => '',
            'type' => 'entreprise',
            'cin' => 'ICE-777888',
        ]);

        // 2. Create individual client linked to the company
        Livewire::test(GestionClients::class)
            ->set('nom', 'Saber')
            ->set('prenom', 'Anas')
            ->set('entreprise_id', $company->id)
            ->call('save')
            ->assertHasNoErrors();

        $client = Client::where('nom', 'Saber')->first();
        $this->assertNotNull($client);
        $this->assertEquals($company->id, $client->entreprise_id);
        $this->assertEquals('OCP Group', $client->entreprise->nom);

        // 3. Assert relationship on company
        $this->assertEquals(1, $company->employes()->count());
        $this->assertEquals('Saber', $company->employes->first()->nom);
    }
}
