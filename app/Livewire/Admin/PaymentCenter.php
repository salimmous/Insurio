<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\FinancialLedger;
use App\Models\Cheque;
use App\Models\BankAccount;
use App\Models\CashRegister;
use App\Models\FinancialAuditLog;
use App\Models\PaymentApproval;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Compagnie;
use App\Models\Succursale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentCenter extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $activeTab = 'ledger'; // ledger, cheques, caisses, banks, approvals, audit

    // Search & Filters
    public $search = '';
    public $filterEntryType = '';
    public $filterMethod = '';
    public $filterCategory = '';
    public $filterBranch = '';
    public $filterDateStart = '';
    public $filterDateEnd = '';

    // Create Transaction Modal (General Ledger Entry)
    public $showCreateModal = false;
    public $client_id = '';
    public $contract_id = '';
    public $category = 'encaissement_prime';
    public $entry_type = 'credit'; // credit = Recette (+), debit = Dépense (-)
    public $amount = 0.00;
    public $payment_method = 'cash';
    public $currency = 'DH';
    public $notes = '';

    // Moroccan Cheque Fields
    public $cheque_number = '';
    public $bank_name = 'Attijariwafa Bank';
    public $cheque_issuer = '';
    public $cheque_due_date = '';
    public $cheque_front_scan;

    // Cash Register Operation Modal
    public $showCashOpModal = false;
    public $cash_op_type = 'deposit'; // deposit, withdrawal, physical_count
    public $cash_amount = 0.00;
    public $cash_notes = '';
    public $physical_count_amount = 0.00;

    // Cheque Status Update Modal
    public $selectedChequeId = null;
    public $newChequeStatus = 'deposited';
    public $showChequeStatusModal = false;

    // Double Validation Modal
    public $selectedApprovalId = null;
    public $approval_action = 'approve';
    public $approval_notes = '';
    public $showApprovalModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'ledger'],
        'filterEntryType' => ['except' => ''],
        'filterMethod' => ['except' => ''],
    ];

    public function mount()
    {
        $this->cheque_due_date = now()->format('Y-m-d');
        $this->ensureInitialBankAndCashSetup();
    }

    public function ensureInitialBankAndCashSetup()
    {
        if (CashRegister::count() === 0) {
            CashRegister::create([
                'name' => 'Caisse Principale Agence',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'expected_balance' => 0.00,
                'physical_balance' => 0.00,
                'is_open' => true,
            ]);
        }

        if (BankAccount::count() === 0) {
            $banks = [
                ['bank_name' => 'Attijariwafa Bank', 'agency' => 'Casablanca CFC', 'rib' => '007 780 0001234567890123 45'],
                ['bank_name' => 'Banque Populaire (BCP)', 'agency' => 'Casablanca Maarif', 'rib' => '190 780 0009876543210987 12'],
                ['bank_name' => 'BMCE Bank of Africa', 'agency' => 'Casablanca Anfa', 'rib' => '011 780 0005554443332221 88'],
            ];

            foreach ($banks as $b) {
                BankAccount::create(array_merge($b, [
                    'opening_balance' => 0.00,
                    'current_balance' => 0.00,
                    'is_active' => true,
                ]));
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->client_id = '';
        $this->contract_id = '';
        $this->category = 'encaissement_prime';
        $this->entry_type = 'credit';
        $this->amount = 0.00;
        $this->payment_method = 'cash';
        $this->notes = '';
        $this->cheque_number = '';
        $this->bank_name = 'Attijariwafa Bank';
        $this->cheque_issuer = '';
        $this->cheque_due_date = now()->format('Y-m-d');
        $this->cheque_front_scan = null;
    }

    public function createLedgerEntry()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        DB::transaction(function () {
            $chequeId = null;

            // Handle Cheque Creation
            if ($this->payment_method === 'cheque') {
                $frontScanPath = null;
                if ($this->cheque_front_scan) {
                    $frontScanPath = $this->cheque_front_scan->store('cheques', 'public');
                }

                $cheque = Cheque::create([
                    'cheque_number' => $this->cheque_number ?: 'CHQ-' . rand(100000, 999999),
                    'bank_name' => $this->bank_name,
                    'issuer' => $this->cheque_issuer ?: ($this->client_id ? Client::find($this->client_id)->nom_complet : 'Émetteur Incognito'),
                    'due_date' => $this->cheque_due_date ?: now()->format('Y-m-d'),
                    'amount' => $this->amount,
                    'status' => 'received',
                    'front_image' => $frontScanPath,
                    'notes' => $this->notes,
                    'client_id' => $this->client_id ?: null,
                    'contract_id' => $this->contract_id ?: null,
                ]);

                $chequeId = $cheque->id;
            }

            // Create General Ledger Record
            $ledger = FinancialLedger::create([
                'category' => $this->category,
                'entry_type' => $this->entry_type,
                'amount' => $this->amount,
                'currency' => 'DH',
                'payment_method' => $this->payment_method,
                'status' => $this->amount > 5000 ? 'pending' : 'completed',
                'notes' => $this->notes,
                'user_id' => auth()->id() ?? 1,
                'client_id' => $this->client_id ?: null,
                'contract_id' => $this->contract_id ?: null,
                'cheque_id' => $chequeId,
            ]);

            // If amount > 5000 DH, trigger Double Validation Approval Workflow
            if ($this->amount > 5000) {
                PaymentApproval::create([
                    'ledger_id' => $ledger->id,
                    'requested_by' => auth()->id() ?? 1,
                    'amount' => $this->amount,
                    'status' => 'pending_manager',
                    'manager_notes' => 'Transaction importante nécessitant la validation de la direction.',
                ]);
            } else {
                // Update Cash Register or Bank Balance immediately
                if ($this->payment_method === 'cash') {
                    $caisse = CashRegister::first();
                    if ($caisse) {
                        if ($this->entry_type === 'credit') {
                            $caisse->increment('current_balance', $this->amount);
                            $caisse->increment('expected_balance', $this->amount);
                        } else {
                            $caisse->decrement('current_balance', $this->amount);
                            $caisse->decrement('expected_balance', $this->amount);
                        }
                    }
                }
            }

            // Log Audit Trail
            FinancialAuditLog::create([
                'ledger_id' => $ledger->id,
                'user_id' => auth()->id() ?? 1,
                'action' => 'created',
                'new_values' => $ledger->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'reason' => 'Saisie directe d\'opération financière au Grand Livre',
            ]);
        });

        $this->closeCreateModal();
        $this->dispatch('swal:success', ['message' => 'Opération financière enregistrée avec succès au Grand Livre.']);
    }

    public function updateChequeStatus($chequeId, $status)
    {
        $cheque = Cheque::findOrFail($chequeId);
        $oldStatus = $cheque->status;
        $cheque->update(['status' => $status]);

        if ($status === 'deposited') {
            $cheque->update(['deposit_date' => now()]);
        } elseif ($status === 'collected') {
            $cheque->update(['collection_date' => now()]);
            // Increment Bank Account Balance
            $bank = BankAccount::first();
            if ($bank) {
                $bank->increment('current_balance', $cheque->amount);
            }
        }

        FinancialAuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'cheque_status_change',
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $status],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'reason' => "Changement de statut du chèque #{$cheque->cheque_number} vers {$status}",
        ]);

        $this->dispatch('swal:success', ['message' => "Statut du chèque #{$cheque->cheque_number} mis à jour."]);
    }

    public function approveTransaction($approvalId)
    {
        DB::transaction(function () use ($approvalId) {
            $approval = PaymentApproval::findOrFail($approvalId);
            $approval->update([
                'status' => 'approved',
                'approved_by_manager' => auth()->id() ?? 1,
                'approved_by_finance' => auth()->id() ?? 1,
            ]);

            if ($approval->ledger) {
                $approval->ledger->update(['status' => 'completed', 'approved_by' => auth()->id() ?? 1]);

                if ($approval->ledger->payment_method === 'cash') {
                    $caisse = CashRegister::first();
                    if ($caisse) {
                        if ($approval->ledger->entry_type === 'credit') {
                            $caisse->increment('current_balance', $approval->amount);
                        } else {
                            $caisse->decrement('current_balance', $approval->amount);
                        }
                    }
                }
            }
        });

        $this->dispatch('swal:success', ['message' => 'Opération financière approuvée et validée au Grand Livre.']);
    }

    public function recordPhysicalCashCount()
    {
        $caisse = CashRegister::first();
        if ($caisse) {
            $variance = $this->physical_count_amount - $caisse->expected_balance;
            $caisse->update([
                'physical_balance' => $this->physical_count_amount,
                'variance_amount' => $variance,
            ]);

            $this->dispatch('swal:success', ['message' => 'Comptage physique de caisse enregistré. Écart: ' . number_format($variance, 2) . ' DH']);
        }
    }

    public function render()
    {
        // Compute Financial Treasury Metrics
        $todayRevenue = FinancialLedger::whereDate('entry_date', now())->where('entry_type', 'credit')->sum('amount');
        $todayExpenses = FinancialLedger::whereDate('entry_date', now())->where('entry_type', 'debit')->sum('amount');
        $cashBalance = CashRegister::sum('current_balance');
        $bankBalance = BankAccount::sum('current_balance');
        $pendingChequesSum = Cheque::whereIn('status', ['received', 'pending', 'deposited', 'under_collection'])->sum('amount');
        $pendingChequesCount = Cheque::whereIn('status', ['received', 'pending', 'deposited', 'under_collection'])->count();
        $returnedChequesCount = Cheque::whereIn('status', ['returned', 'rejected'])->count();
        $pendingApprovalsCount = PaymentApproval::where('status', '!=', 'approved')->count();

        // General Ledger Query
        $ledgerQuery = FinancialLedger::with(['user', 'client', 'contract', 'cheque']);

        if (!empty($this->search)) {
            $ledgerQuery->where(function ($q) {
                $q->where('transaction_id', 'like', '%' . $this->search . '%')
                  ->orWhere('receipt_number', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function ($cq) {
                      $cq->where('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('cin', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if (!empty($this->filterEntryType)) {
            $ledgerQuery->where('entry_type', $this->filterEntryType);
        }

        if (!empty($this->filterMethod)) {
            $ledgerQuery->where('payment_method', $this->filterMethod);
        }

        return view('livewire.admin.payment-center', [
            'ledgers' => $ledgerQuery->latest('entry_date')->paginate(15),
            'cheques' => Cheque::with('client')->latest('due_date')->get(),
            'bankAccounts' => BankAccount::all(),
            'cashRegisters' => CashRegister::all(),
            'approvals' => PaymentApproval::with(['ledger.client', 'requester'])->where('status', '!=', 'approved')->get(),
            'auditLogs' => FinancialAuditLog::with('user')->latest()->take(20)->get(),
            'clients' => Client::orderBy('last_name')->take(50)->get(),
            'todayRevenue' => $todayRevenue,
            'todayExpenses' => $todayExpenses,
            'cashBalance' => $cashBalance,
            'bankBalance' => $bankBalance,
            'pendingChequesSum' => $pendingChequesSum,
            'pendingChequesCount' => $pendingChequesCount,
            'returnedChequesCount' => $returnedChequesCount,
            'pendingApprovalsCount' => $pendingApprovalsCount,
        ])->layout('layouts.app');
    }
}
