<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserActiveSession;
use App\Models\SecurityAuditLog;
use App\Services\Auth\SessionManagementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EnterpriseSessionManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_management_service_registers_and_tracks_active_sessions(): void
    {
        $user = User::factory()->create(['name' => 'Alice Session', 'email' => 'alice@insurio.ma']);
        $service = app(SessionManagementService::class);

        $session = $service->registerOrUpdateSession($user, 'sess-12345');

        $this->assertDatabaseHas('user_active_sessions', [
            'session_id' => 'sess-12345',
            'user_id' => $user->id,
            'user_email' => 'alice@insurio.ma',
            'status' => 'active',
        ]);
    }

    public function test_user_can_terminate_other_sessions(): void
    {
        $user = User::factory()->create(['first_login' => false, 'activated_at' => now()]);
        $this->actingAs($user);
        session(['two_factor_verified' => true]);

        $currentSessionId = session()->getId();

        $service = app(SessionManagementService::class);
        $service->registerOrUpdateSession($user, $currentSessionId);
        $service->registerOrUpdateSession($user, 'sess-other-1');
        $service->registerOrUpdateSession($user, 'sess-other-2');

        Livewire::test(\App\Livewire\Admin\SecuritySettings::class)
            ->set('activeTab', 'sessions')
            ->call('logoutOtherDevices');

        $this->assertDatabaseHas('user_active_sessions', [
            'session_id' => $currentSessionId,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('user_active_sessions', [
            'session_id' => 'sess-other-1',
            'status' => 'revoked',
        ]);

        $this->assertDatabaseHas('security_audit_logs', [
            'event_type' => 'session.revoked',
        ]);
    }

    public function test_super_admin_can_force_logout_user(): void
    {
        $admin = User::factory()->create(['first_login' => false, 'activated_at' => now()]);
        $targetUser = User::factory()->create(['first_login' => false, 'activated_at' => now()]);

        $service = app(SessionManagementService::class);
        $service->registerOrUpdateSession($targetUser, 'target-sess-1');
        $service->registerOrUpdateSession($targetUser, 'target-sess-2');

        $this->actingAs($admin);
        session(['two_factor_verified' => true]);

        Livewire::test(\App\Livewire\Admin\SecuritySettings::class)
            ->call('forceLogoutUserAdmin', $targetUser->id);

        $this->assertDatabaseHas('user_active_sessions', [
            'session_id' => 'target-sess-1',
            'status' => 'revoked',
        ]);

        $this->assertDatabaseHas('security_audit_logs', [
            'event_type' => 'session.revoked',
            'user_id' => $targetUser->id,
        ]);
    }

    public function test_middleware_redirects_revoked_session(): void
    {
        $user = User::factory()->create(['first_login' => false, 'activated_at' => now()]);
        $this->actingAs($user);

        $service = app(SessionManagementService::class);
        $sessionModel = $service->registerOrUpdateSession($user);

        // Revoke current session
        $service->terminateSession($sessionModel->session_id, 'Admin');

        $middleware = new \App\Http\Middleware\TrackActiveSession();
        $request = \Illuminate\Http\Request::create('/dashboard', 'GET');
        $request->setLaravelSession(session()->driver());

        $response = $middleware->handle($request, function () {
            return response('OK');
        });

        $this->assertEquals(302, $response->getStatusCode());
    }
}
