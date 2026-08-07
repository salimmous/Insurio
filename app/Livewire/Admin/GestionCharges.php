<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AgencyExpense;
use App\Models\Succursale;

class GestionCharges extends Component
{
    public $expenses = [];
    public $succursales = [];

    // Form fields
    public $expenseId;
    public $title;
    public $category = 'loyer';
    public $amount;
    public $date_charge;
    public $description;
    public $succursale_id;

    // Filters
    public $filterCategory = '';
    public $filterSuccursale = '';

    public $isEditing = false;
    public $showModal = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'category' => 'required|string|in:loyer,electricite,eau,salaire,autre',
        'amount' => 'required|numeric|min:0',
        'date_charge' => 'required|date',
        'description' => 'nullable|string',
        'succursale_id' => 'nullable|exists:succursales,id',
    ];

    public function mount()
    {
        if (!auth()->user() || (!auth()->user()->hasRole('agency-admin') && !auth()->user()->hasRole('comptable'))) {
            abort(403, 'Accès non autorisé.');
        }

        $this->syncMissingExpensesToLedger();
        $this->date_charge = now()->format('Y-m-d');
        $this->loadData();
    }

    private function syncMissingExpensesToLedger()
    {
        $expenses = AgencyExpense::all();
        $caisse = \App\Models\CashRegister::first();

        foreach ($expenses as $exp) {
            $existing = \App\Models\FinancialLedger::where('category', 'charge')
                ->where('amount', $exp->amount)
                ->whereDate('entry_date', $exp->date_charge)
                ->first();

            if (!$existing) {
                $trxId = 'CHG-' . date('Ymd', strtotime($exp->date_charge ?? 'now')) . '-' . str_pad($exp->id, 5, '0', STR_PAD_LEFT);
                $recId = 'REC-CHG-' . date('Ymd', strtotime($exp->date_charge ?? 'now')) . '-' . str_pad($exp->id, 5, '0', STR_PAD_LEFT);

                \App\Models\FinancialLedger::create([
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'transaction_id' => $trxId,
                    'entry_date' => $exp->date_charge ?? now(),
                    'category' => 'charge',
                    'entry_type' => 'debit',
                    'amount' => $exp->amount,
                    'currency' => 'DH',
                    'payment_method' => 'cash',
                    'status' => 'completed',
                    'receipt_number' => $recId,
                    'qr_code_hash' => md5($trxId . '|' . $exp->amount),
                    'notes' => 'Charge Agence: ' . $exp->title . ($exp->description ? ' - ' . $exp->description : ''),
                    'user_id' => auth()->id() ?? 1,
                    'branch_id' => $exp->succursale_id ?: null,
                    'cash_register_id' => $caisse?->id,
                    'metadata' => [
                        'agency_expense_id' => $exp->id,
                        'category_type' => $exp->category,
                    ],
                ]);
            }
        }

        // Recalculate Cash Register balance
        if ($caisse) {
            $totalCashCredit = (float) \App\Models\FinancialLedger::where('payment_method', 'cash')
                ->where('entry_type', 'credit')
                ->whereIn('status', ['completed', 'posted', 'approved'])
                ->sum('amount');

            $totalCashDebit = (float) \App\Models\FinancialLedger::where('payment_method', 'cash')
                ->where('entry_type', 'debit')
                ->whereIn('status', ['completed', 'posted', 'approved'])
                ->sum('amount');

            $netCash = $totalCashCredit - $totalCashDebit;
            $caisse->update([
                'current_balance' => $netCash,
                'expected_balance' => $netCash,
            ]);
        }
    }

    // Filters
    public $filterCategory = '';
    public $filterSuccursale = '';
    public $search = '';

    public function updatedSearch()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $query = AgencyExpense::with('succursale')->latest('date_charge');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        if ($this->filterSuccursale) {
            $query->where('succursale_id', $this->filterSuccursale);
        }

        $this->expenses = $query->get();
        $this->succursales = Succursale::all();
    }

    public function updatedFilterCategory()
    {
        $this->loadData();
    }

    public function updatedFilterSuccursale()
    {
        $this->loadData();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $expense = AgencyExpense::findOrFail($id);
        $this->expenseId = $expense->id;
        $this->title = $expense->title;
        $this->category = $expense->category;
        $this->amount = $expense->amount;
        $this->date_charge = $expense->date_charge->format('Y-m-d');
        $this->description = $expense->description;
        $this->succursale_id = $expense->succursale_id;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate();

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            if ($this->isEditing) {
                $expense = AgencyExpense::findOrFail($this->expenseId);
                $oldAmount = floatval($expense->amount);
                $expense->update($validated);

                // Update corresponding FinancialLedger if present
                $ledger = \App\Models\FinancialLedger::where('metadata->agency_expense_id', $expense->id)->first();
                if ($ledger) {
                    $ledger->update([
                        'amount' => $expense->amount,
                        'notes' => 'Charge Agence: ' . $expense->title . ($expense->description ? ' - ' . $expense->description : ''),
                        'entry_date' => $expense->date_charge,
                    ]);

                    // Adjust CashRegister balance difference
                    $caisse = \App\Models\CashRegister::first();
                    if ($caisse) {
                        $diff = floatval($expense->amount) - $oldAmount;
                        if ($diff > 0) {
                            $caisse->decrement('current_balance', $diff);
                            $caisse->decrement('expected_balance', $diff);
                        } else if ($diff < 0) {
                            $caisse->increment('current_balance', abs($diff));
                            $caisse->increment('expected_balance', abs($diff));
                        }
                    }
                }
                session()->flash('message', 'La charge a été modifiée avec succès.');
            } else {
                $expense = AgencyExpense::create($validated);

                // 1. Create entry in FinancialLedger so it syncs directly to Payment Center (Grand Livre)
                \App\Models\FinancialLedger::create([
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'transaction_id' => 'CHG-' . date('Ymd') . '-' . sprintf('%04d', rand(1000, 9999)),
                    'entry_date' => $expense->date_charge,
                    'category' => 'charge',
                    'entry_type' => 'debit',
                    'amount' => $expense->amount,
                    'currency' => 'DH',
                    'payment_method' => 'cash',
                    'status' => 'completed',
                    'notes' => 'Charge Agence: ' . $expense->title . ($expense->description ? ' - ' . $expense->description : ''),
                    'user_id' => auth()->id() ?? 1,
                    'branch_id' => $expense->succursale_id ?: null,
                    'metadata' => [
                        'agency_expense_id' => $expense->id,
                        'category_type' => $expense->category,
                    ],
                ]);

                // 2. Decrement Caisse balance immediately so cash balance stays 100% accurate!
                $caisse = \App\Models\CashRegister::first();
                if ($caisse) {
                    $caisse->decrement('current_balance', $expense->amount);
                    $caisse->decrement('expected_balance', $expense->amount);
                }

                session()->flash('message', 'La charge a été ajoutée et comptabilisée en caisse avec succès.');
            }
        });

        $this->showModal = false;
        $this->resetForm();
        $this->loadData();
    }

    public function delete($id)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($id) {
            $expense = AgencyExpense::findOrFail($id);

            // Find linked ledger and restore caisse balance
            $ledger = \App\Models\FinancialLedger::where('metadata->agency_expense_id', $expense->id)->first();
            if ($ledger) {
                $caisse = \App\Models\CashRegister::first();
                if ($caisse) {
                    $caisse->increment('current_balance', $expense->amount);
                    $caisse->increment('expected_balance', $expense->amount);
                }
                $ledger->delete();
            }

            $expense->delete();
        });

        session()->flash('message', 'La charge a été supprimée avec succès.');
        $this->loadData();
    }

    private function resetForm()
    {
        $this->expenseId = null;
        $this->title = '';
        $this->category = 'loyer';
        $this->amount = '';
        $this->date_charge = now()->format('Y-m-d');
        $this->description = '';
        $this->succursale_id = null;
    }

    public function render()
    {
        return view('livewire.admin.gestion-charges')
            ->layout('layouts.app');
    }
}
