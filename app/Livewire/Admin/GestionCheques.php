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

    public $search = '';
    public $filterStatus = '';
    public $filterBank = '';

    // Status Update Modal
    public $showModal = false;
    public $selectedChequeId = null;
    public $selectedChequeNumber = '';
    public $newStatus = 'deposited';
    public $depositDate = '';
    public $notes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function mount()
    {
        $this->depositDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

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
            'notes' => $this->notes,
        ];

        if ($this->newStatus === 'deposited') {
            $updateData['deposit_date'] = $this->depositDate ?: now()->format('Y-m-d');
        } elseif ($this->newStatus === 'collected') {
            $updateData['collection_date'] = now()->format('Y-m-d');
            
            // Increment bank account if available
            $bank = BankAccount::first();
            if ($bank) {
                $bank->increment('current_balance', $cheque->amount);
            }
        }

        $cheque->update($updateData);

        // Audit Trail Log
        try {
            if (class_exists('\App\Models\FinancialAuditLog')) {
                FinancialAuditLog::create([
                    'user_id' => auth()->id() ?? 1,
                    'action' => 'cheque_status_change',
                    'old_values' => ['status' => $oldStatus],
                    'new_values' => ['status' => $this->newStatus, 'deposit_date' => $this->depositDate],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'reason' => "Mise à jour du chèque #{$cheque->cheque_number} vers {$this->newStatus}",
                ]);
            }
        } catch (\Throwable $e) {
            // Ignore audit log error if any
        }

        $this->closeModal();
        $this->dispatch('swal:success', ['message' => "Le statut du chèque N° {$cheque->cheque_number} a été mis à jour avec succès!"]);
    }

    public function quickSetStatus($chequeId, $status)
    {
        $cheque = Cheque::findOrFail($chequeId);
        $oldStatus = $cheque->status;

        $updateData = ['status' => $status];
        if ($status === 'deposited' && !$cheque->deposit_date) {
            $updateData['deposit_date'] = now()->format('Y-m-d');
        } elseif ($status === 'collected') {
            $updateData['collection_date'] = now()->format('Y-m-d');
            $bank = BankAccount::first();
            if ($bank) {
                $bank->increment('current_balance', $cheque->amount);
            }
        }

        $cheque->update($updateData);

        $this->dispatch('swal:success', ['message' => "Statut du chèque N° {$cheque->cheque_number} mis à jour (Statut: {$status})."]);
    }

    public function render()
    {
        // KPI Metrics
        $totalCount = Cheque::count();
        $totalAmount = Cheque::sum('amount');

        $pendingCount = Cheque::whereIn('status', ['received', 'pending', 'created'])->count();
        $pendingAmount = Cheque::whereIn('status', ['received', 'pending', 'created'])->sum('amount');

        $depositedCount = Cheque::where('status', 'deposited')->count();
        $depositedAmount = Cheque::where('status', 'deposited')->sum('amount');

        $collectedCount = Cheque::whereIn('status', ['collected', 'validated'])->count();
        $collectedAmount = Cheque::whereIn('status', ['collected', 'validated'])->sum('amount');

        $returnedCount = Cheque::whereIn('status', ['returned', 'rejected'])->count();
        $returnedAmount = Cheque::whereIn('status', ['returned', 'rejected'])->sum('amount');

        // Query
        $query = Cheque::with(['client', 'contract']);

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

        $cheques = $query->orderBy('due_date', 'asc')->paginate(15);

        return view('livewire.admin.gestion-cheques', [
            'cheques' => $cheques,
            'totalCount' => $totalCount,
            'totalAmount' => $totalAmount,
            'pendingCount' => $pendingCount,
            'pendingAmount' => $pendingAmount,
            'depositedCount' => $depositedCount,
            'depositedAmount' => $depositedAmount,
            'collectedCount' => $collectedCount,
            'collectedAmount' => $collectedAmount,
            'returnedCount' => $returnedCount,
            'returnedAmount' => $returnedAmount,
        ])->layout('layouts.app');
    }
}
