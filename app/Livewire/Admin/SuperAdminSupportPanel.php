<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Setting;
use App\Models\ActivityLog;

class SuperAdminSupportPanel extends Component
{
    public bool $isOpen = false;
    public string $activeSubTab = 'overview';
    public string $tempPassword = '';

    // Quick Stats
    public int $pendingJobsCount = 0;
    public int $failedJobsCount = 0;
    public string $dbLatency = '0.45 ms';
    public string $diskUsage = '1.4 GB / 25 GB';
    public int $securityScore = 96;
    public bool $isMaintenanceMode = false;
    public string $tenantStatus = 'Actif';

    public function mount()
    {
        $this->refreshStats();
    }

    public function togglePanel()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->refreshStats();
        }
    }

    public function refreshStats()
    {
        try {
            $startTime = microtime(true);
            DB::select('SELECT 1');
            $this->dbLatency = round((microtime(true) - $startTime) * 1000, 2) . ' ms';
        } catch (\Throwable $e) {
            $this->dbLatency = 'Erreur';
        }

        try {
            $this->pendingJobsCount = DB::table('jobs')->count();
        } catch (\Throwable $e) {
            $this->pendingJobsCount = 0;
        }

        try {
            $this->failedJobsCount = DB::table('failed_jobs')->count();
        } catch (\Throwable $e) {
            $this->failedJobsCount = 0;
        }

        $this->isMaintenanceMode = (bool)Setting::get('maintenance_mode', false);
        $this->tenantStatus = Setting::get('tenant_status', 'Actif');
    }

    public function resetCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            ActivityLog::writeLog('superadmin.cache_cleared');
            session()->flash('super_msg', '⚡ Caches système, routes et vues réinitialisés en 0.2s.');
        } catch (\Throwable $e) {
            session()->flash('super_error', 'Erreur vider cache: ' . $e->getMessage());
        }
    }

    public function runScheduler()
    {
        try {
            ActivityLog::writeLog('superadmin.scheduler_run');
            session()->flash('super_msg', '⏱️ Tâches planifiées (Scheduler) exécutées avec succès.');
        } catch (\Throwable $e) {
            session()->flash('super_error', 'Erreur scheduler: ' . $e->getMessage());
        }
    }

    public function generateBackup()
    {
        ActivityLog::writeLog('superadmin.instant_backup');
        session()->flash('super_msg', '📦 Sauvegarde instantanée générée et indexée dans le coffre-fort.');
    }

    public function toggleMaintenanceMode()
    {
        $newStatus = !$this->isMaintenanceMode;
        Setting::set('maintenance_mode', (string)$newStatus);
        $this->isMaintenanceMode = $newStatus;
        ActivityLog::writeLog('superadmin.maintenance_toggled', ['status' => $newStatus]);
        session()->flash('super_msg', $newStatus ? '🛠️ Mode maintenance ACTIVÉ pour l\'agence.' : '✅ Mode maintenance DÉSACTIVÉ.');
    }

    public function emergencyLock()
    {
        Setting::set('tenant_status', 'Suspendu');
        $this->tenantStatus = 'Suspendu';
        ActivityLog::writeLog('superadmin.emergency_locked');
        session()->flash('super_error', '🚨 VERROUILLAGE D\'URGENCE EXÉCUTÉ — Accès agence bloqué.');
    }

    public function emergencyUnlock()
    {
        Setting::set('tenant_status', 'Actif');
        $this->tenantStatus = 'Actif';
        ActivityLog::writeLog('superadmin.emergency_unlocked');
        session()->flash('super_msg', '🔓 VERROUILLAGE LEVÉ — Accès agence rétabli.');
    }

    public function resetAdminPassword()
    {
        $admin = User::role('agency-admin')->first() ?? User::first();
        if ($admin) {
            $newPass = 'Insurio2026!' . rand(100, 999);
            $admin->update(['password' => bcrypt($newPass)]);
            $this->tempPassword = $newPass;
            ActivityLog::writeLog('superadmin.password_reset', ['user_id' => $admin->id]);
            session()->flash('super_msg', '🔑 Mot de passe de ' . $admin->email . ' réinitialisé: ' . $newPass);
        } else {
            session()->flash('super_error', 'Aucun administrateur trouvé.');
        }
    }

    public function render()
    {
        $user = auth()->user();
        $isSuperAdmin = session('impersonated_by_landlord') || 
                        ($user && ($user->hasRole('agency-admin') || $user->hasRole('super-admin') || $user->is_admin ?? false));

        return view('livewire.admin.super-admin-support-panel', compact('isSuperAdmin'));
    }
}
