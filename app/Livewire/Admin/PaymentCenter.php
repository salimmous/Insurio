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
use App\Models\Payment;
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
    public $filterDatePreset = 'today'; // default to today
    public $filterDate = '';
    public $filterDateStart = '';
    public $filterDateEnd = '';

    // Create Transaction Modal (General Ledger Entry)
    public $showCreateModal = false;
    public $client_id = '';
    public $contract_id = '';
    public $category = 'charge';
    public $entry_type = 'debit'; // Locked to debit (-) for manual expenses/outputs
    public $amount = 0.00;
    public $paid_amount = 0.00;
    public $reconcile_payment_id = null;
    public $reconcile_ref = '';
    public $reconcile_amount = 0.00;
    public $payment_method = 'cash';
    public $currency = 'DH';
    public $notes = '';

    // Moroccan Cheque Fields
    public $cheque_number = '';
    public $bank_name = 'Attijariwafa Bank';
    public $cheque_issuer = '';
    public $cheque_due_date = '';
    public $cheque_front_scan;

    // Cash Register Movement Modal (Entrées / Sorties d'Espèces)
    public $showCashMovementModal = false;
    public $cash_movement_type = 'debit'; // 'debit' = Sortie (-), 'credit' = Entrée (+)
    public $cash_movement_amount = '';
    public $cash_movement_category = 'depense_agence';
    public $cash_movement_notes = '';
    public $cash_movement_date = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'caisses'],
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

    public function updatedFilterDatePreset() { $this->filterDate = ''; $this->resetPage(); }
    public function updatedFilterDate() { $this->filterDatePreset = 'all'; $this->resetPage(); }
    public function updatedFilterEntryType() { $this->resetPage(); }
    public function updatedFilterMethod() { $this->resetPage(); }
    public function updatedFilterCategory() { $this->resetPage(); }
    public function updatedFilterDateStart() { $this->resetPage(); }
    public function updatedFilterDateEnd() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterEntryType = '';
        $this->filterMethod = '';
        $this->filterCategory = '';
        $this->filterDatePreset = 'today';
        $this->filterDate = '';
        $this->filterDateStart = '';
        $this->filterDateEnd = '';
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
        $this->category = 'charge';
        $this->entry_type = 'debit';
        $this->amount = 0.00;
        $this->payment_method = 'cash';
        $this->notes = '';
        $this->cheque_number = '';
        $this->bank_name = 'Attijariwafa Bank';
        $this->cheque_issuer = '';
        $this->cheque_due_date = now()->format('Y-m-d');
        $this->cheque_front_scan = null;
    }

    public function createPayment()
    {
        if ($this->paid_amount > 0 && floatval($this->amount) == 0) {
            $this->amount = $this->paid_amount;
        }
        return $this->createLedgerEntry();
    }

    public function createReconciliation()
    {
        if ($this->reconcile_payment_id && ($payment = Payment::find($this->reconcile_payment_id))) {
            $payment->update(['payment_status' => 'deposited']);
            if (class_exists(\App\Models\BankReconciliation::class)) {
                \App\Models\BankReconciliation::create([
                    'payment_id' => $payment->id,
                    'reference' => $this->reconcile_ref ?: ('TXN-' . rand(1000, 9999)),
                    'deposit_date' => now(),
                    'amount' => $this->reconcile_amount ?: $payment->amount,
                    'difference' => 0.00,
                    'matched' => true,
                    'user_id' => auth()->id() ?? 1,
                ]);
            } else {
                DB::table('bank_reconciliations')->insert([
                    'payment_id' => $payment->id,
                    'reference' => $this->reconcile_ref ?: ('TXN-' . rand(1000, 9999)),
                    'deposit_date' => now(),
                    'amount' => $this->reconcile_amount ?: $payment->amount,
                    'difference' => 0.00,
                    'matched' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
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

            if ($this->client_id && class_exists(\App\Models\Payment::class)) {
                $pmtNum = 'REC-' . date('Ymd') . '-' . sprintf('%05d', rand(1, 99999));
                $paymentRec = \App\Models\Payment::create([
                    'uuid' => (string) Str::uuid(),
                    'payment_number' => $pmtNum,
                    'client_id' => $this->client_id,
                    'contract_id' => $this->contract_id ?: null,
                    'amount' => $this->amount,
                    'paid_amount' => $this->amount,
                    'remaining_amount' => 0,
                    'payment_method' => $this->payment_method,
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'created_by' => auth()->id() ?? 1,
                ]);

                if ($this->contract_id && ($contract = \App\Models\Contract::find($this->contract_id))) {
                    $contract->update([
                        'payment_status' => 'paid',
                        'statut' => 'actif',
                    ]);
                }
            }

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

    public function openCashMovementModal($type = 'debit')
    {
        $this->cash_movement_type = $type;
        $this->cash_movement_amount = '';
        $this->cash_movement_category = $type === 'debit' ? 'depense_caisse' : 'encaissement_caisse';
        $this->cash_movement_notes = '';
        $this->cash_movement_date = now()->format('Y-m-d\TH:i');
        $this->client_id = '';
        $this->showCashMovementModal = true;
    }

    public function closeCashMovementModal()
    {
        $this->showCashMovementModal = false;
        $this->cash_movement_amount = '';
        $this->cash_movement_notes = '';
    }

    public function recordCashMovement()
    {
        $this->validate([
            'cash_movement_amount' => 'required|numeric|min:0.01',
            'cash_movement_notes' => 'required|string|min:3',
        ]);

        DB::transaction(function () {
            $amount = floatval($this->cash_movement_amount);
            $entryType = $this->cash_movement_type; // 'credit' = Entrée (+), 'debit' = Sortie (-)
            $caisse = CashRegister::first();

            if (!$caisse) {
                $caisse = CashRegister::create([
                    'name' => 'Caisse Principale Agence',
                    'opening_balance' => 0.00,
                    'current_balance' => 0.00,
                    'expected_balance' => 0.00,
                    'physical_balance' => 0.00,
                    'is_open' => true,
                ]);
            }

            $entryDate = $this->cash_movement_date ? Carbon::parse($this->cash_movement_date) : now();

            // Create Financial Ledger Record for Cash Movement
            $ledger = FinancialLedger::create([
                'category' => $this->cash_movement_category ?: ($entryType === 'debit' ? 'depense_caisse' : 'encaissement_caisse'),
                'entry_type' => $entryType,
                'amount' => $amount,
                'currency' => 'DH',
                'payment_method' => 'cash',
                'status' => 'completed',
                'notes' => $this->cash_movement_notes,
                'entry_date' => $entryDate,
                'user_id' => auth()->id() ?? 1,
                'client_id' => $this->client_id ?: null,
                'cash_register_id' => $caisse->id,
            ]);

            // Update Caisse current balance
            if ($entryType === 'credit') {
                $caisse->increment('current_balance', $amount);
                $caisse->increment('expected_balance', $amount);
            } else {
                $caisse->decrement('current_balance', $amount);
                $caisse->decrement('expected_balance', $amount);
            }

            // Log Audit Trail
            FinancialAuditLog::create([
                'ledger_id' => $ledger->id,
                'user_id' => auth()->id() ?? 1,
                'action' => $entryType === 'debit' ? 'cash_withdrawal' : 'cash_deposit',
                'new_values' => [
                    'amount' => $amount,
                    'type' => $entryType,
                    'notes' => $this->cash_movement_notes,
                    'new_balance' => $caisse->fresh()->current_balance,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'reason' => 'Mouvement d\'espèces enregistré manuellement en caisse',
            ]);
        });

        $this->closeCashMovementModal();
        $this->dispatch('swal:success', ['message' => 'Mouvement de caisse enregistré avec succès.']);
    }

    public function getCashJournalProperty()
    {
        $caisse = CashRegister::first();
        $openingBalance = $caisse ? (float)$caisse->opening_balance : 0.00;

        $cashTxs = FinancialLedger::with(['user', 'client', 'contract'])
            ->where('payment_method', 'cash')
            ->where('status', 'completed')
            ->orderBy('entry_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $running = $openingBalance;
        $processed = collect();

        foreach ($cashTxs as $tx) {
            $amt = (float)$tx->amount;
            if ($tx->entry_type === 'credit') {
                $running += $amt;
            } else {
                $running -= $amt;
            }

            $txObj = clone $tx;
            $txObj->running_balance = $running;
            $processed->push($txObj);
        }

        $grouped = $processed->groupBy(function ($tx) {
            return $tx->entry_date ? $tx->entry_date->format('Y-m-d') : now()->format('Y-m-d');
        })->sortKeysDesc();

        return $grouped->map(function ($dayTxs, $dateStr) {
            $totalIn = $dayTxs->where('entry_type', 'credit')->sum('amount');
            $totalOut = $dayTxs->where('entry_type', 'debit')->sum('amount');
            $lastTx = $dayTxs->last();
            $endBalance = $lastTx ? $lastTx->running_balance : 0.00;

            return [
                'date' => $dateStr,
                'formatted_date' => Carbon::parse($dateStr)->isoFormat('dddd D MMMM YYYY'),
                'is_today' => $dateStr === now()->format('Y-m-d'),
                'is_yesterday' => $dateStr === now()->subDay()->format('Y-m-d'),
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'net_change' => $totalIn - $totalOut,
                'end_balance' => $endBalance,
                'transactions' => $dayTxs->reverse(),
            ];
        });
    }

    public function syncRealCashBalance()
    {
        $caisse = CashRegister::first();
        if (!$caisse) {
            $caisse = CashRegister::create([
                'name' => 'Caisse Principale Agence',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'expected_balance' => 0.00,
                'physical_balance' => 0.00,
                'is_open' => true,
            ]);
        }

        $opening = (float)$caisse->opening_balance;
        $totalCashIn = (float)FinancialLedger::where('payment_method', 'cash')->where('status', 'completed')->where('entry_type', 'credit')->sum('amount');
        $totalCashOut = (float)FinancialLedger::where('payment_method', 'cash')->where('status', 'completed')->where('entry_type', 'debit')->sum('amount');
        $realBalance = $opening + $totalCashIn - $totalCashOut;

        $physical = (float)$caisse->physical_balance;
        if ($physical <= 0) {
            $physical = $realBalance;
        }
        $variance = $physical - $realBalance;

        $caisse->update([
            'current_balance' => $realBalance,
            'expected_balance' => $realBalance,
            'physical_balance' => $physical,
            'variance_amount' => $variance,
        ]);
    }

    public function render()
    {
        $this->syncRealCashBalance();

        // Base Query
        $ledgerQuery = FinancialLedger::with(['user', 'client', 'contract', 'cheque']);

        // Search Filter
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

        // Operation Type Filter (credit = Encaissements +, debit = Décaissements -)
        if (!empty($this->filterEntryType)) {
            $ledgerQuery->where('entry_type', $this->filterEntryType);
        }

        // Payment Method Filter
        if (!empty($this->filterMethod)) {
            $ledgerQuery->where('payment_method', $this->filterMethod);
        }

        // Category Filter
        if (!empty($this->filterCategory)) {
            $ledgerQuery->where('category', $this->filterCategory);
        }

        // Date Filter (Direct Calendar Pick or Presets)
        if (!empty($this->filterDate)) {
            $ledgerQuery->whereDate('entry_date', $this->filterDate);
        } elseif ($this->filterDatePreset === 'today') {
            $ledgerQuery->whereDate('entry_date', now()->format('Y-m-d'));
        } elseif ($this->filterDatePreset === 'yesterday') {
            $ledgerQuery->whereDate('entry_date', now()->subDay()->format('Y-m-d'));
        } elseif ($this->filterDatePreset === 'this_week') {
            $ledgerQuery->whereBetween('entry_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->filterDatePreset === 'this_month') {
            $ledgerQuery->whereBetween('entry_date', [now()->startOfMonth(), now()->endOfMonth()]);
        } elseif ($this->filterDatePreset === 'custom') {
            if (!empty($this->filterDateStart)) {
                $ledgerQuery->whereDate('entry_date', '>=', $this->filterDateStart);
            }
            if (!empty($this->filterDateEnd)) {
                $ledgerQuery->whereDate('entry_date', '<=', $this->filterDateEnd);
            }
        }

        // Totals for active filtered view
        $totalRecettes = (float)(clone $ledgerQuery)->where('entry_type', 'credit')->sum('amount');
        $totalDepenses = (float)(clone $ledgerQuery)->where('entry_type', 'debit')->sum('amount');
        $soldeNet = $totalRecettes - $totalDepenses;

        // Fetch ledgers ordered by date
        $allLedgers = $ledgerQuery->orderBy('entry_date', 'desc')->get();

        // Group transactions by Day
        $groupedJournal = $allLedgers->groupBy(function ($item) {
            return $item->entry_date ? Carbon::parse($item->entry_date)->format('Y-m-d') : 'sans_date';
        })->map(function ($dayItems, $dateStr) {
            $totalIn = $dayItems->where('entry_type', 'credit')->sum('amount');
            $totalOut = $dayItems->where('entry_type', 'debit')->sum('amount');

            return [
                'date_key' => $dateStr,
                'formatted_date' => $dateStr !== 'sans_date' ? Carbon::parse($dateStr)->isoFormat('dddd D MMMM YYYY') : 'Date Non Spécifiée',
                'is_today' => $dateStr === now()->format('Y-m-d'),
                'is_yesterday' => $dateStr === now()->subDay()->format('Y-m-d'),
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'net_day' => $totalIn - $totalOut,
                'transactions' => $dayItems,
            ];
        });

        // Today's summary
        $todayRevenue = (float)FinancialLedger::whereDate('entry_date', now())->where('entry_type', 'credit')->sum('amount');
        $todayExpenses = (float)FinancialLedger::whereDate('entry_date', now())->where('entry_type', 'debit')->sum('amount');

        // Global Balances
        $cashBalance = CashRegister::sum('current_balance');
        $bankBalance = BankAccount::sum('current_balance');
        $pendingChequesSum = Cheque::whereIn('status', ['received', 'pending', 'deposited', 'under_collection'])->sum('amount');
        $pendingChequesCount = Cheque::whereIn('status', ['received', 'pending', 'deposited', 'under_collection'])->count();

        return view('livewire.admin.payment-center', [
            'filterDate' => $this->filterDate,
            'filterDatePreset' => $this->filterDatePreset,
            'filterEntryType' => $this->filterEntryType,
            'filterMethod' => $this->filterMethod,
            'filterCategory' => $this->filterCategory,
            'todayRevenue' => $todayRevenue,
            'todayExpenses' => $todayExpenses,
            'groupedJournal' => $groupedJournal,
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'soldeNet' => $soldeNet,
            'cashBalance' => $cashBalance,
            'bankBalance' => $bankBalance,
            'pendingChequesSum' => $pendingChequesSum,
            'pendingChequesCount' => $pendingChequesCount,
            'cheques' => Cheque::with('client')->latest('due_date')->get(),
            'clients' => Client::orderBy('last_name')->take(50)->get(),
        ])->layout('layouts.app');
    }
}
