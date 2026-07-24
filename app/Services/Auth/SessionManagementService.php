<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserActiveSession;
use App\Models\LoginHistory;
use App\Services\Audit\SecurityAuditService;
use Illuminate\Support\Facades\DB;

class SessionManagementService
{
    /**
     * Register or update active session entry on request
     */
    public function registerOrUpdateSession(User $user, ?string $sessionId = null): UserActiveSession
    {
        $request = request();
        $sessionId = $sessionId ?: session()->getId();

        $userAgent = $request->userAgent() ?: 'Unknown';
        [$browser, $os, $device] = SecurityAuditService::parseUserAgent($userAgent);

        $agencyName = (function_exists('tenant') && tenant() && tenant('name')) 
            ? tenant('name') 
            : (\App\Models\Setting::get('agency_name') ?: 'Insurio Enterprise SaaS');

        $branchName = $user->branch?->nom ?? 'Siège Agence';
        $roleName = $user->roles->first()?->name ?? 'Membre';

        return UserActiveSession::updateOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'agency_name' => $agencyName,
                'branch_name' => $branchName,
                'role_name' => $roleName,
                'ip_address' => $request->ip() ?: '127.0.0.1',
                'user_agent' => $userAgent,
                'browser' => $browser,
                'os' => $os,
                'device' => $device,
                'country' => 'Maroc',
                'city' => 'Casablanca',
                'status' => 'active',
                'last_activity_at' => now(),
            ]
        );
    }

    /**
     * Get all active sessions for a specific user
     */
    public function getActiveSessionsForUser(User $user)
    {
        return UserActiveSession::where('user_id', $user->id)
            ->where('status', 'active')
            ->orderByDesc('last_activity_at')
            ->get();
    }

    /**
     * Get all system-wide active sessions (for Super Admin)
     */
    public function getAllActiveSessions()
    {
        return UserActiveSession::where('status', 'active')
            ->orderByDesc('last_activity_at')
            ->get();
    }

    /**
     * Terminate a single session by Session ID
     */
    public function terminateSession(string $sessionId, ?string $revokedBy = null): bool
    {
        $session = UserActiveSession::where('session_id', $sessionId)->first();

        if (!$session) {
            return false;
        }

        $session->update(['status' => 'revoked']);

        // Also delete from Laravel framework sessions table if present
        try {
            DB::table('sessions')->where('id', $sessionId)->delete();
        } catch (\Throwable $e) {
            // Non-critical if sessions table isn't used
        }

        // Write immutable audit log
        $user = User::find($session->user_id);
        SecurityAuditService::log(
            SecurityAuditService::EVENT_SESSION_REVOKED,
            'warning',
            $user,
            "Session ID {$sessionId} révoquée par " . ($revokedBy ?: 'l\'utilisateur/administrateur'),
            ['session_id' => $sessionId, 'ip' => $session->ip_address, 'browser' => $session->browser]
        );

        return true;
    }

    /**
     * Terminate all other sessions for a user EXCEPT the current session
     */
    public function terminateOtherSessions(User $user, string $currentSessionId, ?string $revokedBy = null): int
    {
        $sessions = UserActiveSession::where('user_id', $user->id)
            ->where('session_id', '!=', $currentSessionId)
            ->where('status', 'active')
            ->get();

        $count = 0;
        foreach ($sessions as $session) {
            $session->update(['status' => 'revoked']);
            try {
                DB::table('sessions')->where('id', $session->session_id)->delete();
            } catch (\Throwable $e) {}
            $count++;
        }

        SecurityAuditService::log(
            SecurityAuditService::EVENT_SESSION_REVOKED,
            'warning',
            $user,
            "Révocation de {$count} autre(s) session(s) active(s) par " . ($revokedBy ?: $user->name)
        );

        return $count;
    }

    /**
     * Terminate ALL sessions for a user (Force logout user everywhere)
     */
    public function terminateAllUserSessions(User $user, ?string $revokedBy = null): int
    {
        $sessions = UserActiveSession::where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        $count = 0;
        foreach ($sessions as $session) {
            $session->update(['status' => 'revoked']);
            try {
                DB::table('sessions')->where('id', $session->session_id)->delete();
            } catch (\Throwable $e) {}
            $count++;
        }

        SecurityAuditService::log(
            SecurityAuditService::EVENT_SESSION_REVOKED,
            'warning',
            $user,
            "Révocation complète et déconnexion forcée de TOUTES les sessions pour {$user->name} par " . ($revokedBy ?: 'Administrateur')
        );

        return $count;
    }

    /**
     * Terminate ALL active sessions system-wide (Super Admin Emergency)
     */
    public function terminateSystemWideSessions(?string $revokedBy = null): int
    {
        $sessions = UserActiveSession::where('status', 'active')->get();
        $count = 0;

        foreach ($sessions as $session) {
            $session->update(['status' => 'revoked']);
            try {
                DB::table('sessions')->where('id', $session->session_id)->delete();
            } catch (\Throwable $e) {}
            $count++;
        }

        SecurityAuditService::log(
            SecurityAuditService::EVENT_SESSION_REVOKED,
            'critical',
            auth()->user(),
            "RÉVOCATION D'URGENCE SYSTÈME: {$count} sessions actives interrompues par " . ($revokedBy ?: 'Super Admin')
        );

        return $count;
    }

    /**
     * Get Last Successful Login for User
     */
    public function getLastSuccessfulLogin(User $user): ?LoginHistory
    {
        return LoginHistory::where('user_id', $user->id)
            ->where('status', 'success')
            ->latest('created_at')
            ->first();
    }

    /**
     * Get Last Failed Login for User
     */
    public function getLastFailedLogin(User $user): ?LoginHistory
    {
        return LoginHistory::where('user_id', $user->id)
            ->where('status', 'failed')
            ->latest('created_at')
            ->first();
    }
}
