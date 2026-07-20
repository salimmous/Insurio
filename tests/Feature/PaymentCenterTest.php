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
use App\Models\Payment;
use App\Models\PaymentInstallment;
use App\Models\BankReconciliation;
use App\Models\PaymentFollowUp;
use App\Models\Dossier;
use App\Models\Task;
use App\Models\Tenant;
use App\Models\Communication;
use Livewire\Livewire;
use App\Livewire\Admin\PaymentCenter;
use App\Livewire\Admin\PaymentWorkspace;
use Spatie\Permission\Models\Role;

class PaymentCenterTest extends TestCase
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
            'id' => 'paymenttest',
            'name' => 'Payment Test Agency',
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
            'phone' => '0612345678',
            'email' => 'tariq@test.com',
            'succursale_id' => $this->branch->id,
            'solvabilite' => 'A',
            'incident' => false,
        ]);

        $this->compagnie = Compagnie::create([
            'nom' => 'AXA Assurance',
            'code' => 'AXA',
            'is_active' => true,
        ]);

        $this->contract = Contract::create([
            'client_id' => $this->client->id,
            'insurance_company_id' => $this->compagnie->id,
            'contract_number' => 'CT-2026-0001',
            'policy_number' => 'POL-998877',
            'prime_totale' => 5000.00,
            'statut' => 'brouillon',
            'payment_status' => 'unpaid',
            'employe_id' => $this->employee->id,
            'succursale_id' => $this->branch->id,
            'start_date' => '2026-01-01',
            'end_date' => '2027-01-01',
        ]);
    }

    protected function tearDown(): void
    {
        tenancy()->end();
        parent::tearDown();
    }

    public function test_can_access_payment_center_dashboard()
    {
        $this->actingAs($this->admin);

        Livewire::test(PaymentCenter::class)
            ->assertStatus(200)
            ->assertSee('Centre de Règlements')
            ->assertSee('Revenue du Jour');
    }

    public function test_can_create_cash_payment_and_automatically_reconciles()
    {
        $this->actingAs($this->admin);

        Livewire::test(PaymentCenter::class)
            ->set('client_id', $this->client->id)
            ->set('contract_id', $this->contract->id)
            ->set('amount', 5000.00)
            ->set('paid_amount', 5000.00)
            ->set('payment_method', 'cash')
            ->call('createPayment')
            ->assertHasNoErrors();

        $payment = Payment::where('client_id', $this->client->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals('paid', $payment->payment_status);
        $this->assertEquals(0, $payment->remaining_amount);
        $this->assertNotNull($payment->payment_number);
        $this->assertNotNull($payment->uuid);

        // Verify contract is activated and marked paid
        $this->contract->refresh();
        $this->assertEquals('paid', $this->contract->payment_status);
        $this->assertEquals('actif', $this->contract->statut);

        // Verify legacy Reglement table has been synchronized
        $this->assertDatabaseHas('reglements', [
            'contrat_id' => $this->contract->id,
            'montant' => 5000.00,
            'reference_paiement' => $payment->payment_number,
        ]);
    }

    public function test_can_reconcile_bank_deposits()
    {
        $this->actingAs($this->admin);

        $payment = Payment::create([
            'client_id' => $this->client->id,
            'contract_id' => $this->contract->id,
            'amount' => 3000.00,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        Livewire::test(PaymentCenter::class)
            ->set('reconcile_payment_id', $payment->id)
            ->set('reconcile_ref', 'TXN-998811')
            ->set('reconcile_amount', 3000.00)
            ->call('createReconciliation')
            ->assertHasNoErrors();

        $payment->refresh();
        $this->assertEquals('deposited', $payment->payment_status);

        $this->assertDatabaseHas('bank_reconciliations', [
            'payment_id' => $payment->id,
            'reference' => 'TXN-998811',
            'difference' => 0.00,
            'matched' => true,
        ]);
    }

    public function test_returned_cheque_workflow_triggers_followups_and_incident_dossier()
    {
        $this->actingAs($this->admin);

        $payment = Payment::create([
            'client_id' => $this->client->id,
            'contract_id' => $this->contract->id,
            'amount' => 4500.00,
            'payment_method' => 'cheque',
            'payment_status' => 'deposited',
            'cheque_number' => 'CHQ-777666',
            'bank_name' => 'Attijariwafa Bank',
        ]);

        Livewire::test(PaymentWorkspace::class, ['id' => $payment->id])
            ->set('rejection_reason', 'compte_bloque')
            ->call('recordChequeReturned')
            ->assertHasNoErrors();

        $payment->refresh();
        $this->assertEquals('returned', $payment->payment_status);

        // Verify collection Task is created
        $this->assertDatabaseHas('tasks', [
            'client_id' => $this->client->id,
            'contract_id' => $this->contract->id,
            'priority' => 'high',
        ]);

        // Verify Dossier of type payment_issue is created
        $this->assertDatabaseHas('dossiers', [
            'client_id' => $this->client->id,
            'contract_id' => $this->contract->id,
            'type' => 'payment_issue',
        ]);
    }

    public function test_overdue_command_scans_and_flags_overdue_payments()
    {
        // Create an overdue payment
        $payment = Payment::create([
            'client_id' => $this->client->id,
            'contract_id' => $this->contract->id,
            'amount' => 1500.00,
            'payment_method' => 'credit_card',
            'payment_status' => 'pending',
            'due_date' => now()->subDays(5),
        ]);

        // Run artisan command
        $this->artisan('payments:check-overdue')
            ->expectsOutput('Starting overdue payments scan...')
            ->expectsOutput("Payment {$payment->payment_number} flagged as OVERDUE.")
            ->expectsOutput("Scan completed. 1 payments marked as overdue.")
            ->assertExitCode(0);

        $payment->refresh();
        $this->assertEquals('overdue', $payment->payment_status);

        // Verify Follow-up is generated
        $this->assertDatabaseHas('payment_follow_ups', [
            'payment_id' => $payment->id,
            'priority' => 'high',
        ]);
    }
}
