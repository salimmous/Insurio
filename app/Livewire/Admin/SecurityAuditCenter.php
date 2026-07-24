<?php

namespace App\Livewire\Admin;

use App\Models\SecurityAuditLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SecurityAuditCenter extends Component
{
    use WithPagination;

    public string $search = '';
    public string $agencyFilter = '';
    public string $branchFilter = '';
    public string $userFilter = '';
    public string $roleFilter = '';
    public string $eventTypeFilter = '';
    public string $statusFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $ipFilter = '';
    public string $activeView = 'ledger'; // 'ledger' or 'timeline'

    protected $queryString = [
        'search' => ['except' => ''],
        'agencyFilter' => ['except' => ''],
        'branchFilter' => ['except' => ''],
        'userFilter' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'eventTypeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'ipFilter' => ['except' => ''],
        'activeView' => ['except' => 'ledger'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'agencyFilter',
            'branchFilter',
            'userFilter',
            'roleFilter',
            'eventTypeFilter',
            'statusFilter',
            'dateFrom',
            'dateTo',
            'ipFilter',
        ]);
        $this->resetPage();
    }

    public function exportCsv()
    {
        $query = $this->buildQuery();
        $logs = $query->take(5000)->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="security_audit_export_' . date('Y-m-d_H-i') . '.csv"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'UUID',
                'Date & Time',
                'User Name',
                'User Email',
                'Agency',
                'Branch',
                'Role',
                'IP Address',
                'Browser',
                'OS',
                'Device',
                'Event Type',
                'Status',
                'Notes',
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->uuid,
                    $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '',
                    $log->user_name,
                    $log->user_email,
                    $log->agency_name,
                    $log->branch_name,
                    $log->role_name,
                    $log->ip_address,
                    $log->browser,
                    $log->os,
                    $log->device,
                    $log->event_type,
                    $log->status,
                    $log->notes,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function buildQuery()
    {
        $query = SecurityAuditLog::query()->latest('created_at');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('uuid', 'like', "%{$this->search}%")
                  ->orWhere('user_name', 'like', "%{$this->search}%")
                  ->orWhere('user_email', 'like', "%{$this->search}%")
                  ->orWhere('ip_address', 'like', "%{$this->search}%")
                  ->orWhere('notes', 'like', "%{$this->search}%");
            });
        }

        if ($this->agencyFilter) {
            $query->where('agency_name', 'like', "%{$this->agencyFilter}%");
        }

        if ($this->branchFilter) {
            $query->where('branch_name', 'like', "%{$this->branchFilter}%");
        }

        if ($this->userFilter) {
            $query->where('user_id', $this->userFilter);
        }

        if ($this->roleFilter) {
            $query->where('role_name', 'like', "%{$this->roleFilter}%");
        }

        if ($this->eventTypeFilter) {
            $query->where('event_type', $this->eventTypeFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->ipFilter) {
            $query->where('ip_address', 'like', "%{$this->ipFilter}%");
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query;
    }

    public function render()
    {
        $logsQuery = $this->buildQuery();

        // Calculate Overview Statistics
        $totalEvents = SecurityAuditLog::count();
        $successLogins = SecurityAuditLog::whereIn('event_type', ['login.success', 'account.activated'])->count();
        $failedAttempts = SecurityAuditLog::whereIn('status', ['failed', 'critical'])->count();
        $criticalAlerts = SecurityAuditLog::whereIn('event_type', ['role.changed', 'account.suspended', 'account.deleted', '2fa.disabled'])->count();

        $logs = $logsQuery->paginate(25);

        // Fetch unique dropdown filter lists
        $eventTypes = SecurityAuditLog::select('event_type')->distinct()->pluck('event_type');
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $roles = SecurityAuditLog::select('role_name')->whereNotNull('role_name')->distinct()->pluck('role_name');
        $agencies = SecurityAuditLog::select('agency_name')->whereNotNull('agency_name')->distinct()->pluck('agency_name');
        $branches = SecurityAuditLog::select('branch_name')->whereNotNull('branch_name')->distinct()->pluck('branch_name');

        return view('livewire.admin.security-audit-center', [
            'logs' => $logs,
            'totalEvents' => $totalEvents,
            'successLogins' => $successLogins,
            'failedAttempts' => $failedAttempts,
            'criticalAlerts' => $criticalAlerts,
            'eventTypes' => $eventTypes,
            'users' => $users,
            'roles' => $roles,
            'agencies' => $agencies,
            'branches' => $branches,
        ])->layout('layouts.app');
    }
}
