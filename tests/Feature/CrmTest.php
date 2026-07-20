<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Contract;
use App\Models\AutoContractDetail;
use App\Models\Document;
use App\Models\Task;
use App\Models\Setting;
use App\Models\Landlord\Plan;
use App\Models\Tenant;
use Livewire\Livewire;
use App\Livewire\Admin\ClientProfile;
use App\Livewire\Admin\TaskManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;

class CrmTest extends TestCase
{
    use RefreshDatabase;

    private $tenant;
    private $admin;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up any left-over tenant SQLite database files
        foreach (glob(database_path('*tenant*')) as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        // Initialize tenant
        $this->tenant = Tenant::create([
            'id' => 'crmtest',
            'name' => 'CRM Test Agency',
        ]);
        $this->tenant->domains()->create(['domain' => 'crmtest.localhost']);
        tenancy()->initialize($this->tenant);

        // Run roles setup and create admin inside tenant context
        $adminRole = Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        $this->client = Client::create([
            'first_name' => 'Anas',
            'last_name' => 'Saber',
            'client_type' => 'individual',
            'cin' => 'AB123456',
            'phone' => '0600000000',
            'email' => 'anas@example.com',
            'city' => 'Casablanca',
        ]);
    }

    protected function tearDown(): void
    {
        tenancy()->end();
        parent::tearDown();
    }

    public function test_client_profile_page_loads_and_updates_notes()
    {
        $this->actingAs($this->admin);

        Livewire::test(ClientProfile::class, ['clientId' => $this->client->id])
            ->assertSee('Anas Saber')
            ->set('clientNotes', 'Notes importantes sur Saber')
            ->call('saveNotes')
            ->assertDispatched('swal:success');

        $this->assertEquals('Notes importantes sur Saber', $this->client->fresh()->notes);
        $this->assertDatabaseHas('communications', [
            'client_id' => $this->client->id,
            'type' => 'note',
            'message' => 'Modification des notes du client.',
        ]);
    }

    public function test_client_profile_logs_contact_activity()
    {
        $this->actingAs($this->admin);

        Livewire::test(ClientProfile::class, ['clientId' => $this->client->id])
            ->set('communicationType', 'whatsapp')
            ->set('communicationMessage', 'Message WhatsApp envoyé pour le devis')
            ->call('addCommunication')
            ->assertDispatched('swal:success');

        $this->assertDatabaseHas('communications', [
            'client_id' => $this->client->id,
            'type' => 'whatsapp',
            'message' => 'Message WhatsApp envoyé pour le devis',
        ]);
    }

    public function test_client_profile_can_upload_and_delete_documents()
    {
        $this->actingAs($this->admin);
        Storage::fake('local');

        $file = UploadedFile::fake()->create('cin_recto.jpg', 500);

        Livewire::test(ClientProfile::class, ['clientId' => $this->client->id])
            ->set('documentType', 'cin')
            ->set('uploadedFile', $file)
            ->call('uploadDocument')
            ->assertDispatched('swal:success');

        $doc = Document::first();
        $this->assertNotNull($doc);
        $this->assertEquals('cin', $doc->type);
        $this->assertEquals('cin_recto.jpg', $doc->file_name);
        Storage::disk('local')->assertExists($doc->file_path);

        // Delete document
        Livewire::test(ClientProfile::class, ['clientId' => $this->client->id])
            ->call('deleteDocument', $doc->id)
            ->assertDispatched('swal:success');

        $this->assertNull(Document::find($doc->id));
        Storage::disk('local')->assertMissing($doc->file_path);
    }

    public function test_can_manage_tasks_via_kanban()
    {
        $this->actingAs($this->admin);

        $task = Task::create([
            'title' => 'Relance devis',
            'client_id' => $this->client->id,
            'status' => 'todo',
            'priority' => 'high',
        ]);

        Livewire::test(TaskManager::class)
            ->assertSee('Relance devis')
            ->call('updateTaskStatus', $task->id, 'progress')
            ->assertDispatched('swal:success');

        $this->assertEquals('progress', $task->fresh()->status);
    }

    public function test_api_client_endpoints_are_token_authenticated()
    {
        Setting::set('api_token', 'super_secret_api_token');

        // Without token
        $this->getJson('http://crmtest.localhost/api/v1/clients')
            ->assertStatus(401);

        // With token
        $this->withHeaders(['Authorization' => 'Bearer super_secret_api_token'])
            ->getJson('http://crmtest.localhost/api/v1/clients')
            ->assertStatus(200);
    }

    public function test_api_can_create_client_and_polymorphic_contract()
    {
        Setting::set('api_token', 'api_token_test');
        $this->withHeaders(['Authorization' => 'Bearer api_token_test']);

        // Create Client via API
        $clientResponse = $this->postJson('http://crmtest.localhost/api/v1/clients', [
            'first_name' => 'Salim',
            'last_name' => 'Moussa',
            'client_type' => 'individual',
            'cin' => 'D998877',
            'phone' => '0611223344',
            'email' => 'salim@example.com',
        ])->assertStatus(201);

        $clientId = $clientResponse->json('data.id');

        // Create generic Company & Product for Contract
        $compagnie = \App\Models\Compagnie::create([
            'nom' => 'Wafa Assurance',
            'code' => 'WAFA',
            'is_active' => true,
        ]);
        $product = \App\Models\Product::create([
            'nom' => 'Automobile',
            'code' => 'AUTO_API',
            'branche' => 'AUTO',
        ]);

        // Create polymorphic Contract via API
        $this->postJson('http://crmtest.localhost/api/v1/contracts', [
            'client_id' => $clientId,
            'insurance_company_id' => $compagnie->id,
            'insurance_type_id' => $product->id,
            'contract_number' => 'CON-API-1122',
            'policy_number' => 'POL-API-1122',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addYear()->toDateString(),
            'premium_amount' => 5000.00,
            'commission_amount' => 750.00,
            'payment_status' => 'pending',
            'status' => 'active',
            'details_type' => 'auto',
            'details' => [
                'marque' => 'Dacia',
                'matricule' => '12345-A-6',
                'puissance_fiscale' => 6,
                'nb_places' => 5,
                'carburant' => 'Diesel',
            ]
        ])->assertStatus(201);

        $contract = Contract::where('contract_number', 'CON-API-1122')->first();
        $this->assertNotNull($contract);
        $this->assertEquals(AutoContractDetail::class, $contract->details_type);
        $this->assertEquals('Dacia', $contract->details->marque);
    }
}
