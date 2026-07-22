<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DailyCashClosing;
use App\Models\FinancialLedger;
use App\Models\Cheque;
use App\Models\CashRegister;
use App\Models\BankAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClotureCaisse extends Component
{
    public $filterPeriod = 'today'; // today, yesterday, week, month, custom
    public $filterDateStart;
    public $filterDateEnd;
    public $filterBranch = '';
    public $filterUser = '';
    public $filterMethod = '';

    // Closing Modal State
    public $showClosingModal = false;
    public $physical_count = 0.00;
    public $closing_notes = '';
    public $manager_name = '';
    public $employee_name = '';

    public function mount()
    {
        $this->filterDateStart = now()->format('Y-m-d');
        $this->filterDateEnd = now()->format('Y-m-d');
        $this->manager_name = auth()->user()->name ?? 'Responsable Agence';
        $this->employee_name = auth()->user()->name ?? 'Caissier Agence';
    }

    public function setPeriod($period)
    {
        $this->filterPeriod = $period;
        if ($period === 'today') {
            $this->filterDateStart = now()->format('Y-m-d');
            $this->filterDateEnd = now()->format('Y-m-d');
        } elseif ($period === 'yesterday') {
            $this->filterDateStart = now()->subDay()->format('Y-m-d');
            $this->filterDateEnd = now()->subDay()->format('Y-m-d');
        } elseif ($period === 'week') {
            $this->filterDateStart = now()->startOfWeek()->format('Y-m-d');
            $this->filterDateEnd = now()->endOfWeek()->format('Y-m-d');
        } elseif ($period === 'month') {
            $this->filterDateStart = now()->startOfMonth()->format('Y-m-d');
            $this->filterDateEnd = now()->endOfMonth()->format('Y-m-d');
        }
    }

    public function openClosingModal()
    {
        $caisse = CashRegister::first();
        $this->physical_count = $caisse ? $caisse->current_balance : 0.00;
        $this->showClosingModal = true;
    }

    public function executeCashClosing()
    {
        $caisse = CashRegister::first();
        $openingBalance = $caisse ? $caisse->opening_balance : 0.00;
        
        $cashIn = FinancialLedger::whereBetween('entry_date', [$this->filterDateStart . ' 00:00:00', $this->filterDateEnd . ' 23:59:59'])
            ->where('payment_method', 'cash')->where('entry_type', 'credit')->sum('amount');

        $cashOut = FinancialLedger::whereBetween('entry_date', [$this->filterDateStart . ' 00:00:00', $this->filterDateEnd . ' 23:59:59'])
            ->where('payment_method', 'cash')->where('entry_type', 'debit')->sum('amount');

        $transfersIn = FinancialLedger::whereBetween('entry_date', [$this->filterDateStart . ' 00:00:00', $this->filterDateEnd . ' 23:59:59'])
            ->where('payment_method', 'transfer')->where('entry_type', 'credit')->sum('amount');

        $cardIn = FinancialLedger::whereBetween('entry_date', [$this->filterDateStart . ' 00:00:00', $this->filterDateEnd . ' 23:59:59'])
            ->whereIn('payment_method', ['card', 'tpe'])->where('entry_type', 'credit')->sum('amount');

        $chequesReceived = Cheque::whereBetween('created_at', [$this->filterDateStart . ' 00:00:00', $this->filterDateEnd . ' 23:59:59'])->sum('amount');
        $chequesDeposited = Cheque::whereBetween('deposit_date', [$this->filterDateStart, $this->filterDateEnd])->sum('amount');
        
        $expenses = FinancialLedger::whereBetween('entry_date', [$this->filterDateStart . ' 00:00:00', $this->filterDateEnd . ' 23:59:59'])
            ->where('category', 'charge')->sum('amount');

        $commissions = FinancialLedger::whereBetween('entry_date', [$this->filterDateStart . ' 00:00:00', $this->filterDateEnd . ' 23:59:59'])
            ->where('category', 'commission')->sum('amount');

        $refunds = FinancialLedger::whereBetween('entry_date', [$this->filterDateStart . ' 00:00:00', $this->filterDateEnd . ' 23:59:59'])
            ->where('category', 'reglement_sinistre')->sum('amount');

        $expectedClosing = $openingBalance + $cashIn - $cashOut;
        $variance = $this->physical_count - $expectedClosing;

        DailyCashClosing::create([
            'closing_date' => now()->format('Y-m-d'),
            'cash_register_id' => $caisse->id ?? null,
            'opening_balance' => $openingBalance,
            'total_cash_in' => $cashIn,
            'total_cash_out' => $cashOut,
            'total_transfers_in' => $transfersIn,
            'total_card_in' => $cardIn,
            'total_cheques_received' => $chequesReceived,
            'total_cheques_deposited' => $chequesDeposited,
            'total_expenses' => $expenses,
            'total_commissions' => $commissions,
            'total_refunds' => $refunds,
            'expected_closing_balance' => $expectedClosing,
            'physical_closing_balance' => $this->physical_count,
            'variance_amount' => $variance,
            'net_cash' => $this->physical_count,
            'net_profit' => ($cashIn + $transfersIn + $cardIn) - ($expenses + $commissions + $refunds),
            'status' => 'closed',
            'closed_by' => auth()->id() ?? 1,
            'approved_by' => auth()->id() ?? 1,
            'closed_at' => now(),
            'notes' => $this->closing_notes,
            'manager_signature' => $this->manager_name,
            'employee_signature' => $this->employee_name,
        ]);

        if ($caisse) {
            $caisse->update([
                'physical_balance' => $this->physical_count,
                'variance_amount' => $variance,
                'is_open' => false,
            ]);
        }

        $this->showClosingModal = false;
        $this->dispatch('swal:success', ['message' => 'Clôture de caisse journalière validée et enregistrée avec succès.']);
    }

    public function render()
    {
        $startDate = $this->filterDateStart . ' 00:00:00';
        $endDate = $this->filterDateEnd . ' 23:59:59';

        $caisse = CashRegister::first();
        $openingBalance = $caisse ? $caisse->opening_balance : 0.00;

        // Cash In Today
        $cashInToday = FinancialLedger::whereBetween('entry_date', [$startDate, $endDate])
            ->where('payment_method', 'cash')->where('entry_type', 'credit')->sum('amount');

        // Cash Out Today
        $cashOutToday = FinancialLedger::whereBetween('entry_date', [$startDate, $endDate])
            ->where('payment_method', 'cash')->where('entry_type', 'debit')->sum('amount');

        // Transfers In & Card
        $transfersInToday = FinancialLedger::whereBetween('entry_date', [$startDate, $endDate])
            ->where('payment_method', 'transfer')->where('entry_type', 'credit')->sum('amount');

        $cardInToday = FinancialLedger::whereBetween('entry_date', [$startDate, $endDate])
            ->whereIn('payment_method', ['card', 'tpe'])->where('entry_type', 'credit')->sum('amount');

        // Cheques Metrics
        $chequesReceivedToday = Cheque::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $chequesDepositedToday = Cheque::whereBetween('deposit_date', [$this->filterDateStart, $this->filterDateEnd])->sum('amount');
        $pendingChequesSum = Cheque::whereIn('status', ['received', 'pending', 'deposited', 'under_collection'])->sum('amount');
        $returnedChequesSum = Cheque::whereIn('status', ['returned', 'rejected'])->sum('amount');

        // Expenses & Commissions
        $expensesToday = FinancialLedger::whereBetween('entry_date', [$startDate, $endDate])->where('category', 'charge')->sum('amount');
        $commissionsToday = FinancialLedger::whereBetween('entry_date', [$startDate, $endDate])->where('category', 'commission')->sum('amount');
        $refundsToday = FinancialLedger::whereBetween('entry_date', [$startDate, $endDate])->where('category', 'reglement_sinistre')->sum('amount');

        // Summary Calculations
        $totalIncome = $cashInToday + $transfersInToday + $cardInToday + $chequesReceivedToday;
        $totalExpenses = $expensesToday + $commissionsToday + $refundsToday + $cashOutToday;
        $netProfit = $totalIncome - $totalExpenses;
        $closingBalance = $openingBalance + $cashInToday - $cashOutToday;
        $cashDifference = $caisse ? $caisse->variance_amount : 0.00;

        // Daily Transactions Timeline Stream
        $timelineQuery = FinancialLedger::with(['client', 'user'])
            ->whereBetween('entry_date', [$startDate, $endDate]);

        if (!empty($this->filterUser)) {
            $timelineQuery->where('user_id', $this->filterUser);
        }

        if (!empty($this->filterMethod)) {
            $timelineQuery->where('payment_method', $this->filterMethod);
        }

        $closings = DailyCashClosing::with(['closer', 'approver'])->latest('closing_date')->get();

        return view('livewire.admin.cloture-caisse', [
            'openingBalance' => $openingBalance,
            'cashInToday' => $cashInToday,
            'cashOutToday' => $cashOutToday,
            'transfersInToday' => $transfersInToday,
            'cardInToday' => $cardInToday,
            'chequesReceivedToday' => $chequesReceivedToday,
            'chequesDepositedToday' => $chequesDepositedToday,
            'pendingChequesSum' => $pendingChequesSum,
            'returnedChequesSum' => $returnedChequesSum,
            'expensesToday' => $expensesToday,
            'commissionsToday' => $commissionsToday,
            'refundsToday' => $refundsToday,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'closingBalance' => $closingBalance,
            'cashDifference' => $cashDifference,
            'bankBalance' => BankAccount::sum('current_balance'),
            'timeline' => $timelineQuery->latest('entry_date')->get(),
            'closings' => $closings,
            'users' => User::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
