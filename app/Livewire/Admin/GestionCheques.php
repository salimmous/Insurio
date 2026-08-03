<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cheque;
use App\Models\FinancialAuditLog;
use App\Models\BankAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GestionCheques extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $filterStatus = '';
    public $filterBank = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $filterPaymentReceived = ''; // '' = all, 'yes' = dakhlat, 'no' = not yet

    // Bulk selection
    public $selectedIds = [];
    public $selectAll = false;
    public $bulkAction = '';

    // Status Update Modal
    public $showModal = false;
    public $selectedChequeId = null;
    public $selectedChequeNumber = '';
    public $newStatus = 'deposited';
    public $depositDate = '';
    public $notes = '';

    // Bulk Action Modal
    public $showBulkModal = false;
    public $bulkNewStatus = 'deposited';
    public $bulkDepositDate = '';
    public $bulkNotes = '';

    protected $queryString = [
        'search'               => ['except' => ''],
        'filterStatus'         => ['except' => ''],
        'filterBank'           => ['except' => ''],
        'filterPaymentReceived' => ['except' => ''],
        'filterDateFrom'       => ['except' => ''],
        'filterDateTo'         => ['except' => ''],
    ];

    public function mount()
    {
        $this->depositDate     = now()->format('Y-m-d');
        $this->bulkDepositDate = now()->format('Y-m-d');
    }

    // ─── Watchers ───────────────────────────────────────────────────────────────

    public function updatingSearch()           { $this->resetPage(); $this->selectedIds = []; }
    public function updatingFilterStatus()     { $this->resetPage(); $this->selectedIds = []; }
    public function updatingFilterBank()       { $this->resetPage(); $this->selectedIds = []; }
    public function updatingFilterPaymentReceived() { $this->resetPage(); $this->selectedIds = []; }
    public function updatingFilterDateFrom()   { $this->resetPage(); $this->selectedIds = []; }
    public function updatingFilterDateTo()     { $this->resetPage(); $this->selectedIds = []; }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Select all IDs from current page
            $this->selectedIds = $this->buildQuery()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function updatedSelectedIds()
    {
        $this->selectAll = false;
    }

    // ─── Clear / Reset ──────────────────────────────────────────────────────────

    public function clearFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterBank = '';
        $this->filterPaymentReceived = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->selectedIds = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    // ─── Single Status Update Modal ─────────────────────────────────────────────

    public function openStatusModal($chequeId, $targetStatus = 'deposited')
    {
        $cheque = Cheque::findOrFail($chequeId);
        $this->selectedChequeId = $cheque->id;
        $this->selectedChequeNumber = $cheque->cheque_number;
        $this->newStatus = $targetStatus;
        $this->depositDate = $cheque->deposit_date ? $cheque->deposit_date->format('Y-m-d') : now()->format('Y-m-d');
        $this->notes = $cheque->notes ?? '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedChequeId = null;
    }

    public function saveStatusUpdate()
    {
        if (!$this->selectedChequeId) {
            return;
        }

        $cheque = Cheque::findOrFail($this->selectedChequeId);
        $oldStatus = $cheque->status;

        $updateData = [
            'status' => $this->newStatus,
            'notes'  => $this->notes,
        ];

        if ($this->newStatus === 'deposited') {
            $updateData['deposit_date'] = $this->depositDate ?: now()->format('Y-m-d');
        } elseif ($this->newStatus === 'collected') {
            $updateData['collection_date'] = now()->format('Y-m-d');
            if ($this->depositDate) {
                $updateData['deposit_date'] = $this->depositDate;
            }
            $bank = BankAccount::first();
            if ($bank) {
                $bank->increment('current_balance', $cheque->amount);
            }
        }

        $cheque->update($updateData);

        try {
            if (class_exists('\App\Models\FinancialAuditLog')) {
                FinancialAuditLog::create([
                    'user_id'    => auth()->id() ?? 1,
                    'action'     => 'cheque_status_change',
                    'old_values' => ['status' => $oldStatus],
                    'new_values' => ['status' => $this->newStatus, 'deposit_date' => $this->depositDate],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'reason'     => "Mise à jour du chèque #{$cheque->cheque_number} vers {$this->newStatus}",
                ]);
            }
        } catch (\Throwable $e) {
            // Ignore
        }

        $this->closeModal();
        $this->dispatch('swal:success', ['message' => "Le statut du chèque N° {$cheque->cheque_number} a été mis à jour avec succès!"]);
    }

    // ─── Quick Status (single row) ───────────────────────────────────────────────

    public function quickSetStatus($chequeId, $status)
    {
        $cheque = Cheque::findOrFail($chequeId);

        $updateData = ['status' => $status];
        if ($status === 'deposited' && !$cheque->deposit_date) {
            $updateData['deposit_date'] = now()->format('Y-m-d');
        } elseif ($status === 'collected') {
            $updateData['collection_date'] = now()->format('Y-m-d');
            if (!$cheque->deposit_date) {
                $updateData['deposit_date'] = now()->format('Y-m-d');
            }
            $bank = BankAccount::first();
            if ($bank) {
                $bank->increment('current_balance', $cheque->amount);
            }
        }

        $cheque->update($updateData);

        $labels = [
            'deposited' => 'Versé',
            'collected' => 'Encaissé',
            'returned'  => 'Impayé',
            'pending'   => 'En Attente',
        ];
        $lbl = $labels[$status] ?? $status;

        $this->dispatch('swal:success', ['message' => "Chèque N° {$cheque->cheque_number} marqué comme {$lbl}."]);
    }

    public function quickBulkSetStatus($status)
    {
        if (empty($this->selectedIds)) {
            return;
        }

        $cheques = Cheque::whereIn('id', $this->selectedIds)->get();
        $totalCollectedAmount = 0;

        foreach ($cheques as $cheque) {
            $updateData = ['status' => $status];

            if ($status === 'deposited' && !$cheque->deposit_date) {
                $updateData['deposit_date'] = now()->format('Y-m-d');
            } elseif ($status === 'collected') {
                $updateData['collection_date'] = now()->format('Y-m-d');
                if (!$cheque->deposit_date) {
                    $updateData['deposit_date'] = now()->format('Y-m-d');
                }
                $totalCollectedAmount += $cheque->amount;
            }

            $cheque->update($updateData);
        }

        if ($status === 'collected' && $totalCollectedAmount > 0) {
            $bank = BankAccount::first();
            if ($bank) {
                $bank->increment('current_balance', $totalCollectedAmount);
            }
        }

        $count = count($this->selectedIds);
        $this->selectedIds = [];
        $this->selectAll   = false;

        $labels = [
            'deposited' => 'Versés',
            'collected' => 'Encaissés',
            'returned'  => 'Impayés',
            'pending'   => 'En Attente',
        ];
        $lbl = $labels[$status] ?? $status;

        $this->dispatch('swal:success', ['message' => "{$count} chèque(s) marqué(s) comme {$lbl}!"]);
    }

    // ─── Bulk Actions ────────────────────────────────────────────────────────────

    public function openBulkModal()
    {
        if (empty($this->selectedIds)) {
            return;
        }
        $this->bulkNewStatus   = 'deposited';
        $this->bulkDepositDate = now()->format('Y-m-d');
        $this->bulkNotes       = '';
        $this->showBulkModal   = true;
    }

    public function closeBulkModal()
    {
        $this->showBulkModal = false;
    }

    public function saveBulkUpdate()
    {
        if (empty($this->selectedIds)) {
            return;
        }

        $cheques = Cheque::whereIn('id', $this->selectedIds)->get();
        $totalAmount = 0;

        foreach ($cheques as $cheque) {
            $updateData = [
                'status' => $this->bulkNewStatus,
                'notes'  => $this->bulkNotes ?: $cheque->notes,
            ];

            if ($this->bulkNewStatus === 'deposited') {
                $updateData['deposit_date'] = $this->bulkDepositDate ?: now()->format('Y-m-d');
            } elseif ($this->bulkNewStatus === 'collected') {
                $updateData['collection_date'] = now()->format('Y-m-d');
                if ($this->bulkDepositDate) {
                    $updateData['deposit_date'] = $this->bulkDepositDate;
                }
                $totalAmount += $cheque->amount;
            }

            $cheque->update($updateData);
        }

        // Update bank balance once for all collected cheques
        if ($this->bulkNewStatus === 'collected' && $totalAmount > 0) {
            $bank = BankAccount::first();
            if ($bank) {
                $bank->increment('current_balance', $totalAmount);
            }
        }

        $count = count($this->selectedIds);
        $this->selectedIds   = [];
        $this->selectAll     = false;
        $this->showBulkModal = false;
        $this->dispatch('swal:success', ['message' => "{$count} chèque(s) mis à jour avec succès!"]);
    }

    // ─── Query Builder (shared) ──────────────────────────────────────────────────

    private function buildQuery()
    {
        $query = Cheque::with(['client', 'contract']);

        // Search
        if (!empty($this->search)) {
            $s = $this->search;
            $query->where(function ($q) use ($s) {
                $q->where('cheque_number', 'like', "%{$s}%")
                  ->orWhere('bank_name', 'like', "%{$s}%")
                  ->orWhere('issuer', 'like', "%{$s}%")
                  ->orWhereHas('client', function ($cq) use ($s) {
                      $cq->where('nom_complet', 'like', "%{$s}%")
                        ->orWhere('last_name', 'like', "%{$s}%")
                        ->orWhere('first_name', 'like', "%{$s}%")
                        ->orWhere('cin', 'like', "%{$s}%");
                  })
                  ->orWhereHas('contract', function ($ctq) use ($s) {
                      $ctq->where('numero_contrat', 'like', "%{$s}%");
                  });
            });
        }

        // Filter by status
        if (!empty($this->filterStatus)) {
            if ($this->filterStatus === 'pending') {
                $query->whereIn('status', ['received', 'pending', 'created']);
            } elseif ($this->filterStatus === 'collected') {
                $query->whereIn('status', ['collected', 'validated']);
            } elseif ($this->filterStatus === 'returned') {
                $query->whereIn('status', ['returned', 'rejected']);
            } else {
                $query->where('status', $this->filterStatus);
            }
        }

        // Filter by bank
        if (!empty($this->filterBank)) {
            $query->where('bank_name', 'like', "%{$this->filterBank}%");
        }

        // Filter by payment received (dakhlat)
        if ($this->filterPaymentReceived === 'yes') {
            $query->whereIn('status', ['collected', 'validated']);
        } elseif ($this->filterPaymentReceived === 'no') {
            $query->whereNotIn('status', ['collected', 'validated']);
        }

        // Date range filter (on due_date)
        if (!empty($this->filterDateFrom)) {
            $query->whereDate('due_date', '>=', $this->filterDateFrom);
        }
        if (!empty($this->filterDateTo)) {
            $query->whereDate('due_date', '<=', $this->filterDateTo);
        }

        return $query->orderBy('due_date', 'asc');
    }

    // ─── Render ──────────────────────────────────────────────────────────────────

    public function render()
    {
        // KPI Metrics (global, not filtered)
        $totalCount    = Cheque::count();
        $totalAmount   = Cheque::sum('amount');

        $pendingCount  = Cheque::whereIn('status', ['received', 'pending', 'created'])->count();
        $pendingAmount = Cheque::whereIn('status', ['received', 'pending', 'created'])->sum('amount');

        $depositedCount  = Cheque::where('status', 'deposited')->count();
        $depositedAmount = Cheque::where('status', 'deposited')->sum('amount');

        $collectedCount  = Cheque::whereIn('status', ['collected', 'validated'])->count();
        $collectedAmount = Cheque::whereIn('status', ['collected', 'validated'])->sum('amount');

        $returnedCount  = Cheque::whereIn('status', ['returned', 'rejected'])->count();
        $returnedAmount = Cheque::whereIn('status', ['returned', 'rejected'])->sum('amount');

        // Distinct banks for filter
        $banks = Cheque::whereNotNull('bank_name')
            ->selectRaw('DISTINCT bank_name')
            ->orderBy('bank_name')
            ->pluck('bank_name')
            ->filter()
            ->values();

        // Paginated result
        $cheques = $this->buildQuery()->paginate(20);

        // Stats for selected (filtered) set
        $filteredTotal  = $this->buildQuery()->count();
        $filteredAmount = $this->buildQuery()->sum('amount');

        // Total amount and status flags for checked bulk selection
        $selectedAmount = 0;
        $hasPending = false;
        $hasDeposited = false;
        $hasCollected = false;

        if (!empty($this->selectedIds)) {
            $selectedCheques = Cheque::whereIn('id', $this->selectedIds)->get();
            $selectedAmount  = $selectedCheques->sum('amount');

            foreach ($selectedCheques as $chq) {
                if (in_array($chq->status, ['received', 'pending', 'created'])) {
                    $hasPending = true;
                } elseif ($chq->status === 'deposited') {
                    $hasDeposited = true;
                } elseif (in_array($chq->status, ['collected', 'validated'])) {
                    $hasCollected = true;
                }
            }
        }

        return view('livewire.admin.gestion-cheques', [
            'cheques'         => $cheques,
            'banks'           => $banks,
            'totalCount'      => $totalCount,
            'totalAmount'     => $totalAmount,
            'pendingCount'    => $pendingCount,
            'pendingAmount'   => $pendingAmount,
            'depositedCount'  => $depositedCount,
            'depositedAmount' => $depositedAmount,
            'collectedCount'  => $collectedCount,
            'collectedAmount' => $collectedAmount,
            'returnedCount'   => $returnedCount,
            'returnedAmount'  => $returnedAmount,
            'filteredTotal'   => $filteredTotal,
            'filteredAmount'  => $filteredAmount,
            'selectedAmount'  => $selectedAmount,
            'hasPending'      => $hasPending,
            'hasDeposited'    => $hasDeposited,
            'hasCollected'    => $hasCollected,
        ])->layout('layouts.app');
    }
}
