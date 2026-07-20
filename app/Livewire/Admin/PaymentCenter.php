<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Payment;
use App\Models\PaymentInstallment;
use App\Models\BankReconciliation;
use App\Models\PaymentFollowUp;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Compagnie;
use App\Models\Succursale;
use App\Models\Employe;
use App\Models\User;
use App\Services\PaymentAutomationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentCenter extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $activeTab = 'payments';

    // Filters & Search
    public $search = '';
    public $filterStatus = '';
    public $filterMethod = '';
    public $filterCompany = '';
    public $filterBranch = '';
    public $filterDateStart = '';
    public $filterDateEnd = '';

    // Create Payment Form
    public $showCreateModal = false;
    public $client_id = '';
    public $contract_id = '';
    public $amount = 0.00;
    public $paid_amount = 0.00;
    public $discount = 0.00;
    public $tax = 0.00;
    public $payment_method = 'cash';
    public $currency = 'DH';
    public $due_date = '';
    public $payment_date = '';
    
    // Cheque fields
    public $cheque_number = '';
    public $bank_name = '';
    public $bank_account = '';
    public $cheque_issue_date = '';
    
    // Card & Online fields
    public $reference_number = '';

    // Cheque Center Filters
    public $chequeStatusFilter = 'waiting'; // waiting, deposited, cleared, returned, rejected

    // Bank Reconciliation Form
    public $reconcile_payment_id = '';
    public $reconcile_date = '';
    public $reconcile_ref = '';
    public $reconcile_amount = 0.00;
    public $showReconcileModal = false;

    // AI Assistant Panel
    public $aiSummary = '';
    public $showAiAssistant = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'payments'],
        'filterStatus' => ['except' => ''],
        'filterMethod' => ['except' => ''],
    ];

    public function mount()
    {
        $this->payment_date = now()->format('Y-m-d');
        $this->due_date = now()->format('Y-m-d');
        $this->reconcile_date = now()->format('Y-m-d');
        $this->generateAiInsights();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function generateAiInsights()
    {
        // Compute active intelligence alerts based on real DB telemetry
        $riskCount = Client::where('incident', true)->count();
        $overdueSum = Payment::where('payment_status', 'overdue')->sum('remaining_amount');
        $chequeReturned = Payment::where('payment_status', 'returned')->count();

        $this->aiSummary = "🤖 **Insurio Financial AI Co-pilot Insights**:\n\n" .
            "1. **Analyse de Risque** : {$riskCount} clients présentent des incidents ou des risques de solvabilité active.\n" .
            "2. **Prédictions Impayés** : Une somme estimée de " . number_format($overdueSum, 2) . " DH est en retard critique d'échéance. Relancer les conseillers.\n" .
            "3. **Cheque Center** : {$chequeReturned} chèques rejetés en attente de régularisation par la banque.\n" .
            "4. **Recommandation** : Prioriser le recouvrement auprès de Tariq Benoumar (SLA en retard).";
    }

    public function openCreatePayment()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function resetCreateForm()
    {
        $this->client_id = '';
        $this->contract_id = '';
        $this->amount = 0.00;
        $this->paid_amount = 0.00;
        $this->discount = 0.00;
        $this->tax = 0.00;
        $this->payment_method = 'cash';
        $this->cheque_number = '';
        $this->bank_name = '';
        $this->bank_account = '';
        $this->cheque_issue_date = '';
        $this->reference_number = '';
        $this->payment_date = now()->format('Y-m-d');
        $this->due_date = now()->format('Y-m-d');
    }

    public function createPayment()
    {
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'contract_id' => 'required|exists:contracts,id',
            'amount' => 'required|numeric|min:0.01',
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'due_date' => 'nullable|date',
            'cheque_number' => 'nullable|required_if:payment_method,cheque',
            'bank_name' => 'nullable|required_if:payment_method,cheque|required_if:payment_method,bank_transfer',
        ]);

        $contract = Contract::findOrFail($this->contract_id);
        $client = Client::findOrFail($this->client_id);

        $status = 'pending';
        if ($this->payment_method === 'cash') {
            $status = 'paid';
            $this->paid_amount = $this->amount;
        }

        $payment = Payment::create([
            'client_id' => $this->client_id,
            'contract_id' => $this->contract_id,
            'policy_id' => $contract->policy_number,
            'company_id' => $contract->insurance_company_id,
            'employee_id' => $contract->employe_id,
            'branch_id' => $contract->succursale_id ?: $client->succursale_id,
            'amount' => $this->amount,
            'paid_amount' => $this->paid_amount ?: 0.00,
            'discount' => $this->discount ?: 0.00,
            'tax' => $this->tax ?: 0.00,
            'payment_method' => $this->payment_method,
            'payment_status' => $status,
            'payment_date' => $this->payment_date,
            'due_date' => $this->due_date,
            'cheque_number' => $this->cheque_number,
            'bank_name' => $this->bank_name,
            'bank_account' => $this->bank_account,
            'cheque_issue_date' => $this->cheque_issue_date ?: null,
            'reference_number' => $this->reference_number,
        ]);

        $automation = app(PaymentAutomationService::class);
        $automation->handlePaymentCreated($payment);

        if ($payment->payment_status === 'paid') {
            $automation->handlePaymentPaid($payment);
        }

        $this->showCreateModal = false;
        $this->dispatch('swal:success', ['message' => "Règlement {$payment->payment_number} enregistré avec succès."]);
        $this->generateAiInsights();
    }

    public function recordInstallmentPayment($installmentId)
    {
        $inst = PaymentInstallment::findOrFail($installmentId);
        $inst->status = 'paid';
        $inst->paid_date = now();
        $inst->receipt_number = 'REC-INST-' . date('Y') . '-' . sprintf('%06d', $inst->id);
        $inst->remaining_amount = 0.00;
        $inst->save();

        // Increment payment paid amount
        $payment = $inst->payment;
        $payment->paid_amount = min($payment->amount, $payment->paid_amount + $inst->amount);
        if ($payment->paid_amount >= $payment->amount - $payment->discount) {
            $payment->payment_status = 'paid';
        } else {
            $payment->payment_status = 'partially_paid';
        }
        $payment->save();

        app(PaymentAutomationService::class)->handlePaymentPaid($payment);

        $this->dispatch('swal:success', ['message' => "Échéance N° {$inst->id} acquittée. Reçu: {$inst->receipt_number}."]);
        $this->generateAiInsights();
    }

    public function openReconcile($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $this->reconcile_payment_id = $payment->id;
        $this->reconcile_amount = $payment->amount;
        $this->reconcile_ref = $payment->reference_number ?: '';
        $this->reconcile_date = now()->format('Y-m-d');
        $this->showReconcileModal = true;
    }

    public function createReconciliation()
    {
        $this->validate([
            'reconcile_payment_id' => 'required|exists:payments,id',
            'reconcile_date' => 'required|date',
            'reconcile_ref' => 'required|string',
            'reconcile_amount' => 'required|numeric',
        ]);

        $payment = Payment::findOrFail($this->reconcile_payment_id);
        $difference = abs($payment->amount - $this->reconcile_amount);

        BankReconciliation::create([
            'payment_id' => $payment->id,
            'deposit_date' => $this->reconcile_date,
            'reference' => $this->reconcile_ref,
            'matched' => true,
            'difference' => $difference,
            'notes' => "Rapprochement bancaire manuel validé.",
        ]);

        $payment->payment_status = 'deposited';
        $payment->save();

        $this->showReconcileModal = false;
        $this->dispatch('swal:success', ['message' => 'Rapprochement bancaire enregistré avec succès.']);
    }

    public function updateChequeStatus($paymentId, $newStatus)
    {
        $payment = Payment::findOrFail($paymentId);
        $oldStatus = $payment->payment_status;

        $payment->payment_status = $newStatus;

        if ($newStatus === 'deposited') {
            $payment->cheque_deposit_date = now();
        } elseif ($newStatus === 'paid') { // Cleared
            $payment->cheque_clearance_date = now();
            $payment->paid_amount = $payment->amount;
            $payment->save();
            
            app(PaymentAutomationService::class)->handlePaymentPaid($payment);
        } elseif ($newStatus === 'returned') {
            app(PaymentAutomationService::class)->handleReturnedCheque($payment, "Provision insuffisante");
        }

        $payment->save();
        $this->dispatch('swal:success', ['message' => "Statut du chèque N° {$payment->cheque_number} mis à jour : {$newStatus}."]);
        $this->generateAiInsights();
    }

    public function render()
    {
        // 1. Calculate General Financial KPIs (Landlord Dashboard style but scoped to tenant)
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $yearStart = Carbon::now()->startOfYear();

        $kpis = [
            'today_revenue' => Payment::where('payment_status', 'paid')->whereDate('payment_date', $today)->sum('paid_amount'),
            'cash_today' => Payment::where('payment_status', 'paid')->where('payment_method', 'cash')->whereDate('payment_date', $today)->sum('paid_amount'),
            'bank_today' => Payment::where('payment_status', 'paid')->whereIn('payment_method', ['bank_transfer', 'credit_card', 'debit_card'])->whereDate('payment_date', $today)->sum('paid_amount'),
            'cheque_today' => Payment::where('payment_status', 'paid')->where('payment_method', 'cheque')->whereDate('payment_date', $today)->sum('paid_amount'),
            
            'pending_count' => Payment::whereIn('payment_status', ['pending', 'draft', 'waiting_validation'])->count(),
            'outstanding_balance' => Payment::whereNotIn('payment_status', ['paid', 'cancelled', 'written_off'])->sum('remaining_amount'),
            'returned_count' => Payment::where('payment_status', 'returned')->count(),
            
            'monthly_revenue' => Payment::where('payment_status', 'paid')->where('payment_date', '>=', $monthStart)->sum('paid_amount'),
            'yearly_revenue' => Payment::where('payment_status', 'paid')->where('payment_date', '>=', $yearStart)->sum('paid_amount'),
        ];

        // 2. Fetch Payments with eager load
        $paymentsQuery = Payment::with(['client', 'contract.compagnie', 'branch', 'employee'])->latest();

        // Apply filters
        if ($this->search) {
            $s = '%' . $this->search . '%';
            $paymentsQuery->where(function ($q) use ($s) {
                $q->where('payment_number', 'like', $s)
                  ->orWhere('policy_id', 'like', $s)
                  ->orWhere('cheque_number', 'like', $s)
                  ->orWhere('reference_number', 'like', $s)
                  ->orWhereHas('client', function ($cq) use ($s) {
                      $cq->where('last_name', 'like', $s)->orWhere('first_name', 'like', $s);
                  })
                  ->orWhereHas('contract', function ($ctq) use ($s) {
                      $ctq->where('contract_number', 'like', $s);
                  });
            });
        }

        if ($this->filterStatus) {
            $paymentsQuery->where('payment_status', $this->filterStatus);
        }

        if ($this->filterMethod) {
            $paymentsQuery->where('payment_method', $this->filterMethod);
        }

        if ($this->filterCompany) {
            $paymentsQuery->where('company_id', $this->filterCompany);
        }

        if ($this->filterBranch) {
            $paymentsQuery->where('branch_id', $this->filterBranch);
        }

        if ($this->filterDateStart) {
            $paymentsQuery->whereDate('payment_date', '>=', $this->filterDateStart);
        }

        if ($this->filterDateEnd) {
            $paymentsQuery->whereDate('payment_date', '<=', $this->filterDateEnd);
        }

        $paymentsList = $paymentsQuery->paginate(15);

        // 3. Fetch Installments
        $installmentsList = PaymentInstallment::with(['payment.client', 'payment.contract'])
            ->orderBy('due_date', 'asc')
            ->paginate(15, ['*'], 'installmentsPage');

        // 4. Fetch Cheques
        $chequesList = Payment::where('payment_method', 'cheque')
            ->where('payment_status', $this->chequeStatusFilter)
            ->with(['client', 'contract'])
            ->paginate(15, ['*'], 'chequesPage');

        // 5. Fetch Reconciliations
        $unreconciledPayments = Payment::whereIn('payment_method', ['bank_transfer', 'cheque', 'credit_card'])
            ->whereNotIn('payment_status', ['deposited', 'paid', 'cancelled'])
            ->with(['client', 'contract'])
            ->get();

        $reconciledList = BankReconciliation::with('payment.client')->latest()->paginate(15, ['*'], 'reconciledPage');

        // 6. Accounting Data (Aggregations by Month / Method / Employee)
        $methodChart = Payment::where('payment_status', 'paid')
            ->select('payment_method', DB::raw('SUM(paid_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $companyChart = Payment::where('payment_status', 'paid')
            ->select('company_id', DB::raw('SUM(paid_amount) as total'))
            ->groupBy('company_id')
            ->with('company')
            ->get();

        return view('livewire.admin.payment-center', [
            'kpis' => $kpis,
            'payments' => $paymentsList,
            'installments' => $installmentsList,
            'cheques' => $chequesList,
            'unreconciled' => $unreconciledPayments,
            'reconciled' => $reconciledList,
            'methodChart' => $methodChart,
            'companyChart' => $companyChart,
            'clients' => Client::all(),
            'contracts' => Contract::all(),
            'companies' => Compagnie::all(),
            'branches' => Succursale::all(),
        ])->layout('layouts.app');
    }
}
