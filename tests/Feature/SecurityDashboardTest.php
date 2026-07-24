<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SecurityAuditLog;
use App\Services\Audit\SecurityAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SecurityDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_dashboard_renders_with_kpi_cards_and_analytics(): void
    {
        $user = User::factory()->create(['first_login' => false, 'activated_at' => now()]);
        SecurityAuditService::log(SecurityAuditService::EVENT_LOGIN_SUCCESS, 'success', $user, 'Logged in');
        SecurityAuditService::log(SecurityAuditService::EVENT_2FA_ENABLED, 'success', $user, '2FA Enabled');

        $this->actingAs($user);
        session(['two_factor_verified' => true]);

        Livewire::test(\App\Livewire\Admin\SecurityDashboard::class)
            ->assertSee('Tableau de Bord de Sécurité Enterprise')
            ->assertSee('Total Utilisateurs')
            ->assertSee('Comptes Activés')
            ->assertSee('2FA Activé')
            ->assertSee('Logins du Jour');
    }

    public function test_admin_can_unlock_locked_account_from_dashboard(): void
    {
        $admin = User::factory()->create(['first_login' => false, 'activated_at' => now()]);
        $lockedUser = User::factory()->create([
            'status' => 'locked',
            'failed_attempts' => 5,
        ]);

        $this->actingAs($admin);
        session(['two_factor_verified' => true]);

        Livewire::test(\App\Livewire\Admin\SecurityDashboard::class)
            ->call('unlockAccount', $lockedUser->id);

        $this->assertDatabaseHas('users', [
            'id' => $lockedUser->id,
            'status' => 'active',
            'failed_attempts' => 0,
        ]);

        $this->assertDatabaseHas('security_audit_logs', [
            'event_type' => 'account.reactivated',
        ]);
    }

    public function test_admin_can_resend_expired_activation_link(): void
    {
        $admin = User::factory()->create(['first_login' => false, 'activated_at' => now()]);
        $pendingUser = User::factory()->create([
            'first_login' => true,
            'activation_token_expires_at' => now()->subHours(2),
        ]);

        $this->actingAs($admin);
        session(['two_factor_verified' => true]);

        Livewire::test(\App\Livewire\Admin\SecurityDashboard::class)
            ->call('resendActivationLink', $pendingUser->id);

        $pendingUser->refresh();
        $this->assertTrue($pendingUser->activation_token_expires_at->isFuture());

        $this->assertDatabaseHas('security_audit_logs', [
            'event_type' => 'activation_link.regenerated',
            'user_id' => $pendingUser->id,
        ]);
    }
}
