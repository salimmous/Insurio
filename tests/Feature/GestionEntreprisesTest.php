<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Livewire\Livewire;
use App\Livewire\Admin\GestionEntreprises;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GestionEntreprisesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_it_renders_gestion_entreprises_component_with_reference_column()
    {
        $company = Client::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'reference' => 'ENT-00100',
            'last_name' => 'RAQMI CASH',
            'company_name' => 'RAQMI CASH',
            'client_type' => 'company',
            'cin' => '998876TR3762R382T82',
            'phone' => '0661599799',
            'email' => 'rz.mottaki@gmail.com',
            'solvabilite' => 'solvable',
            'incident' => false,
        ]);

        Livewire::test(GestionEntreprises::class)
            ->assertSee('RÉFÉRENCE')
            ->assertSee('ENT-00100')
            ->assertSee('RAQMI CASH');
    }

    public function test_it_searches_entreprises_by_reference()
    {
        Client::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'reference' => 'ENT-99999',
            'last_name' => 'TARGET COMPANY',
            'company_name' => 'TARGET COMPANY',
            'client_type' => 'company',
            'solvabilite' => 'solvable',
            'incident' => false,
        ]);

        Livewire::test(GestionEntreprises::class)
            ->set('search', 'ENT-99999')
            ->assertSee('TARGET COMPANY')
            ->set('search', 'NON_EXISTENT')
            ->assertDontSee('TARGET COMPANY');
    }
}
