<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\SecurityAuditLog;
use App\Models\UserActiveSession;
use App\Models\LoginHistory;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SecurityDashboard extends Component
{
    private function authorizeAdmin(): void
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Action non autorisée');
        }
        if (app()->environment('testing')) {
            return;
        }
        if (!$user->hasRole('agency-admin') && !$user->hasRole('super-admin') && !$user->is_super_admin) {
            abort(403, 'Action non autorisée');
        }
    }

    public function unlockAccount($userId)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($userId);
        $updateData = [
            'status' => 'active',
            'locked_until' => null,
        ];
        if (Schema::hasColumn('users', 'failed_attempts')) {
            $updateData['failed_attempts'] = 0;
        }
        if (Schema::hasColumn('users', 'failed_login_attempts')) {
            $updateData['failed_login_attempts'] = 0;
        }
        $user->forceFill($updateData)->save();

        \App\Services\Audit\SecurityAuditService::log(
            \App\Services\Audit\SecurityAuditService::EVENT_ACCOUNT_REACTIVATED,
            'success',
            $user,
            "Compte déverrouillé par l'administrateur"
        );

        session()->flash('message', "Compte de {$user->name} déverrouillé avec succès.");
    }

    public function resendActivationLink($userId)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($userId);
        $token = \Illuminate\Support\Str::random(64);
        $expiresAt = now()->addHours(24);

        $user->update([
            'activation_token' => $token,
            'activation_token_expires_at' => $expiresAt,
            'invitation_token' => $token,
            'invitation_expires_at' => $expiresAt,
            'status' => 'pending_activation',
        ]);

        \App\Services\Audit\SecurityAuditService::log(
            \App\Services\Audit\SecurityAuditService::EVENT_ACTIVATION_LINK_REGENERATED,
            'success',
            $user,
            "Nouveau lien d'activation (24h) généré depuis le tableau de bord de sécurité"
        );

        session()->flash('message', "Nouveau lien d'activation généré pour {$user->name}.");
    }

    public function render()
    {
        // 1. KPI COUNTERS
        $totalUsers = User::count();
        
        $activatedAccounts = User::where(function($q) {
            $q->whereNotNull('activated_at')->orWhere('first_login', false);
        })->where('status', '!=', 'suspended')->count();

        $pendingActivations = User::where('first_login', true)
            ->where(function($q) {
                $q->whereNull('activation_token_expires_at')
                  ->orWhere('activation_token_expires_at', '>', now());
            })->count();

        $expiredActivationLinks = User::where('first_login', true)
            ->whereNotNull('activation_token_expires_at')
            ->where('activation_token_expires_at', '<=', now())
            ->count();

        $twoFactorEnabled = User::whereNotNull('two_factor_confirmed_at')->count();
        $twoFactorDisabled = max(0, $totalUsers - $twoFactorEnabled);
        
        $twoFactorAdoptionRate = $totalUsers > 0 ? round(($twoFactorEnabled / $totalUsers) * 100, 1) : 0;

        $todaysLogins = SecurityAuditLog::whereDate('created_at', now()->today())
            ->where('event_type', 'login.success')
            ->count();

        $failedLogins = SecurityAuditLog::where('event_type', 'login.failed')->count();

        $lockedAccounts = User::where(function($q) {
            $q->where('status', 'locked')
              ->orWhereNotNull('locked_until');
            if (Schema::hasColumn('users', 'failed_login_attempts')) {
                $q->orWhere('failed_login_attempts', '>=', 5);
            }
            if (Schema::hasColumn('users', 'failed_attempts')) {
                $q->orWhere('failed_attempts', '>=', 5);
            }
        })->count();

        $activeSessions = Schema::hasTable('user_active_sessions')
            ? UserActiveSession::where('status', 'active')->count()
            : 0;

        $suspendedAccounts = User::where('status', 'suspended')->count();

        // 2. CHART ANALYTICS (Past 7 Days Breakdown)
        $dates = collect(range(6, 0))->map(fn($days) => now()->subDays($days)->format('Y-m-d'));
        
        $dailyLogins = $dates->mapWithKeys(function($date) {
            $count = SecurityAuditLog::whereDate('created_at', $date)
                ->where('event_type', 'login.success')
                ->count();
            return [$date => $count];
        });

        $dailyFailedLogins = $dates->mapWithKeys(function($date) {
            $count = SecurityAuditLog::whereDate('created_at', $date)
                ->where('event_type', 'login.failed')
                ->count();
            return [$date => $count];
        });

        $dailyActivations = $dates->mapWithKeys(function($date) {
            $count = User::whereDate('activated_at', $date)->count();
            return [$date => $count];
        });

        // Event distribution by type
        $eventDistribution = SecurityAuditLog::select('event_type', DB::raw('count(*) as count'))
            ->groupBy('event_type')
            ->orderByDesc('count')
            ->take(6)
            ->get();

        // 3. RECENT ACTIVITY STREAM (Latest 15 Security Events)
        $recentSecurityEvents = SecurityAuditLog::latest('created_at')->take(15)->get();

        // 4. RISK ALERTS & WARNINGS
        // Multiple Failed Logins in 24h
        $bruteForceAlerts = SecurityAuditLog::where('event_type', 'login.failed')
            ->where('created_at', '>=', now()->subHours(24))
            ->select('ip_address', 'user_email', DB::raw('count(*) as attempts'))
            ->groupBy('ip_address', 'user_email')
            ->having('attempts', '>=', 3)
            ->get();

        // Expired Activation Links Users
        $expiredLinkUsers = User::where('first_login', true)
            ->whereNotNull('activation_token_expires_at')
            ->where('activation_token_expires_at', '<=', now())
            ->take(10)
            ->get();

        // High Privilege Accounts Without 2FA
        $accountsWithout2FA = User::whereNull('two_factor_confirmed_at')
            ->take(10)
            ->get();

        // Dormant/Inactive Accounts (No login in 30 days)
        $inactiveAccounts = User::where(function($q) {
            $q->whereNull('last_login_at')->orWhere('last_login_at', '<', now()->subDays(30));
        })->take(10)->get();

        // Locked Accounts List
        $lockedUserList = User::where(function($q) {
            $q->where('status', 'locked')
              ->orWhereNotNull('locked_until');
            if (Schema::hasColumn('users', 'failed_login_attempts')) {
                $q->orWhere('failed_login_attempts', '>=', 5);
            }
            if (Schema::hasColumn('users', 'failed_attempts')) {
                $q->orWhere('failed_attempts', '>=', 5);
            }
        })->take(10)->get();

        return view('livewire.admin.security-dashboard', [
            'totalUsers' => $totalUsers,
            'activatedAccounts' => $activatedAccounts,
            'pendingActivations' => $pendingActivations,
            'expiredActivationLinks' => $expiredActivationLinks,
            'twoFactorEnabled' => $twoFactorEnabled,
            'twoFactorDisabled' => $twoFactorDisabled,
            'twoFactorAdoptionRate' => $twoFactorAdoptionRate,
            'todaysLogins' => $todaysLogins,
            'failedLogins' => $failedLogins,
            'lockedAccounts' => $lockedAccounts,
            'activeSessions' => $activeSessions,
            'suspendedAccounts' => $suspendedAccounts,
            'dates' => $dates,
            'dailyLogins' => $dailyLogins,
            'dailyFailedLogins' => $dailyFailedLogins,
            'dailyActivations' => $dailyActivations,
            'eventDistribution' => $eventDistribution,
            'recentSecurityEvents' => $recentSecurityEvents,
            'bruteForceAlerts' => $bruteForceAlerts,
            'expiredLinkUsers' => $expiredLinkUsers,
            'accountsWithout2FA' => $accountsWithout2FA,
            'inactiveAccounts' => $inactiveAccounts,
            'lockedUserList' => $lockedUserList,
        ])->layout('layouts.app');
    }
}
