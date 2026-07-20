<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Landlord\PlatformAdmin;
use App\Models\Tenant;

class PlatformTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up any left-over tenant SQLite database files
        foreach (glob(database_path('*tenant*')) as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        // Create platform admin
        $this->admin = PlatformAdmin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@insurio.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_super_admin_can_render_login_page()
    {
        $response = $this->get(route('platform.login'));
        $response->assertStatus(200);
    }

    public function test_super_admin_can_login_with_correct_credentials()
    {
        $response = $this->post(route('platform.login.submit'), [
            'email' => 'superadmin@insurio.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('platform.dashboard'));
        $this->assertAuthenticatedAs($this->admin, 'platform');
    }

    public function test_super_admin_cannot_login_with_incorrect_credentials()
    {
        $response = $this->post(route('platform.login.submit'), [
            'email' => 'superadmin@insurio.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('platform');
    }

    public function test_super_admin_can_render_dashboard()
    {
        $response = $this->actingAs($this->admin, 'platform')->get(route('platform.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Console de Supervision Centrale');
    }

    public function test_super_admin_can_render_onboarding_form()
    {
        $response = $this->actingAs($this->admin, 'platform')->get(route('platform.tenants.create'));
        $response->assertStatus(200);
        $response->assertSee('Nouvelle Agence Cliente');
    }

    public function test_super_admin_can_onboard_new_tenant()
    {
        $response = $this->actingAs($this->admin, 'platform')->post(route('platform.tenants.store'), [
            'id' => 'testagence',
            'name' => 'Test Agence',
            'email' => 'testadmin@insurio.com',
            'plan' => 'premium',
        ]);

        $response->assertRedirect(route('platform.dashboard'));
        $this->assertDatabaseHas('tenants', [
            'id' => 'testagence',
            'name' => 'Test Agence',
        ]);
        $this->assertDatabaseHas('domains', [
            'domain' => 'testagence.sc7mosa1422.universe.wf',
        ]);
    }

    public function test_super_admin_can_render_expenses_page()
    {
        $response = $this->actingAs($this->admin, 'platform')->get(route('platform.expenses.index'));
        $response->assertStatus(200);
        $response->assertSee('Registre des Charges / Dépenses');
    }

    public function test_super_admin_can_add_expense()
    {
        $response = $this->actingAs($this->admin, 'platform')->post(route('platform.expenses.store'), [
            'title' => 'Facture Serveur VPS',
            'category' => 'Hébergement',
            'amount' => 125.50,
            'expense_date' => '2026-07-20',
            'description' => 'Facture mensuelle d’hébergement VPS central',
        ]);

        $response->assertRedirect(route('platform.expenses.index'));
        $this->assertDatabaseHas('platform_expenses', [
            'title' => 'Facture Serveur VPS',
            'amount' => 125.50,
            'category' => 'Hébergement',
        ]);
    }

    public function test_super_admin_can_delete_expense()
    {
        $expense = \App\Models\Landlord\PlatformExpense::create([
            'title' => 'Dépense à Supprimer',
            'category' => 'Autre',
            'amount' => 50.00,
            'expense_date' => '2026-07-20',
        ]);

        $response = $this->actingAs($this->admin, 'platform')->delete(route('platform.expenses.destroy', $expense->id));

        $response->assertRedirect(route('platform.expenses.index'));
        $this->assertDatabaseMissing('platform_expenses', [
            'id' => $expense->id,
        ]);
    }

    public function test_super_admin_can_manage_tenant_succursales()
    {
        // Create a tenant
        $tenant = Tenant::create([
            'id' => 'agencetest',
            'name' => 'Agence Test Branch',
        ]);
        $tenant->domains()->create(['domain' => 'agencetest.localhost']);

        // 1. Create Succursale for this tenant via Super Admin with custom domain
        $response = $this->actingAs($this->admin, 'platform')->post(route('platform.tenants.store_succursale', $tenant->id), [
            'nom' => 'Succursale Fes',
            'code_succursale' => 'SUC-FES',
            'ville' => 'Fes',
            'adresse' => 'Avenue Hassan II',
            'telephone' => '0535000000',
            'domain' => 'fessuc.localhost',
        ]);

        $response->assertRedirect(route('platform.tenants.edit', $tenant->id));

        // Verify domain registered centrally
        $this->assertDatabaseHas('domains', [
            'domain' => 'fessuc.localhost',
            'tenant_id' => $tenant->id,
        ]);

        // Switch to tenant context to verify it's in their database
        tenancy()->initialize($tenant);
        $this->assertDatabaseHas('succursales', [
            'code_succursale' => 'SUC-FES',
            'nom' => 'Succursale Fes',
            'domain' => 'fessuc.localhost',
        ]);

        $succursale = \App\Models\Succursale::first();
        tenancy()->end();

        // 2. Delete Succursale via Super Admin
        $response = $this->actingAs($this->admin, 'platform')->delete(route('platform.tenants.destroy_succursale', [$tenant->id, $succursale->id]));

        $response->assertRedirect(route('platform.tenants.edit', $tenant->id));

        // Verify domain deleted centrally
        $this->assertDatabaseMissing('domains', [
            'domain' => 'fessuc.localhost',
        ]);

        tenancy()->initialize($tenant);
        $this->assertDatabaseMissing('succursales', [
            'id' => $succursale->id,
        ]);
        tenancy()->end();
    }
}
