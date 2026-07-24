<?php

namespace Tests\Feature;

use App\Models\SecurityAuditLog;
use App\Models\User;
use App\Services\Audit\SecurityAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SecurityAuditCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_audit_service_creates_immutable_log(): void
    {
        $user = User::factory()->create(['name' => 'John Security', 'email' => 'john@insurio.ma']);

        $log = SecurityAuditService::log(
            SecurityAuditService::EVENT_ACCOUNT_CREATED,
            'success',
            $user,
            "Compte créé avec succès pour John Security"
        );

        $this->assertDatabaseHas('security_audit_logs', [
            'uuid' => $log->uuid,
            'event_type' => 'account.created',
            'user_name' => 'John Security',
            'user_email' => 'john@insurio.ma',
            'status' => 'success',
        ]);
    }

    public function test_security_audit_log_is_strictly_immutable(): void
    {
        $user = User::factory()->create();
        $log = SecurityAuditService::log(SecurityAuditService::EVENT_LOGIN_SUCCESS, 'success', $user, 'Login test');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Security audit logs are permanent and cannot be deleted.');

        $log->delete();
    }

    public function test_security_audit_log_prevents_updating(): void
    {
        $user = User::factory()->create();
        $log = SecurityAuditService::log(SecurityAuditService::EVENT_LOGIN_SUCCESS, 'success', $user, 'Login test');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Security audit logs are immutable and cannot be updated.');

        $log->update(['notes' => 'Tampered notes']);
    }

    public function test_security_audit_center_component_renders_and_filters(): void
    {
        $user = User::factory()->create(['first_login' => false, 'activated_at' => now()]);
        SecurityAuditService::log(SecurityAuditService::EVENT_2FA_ENABLED, 'success', $user, '2FA Enabled Note');
        SecurityAuditService::log(SecurityAuditService::EVENT_ACCOUNT_SUSPENDED, 'warning', $user, 'Account Suspended Note');

        $this->actingAs($user);
        session(['two_factor_verified' => true]);

        Livewire::test(\App\Livewire\Admin\SecurityAuditCenter::class)
            ->assertSee('Centre')
            ->assertSee('Audit de')
            ->assertSee('2FA Enabled Note')
            ->assertSee('Account Suspended Note')
            ->set('statusFilter', 'warning')
            ->assertSee('Account Suspended Note')
            ->assertDontSee('2FA Enabled Note');
    }

    public function test_pdf_export_and_csv_export_endpoint(): void
    {
        $user = User::factory()->create(['first_login' => false, 'activated_at' => now()]);
        SecurityAuditService::log(SecurityAuditService::EVENT_ROLE_CHANGED, 'warning', $user, 'Role changed to Super Admin');

        $this->actingAs($user);
        session(['two_factor_verified' => true]);

        $response = $this->get(route('admin.security-audit.pdf'));
        $response->assertStatus(200);

        Livewire::test(\App\Livewire\Admin\SecurityAuditCenter::class)
            ->call('exportCsv')
            ->assertFileDownloaded();
    }
}
