<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Client;
use App\Models\Compagnie;
use App\Models\ContratAuto;
use App\Models\AgenceBranch;
use Livewire\Livewire;
use App\Livewire\Admin\GestionProducts;
use App\Livewire\Automobile\FormulaireContrat;
use Spatie\Permission\Models\Role;

class GestionProductsTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $client;
    private $compagnie;
    private $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        $this->client = Client::create([
            'nom' => 'Client',
            'prenom' => 'Test',
            'cin' => 'AB123456',
            'type' => 'particulier',
        ]);

        $this->compagnie = Compagnie::create([
            'nom' => 'AXA',
            'code' => 'AXA',
        ]);

        $this->branch = AgenceBranch::create([
            'nom' => 'Branch Casa',
            'statut' => 'active',
        ]);
    }

    public function test_can_manage_insurance_products()
    {
        $this->actingAs($this->admin);

        // 1. Create product
        Livewire::test(GestionProducts::class)
            ->set('code', 'TEST')
            ->set('nom', 'Test Product')
            ->set('description', 'Multirisque Habitation')
            ->set('statut', 'actif')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $product = Product::where('code', 'TEST')->first();
        $this->assertNotNull($product);
        $this->assertEquals('Test Product', $product->nom);

        // 2. Edit product
        Livewire::test(GestionProducts::class)
            ->call('openModal', $product->id)
            ->set('nom', 'Test Product Modifiée')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertEquals('Test Product Modifiée', $product->fresh()->nom);

        // 3. Search product
        Livewire::test(GestionProducts::class)
            ->set('search', 'TEST')
            ->assertViewHas('products', function ($products) {
                return $products->count() === 1;
            });

        // 4. Delete product
        Livewire::test(GestionProducts::class)
            ->call('delete', $product->id)
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertNull($product->fresh());
    }

    public function test_contract_form_integrates_products()
    {
        $this->actingAs($this->admin);

        // Ensure default products exist
        $product = Product::firstOrCreate(
            ['code' => 'AUTO'],
            ['nom' => 'Automobile', 'description' => 'Auto desc', 'statut' => 'actif']
        );

        $motoProduct = Product::firstOrCreate(
            ['code' => 'MOTO'],
            ['nom' => 'Moto / Deux Roues', 'description' => 'Moto desc', 'statut' => 'actif']
        );

        // Load contract creation form
        Livewire::test(FormulaireContrat::class)
            ->assertSet('product_id', $product->id)
            ->assertSet('branche_code', 'AUTO')
            ->set('product_id', $motoProduct->id)
            ->assertSet('branche_code', 'MOTO')
            ->assertSet('branche_libelle', 'Moto')
            ->set('numero_contrat', 'REF-TEST-999')
            ->set('compagnie_id', $this->compagnie->id)
            ->set('police', 'POL-TEST-999')
            ->set('client_id', $this->client->id)
            ->set('prime_rc', 1000)
            ->call('save')
            ->assertHasNoErrors();

        $contract = ContratAuto::where('numero_contrat', 'REF-TEST-999')->first();
        $this->assertNotNull($contract);
        $this->assertEquals($motoProduct->id, $contract->product_id);
        $this->assertEquals('MOTO', $contract->branche_code);
        $this->assertEquals('Moto', $contract->branche_libelle);
    }
}
