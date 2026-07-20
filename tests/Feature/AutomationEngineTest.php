<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Contract;
use App\Models\Client;
use App\Models\AutomationRule;
use App\Models\Task;
use App\Models\Communication;
use App\Events\ContractExpiringEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AutomationEngineTest extends TestCase
{
    use RefreshDatabase;

    private $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up left-over tenant DBs
        foreach (glob(database_path('*tenant*')) as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        $this->tenant = Tenant::create([
            'id' => 'agencytest',
            'name' => 'Agency Test Corp',
        ]);
        
        tenancy()->initialize($this->tenant);
    }

    protected function tearDown(): void
    {
        tenancy()->end();
        parent::tearDown();
    }

    public function test_automation_rule_fires_and_creates_task_and_whatsapp_log()
    {
        // 1. Fetch or create dependencies
        $compagnie = \App\Models\Compagnie::firstOrCreate(
            ['id' => 1],
            ['nom' => 'AXA Assurance', 'statut' => 'active']
        );

        $product = \App\Models\Product::where('code', 'AUTO')->first();
        if (!$product) {
            $product = \App\Models\Product::create([
                'id' => 1,
                'code' => 'AUTO',
                'nom' => 'Automobile',
            ]);
        }

        // 2. Create client & contract
        $client = Client::create([
            'first_name' => 'Salim',
            'last_name' => 'Moussa',
            'email' => 'salim@moussa.com',
            'phone' => '+212600000000',
        ]);

        $contract = Contract::create([
            'contract_number' => 'TEST-AUTO-999',
            'policy_number' => 'TEST-AUTO-999',
            'client_id' => $client->id,
            'premium_amount' => 5000.00,
            'commission_amount' => 500.00,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'active',
            'compagnie_id' => $compagnie->id,
            'insurance_type_id' => $product->id,
            'insurer_id' => 1,
            'insurance_product_id' => 1,
            'commission_rate' => 10.0,
        ]);

        // 3. Create automation rule
        AutomationRule::create([
            'name' => 'Relance Standard 30 Jours',
            'event' => 'contract.expiring',
            'conditions' => ['days_before_expiry' => 30],
            'actions' => [
                ['type' => 'whatsapp'],
                ['type' => 'task']
            ],
            'is_active' => true,
        ]);

        // 4. Trigger Event
        event(new ContractExpiringEvent($contract, 30));

        // 5. Assertions
        // Assert Task was created
        $this->assertTrue(
            Task::where('client_id', $client->id)
                ->where('contract_id', $contract->id)
                ->exists()
        );

        // Assert WhatsApp communication log exists
        $this->assertTrue(
            Communication::where('client_id', $client->id)
                ->where('type', 'whatsapp')
                ->exists()
        );
    }
}
