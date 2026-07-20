<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Contract;
use App\Models\Succursale;
use App\Models\Employe;
use App\Models\CommissionEmploye;
use App\Models\AgencyExpense;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
{
    public $totalProduction = 0.00;
    public $totalCommissions = 0.00;
    public $activeContractsCount = 0;
    public $totalImpayes = 0.00;
    public $latestContracts = [];
    public $branchRankings = [];
    public $agentRankings = [];
    public $expiringContracts = [];

    // Expiring buckets
    public $expiring30Count = 0;
    public $expiring15Count = 0;
    public $expiring7Count = 0;

    // Financials
    public $selectedBranch = '';
    public $totalExpenses = 0.00;
    public $netCashflow = 0.00;
    public $netProfit = 0.00;
    public $clientsCount = 0;

    // Expense Category details
    public $expenseLoyer = 0.00;
    public $expenseElectricite = 0.00;
    public $expenseEau = 0.00;
    public $expenseSalaire = 0.00;
    public $expenseAutre = 0.00;

    // Sidebar/Selector List
    public $branchList = [];

    // Chart Data
    public $chartLabels = [];
    public $chartProductionData = [];
    public $chartCommissionsData = [];

    public function mount()
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        if ($user->hasRole('agent-commercial')) {
            return redirect()->route('automobile.index');
        }

        if (!$user->hasRole('agency-admin') && !$user->hasRole('responsable-succursale') && !$user->hasRole('comptable') && !$user->hasRole('consultation')) {
            abort(403, 'Accès non autorisé.');
        }

        // If the user is a responsable-succursale, restrict them to their branch only!
        if ($user->hasRole('responsable-succursale')) {
            $employe = Employe::where('user_id', $user->id)->first();
            if ($employe) {
                $this->selectedBranch = $employe->succursale_id;
            }
        }

        $this->branchList = Succursale::all();
        $this->loadKPIs();
    }

    public function updatedSelectedBranch()
    {
        $this->loadKPIs();
    }

    public function loadKPIs()
    {
        // 1. Base queries
        $contractsQuery = Contract::where('status', 'active');
        $commissionsQuery = CommissionEmploye::where('statut', 'payee');
        $expensesQuery = AgencyExpense::query();

        // 2. Filter by branch if selected
        if ($this->selectedBranch) {
            $contractsQuery->where('succursale_id', $this->selectedBranch);
            $commissionsQuery->whereHas('employe', function($q) {
                $q->where('succursale_id', $this->selectedBranch);
            });
            $expensesQuery->where('succursale_id', $this->selectedBranch);
        }

        // 3. Core KPIs
        $this->totalProduction = (float)$contractsQuery->sum('premium_amount');
        $this->totalCommissions = (float)$contractsQuery->sum('commission_amount');
        $this->activeContractsCount = $contractsQuery->count();

        // Solde à recouvrer (unpaid balance)
        $activeContracts = $contractsQuery->with('reglements')->get();
        $this->totalImpayes = $activeContracts->sum(function($c) {
            return $c->solde;
        });

        // 4. Clients Count
        if ($this->selectedBranch) {
            $this->clientsCount = Client::whereHas('contracts', function($q) {
                $q->where('succursale_id', $this->selectedBranch)->where('status', 'active');
            })->count();
        } else {
            $this->clientsCount = Client::count();
        }

        // 5. Calculate Expenses/Charges
        $this->expenseLoyer = (float)$expensesQuery->clone()->where('category', 'loyer')->sum('amount');
        $this->expenseElectricite = (float)$expensesQuery->clone()->where('category', 'electricite')->sum('amount');
        $this->expenseEau = (float)$expensesQuery->clone()->where('category', 'eau')->sum('amount');
        $this->expenseSalaire = (float)$expensesQuery->clone()->where('category', 'salaire')->sum('amount');
        $this->expenseAutre = (float)$expensesQuery->clone()->where('category', 'autre')->sum('amount');

        // Total expenses = sum of all categories + paid internal commissions
        $totalDirectExpenses = $this->expenseLoyer + $this->expenseElectricite + $this->expenseEau + $this->expenseSalaire + $this->expenseAutre;
        $totalEmployeeCommsPaid = (float)$commissionsQuery->sum('montant_commission');
        $this->totalExpenses = $totalDirectExpenses + $totalEmployeeCommsPaid;

        // Cashflow & Profits
        $this->netCashflow = $this->totalProduction - $this->totalExpenses;
        $this->netProfit = ($this->totalCommissions > 0 ? $this->totalCommissions : $this->totalProduction) - $this->totalExpenses;

        // 6. Latest Contracts
        $latestQuery = Contract::with(['client', 'succursale'])->latest();
        if ($this->selectedBranch) {
            $latestQuery->where('succursale_id', $this->selectedBranch);
        }
        $this->latestContracts = $latestQuery->limit(5)->get();

        // 7. Expiring Contracts Buckets
        $expiring30Query = Contract::where('status', 'active')
            ->whereBetween('end_date', [now()->toDateString(), now()->addDays(30)->toDateString()]);
        $expiring15Query = Contract::where('status', 'active')
            ->whereBetween('end_date', [now()->toDateString(), now()->addDays(15)->toDateString()]);
            $expiring7Query = Contract::where('status', 'active')
            ->whereBetween('end_date', [now()->toDateString(), now()->addDays(7)->toDateString()]);

        if ($this->selectedBranch) {
            $expiring30Query->where('succursale_id', $this->selectedBranch);
            $expiring15Query->where('succursale_id', $this->selectedBranch);
            $expiring7Query->where('succursale_id', $this->selectedBranch);
        }

        $this->expiring30Count = $expiring30Query->count();
        $this->expiring15Count = $expiring15Query->count();
        $this->expiring7Count = $expiring7Query->count();

        // 7b. Expiring Contracts List (Top 5)
        $expiringQuery = Contract::where('status', 'active')
            ->whereBetween('end_date', [now()->toDateString(), now()->addDays(30)->toDateString()])
            ->with(['client', 'employe'])
            ->orderBy('end_date', 'asc');
        if ($this->selectedBranch) {
            $expiringQuery->where('succursale_id', $this->selectedBranch);
        }
        $this->expiringContracts = $expiringQuery->limit(5)->get();

        // 8. Branch Performance
        $this->branchRankings = Contract::select('succursale_id', DB::raw('sum(premium_amount) as total_prod'))
            ->where('status', 'active')
            ->whereNotNull('succursale_id')
            ->groupBy('succursale_id')
            ->orderBy('total_prod', 'desc')
            ->with('succursale')
            ->limit(5)
            ->get();

        // 9. Agent Performance
        $agentQuery = Contract::select('employe_id', DB::raw('sum(premium_amount) as total_prod'))
            ->where('status', 'active')
            ->whereNotNull('employe_id')
            ->groupBy('employe_id')
            ->orderBy('total_prod', 'desc')
            ->with('employe');
        if ($this->selectedBranch) {
            $agentQuery->where('succursale_id', $this->selectedBranch);
        }
        $this->agentRankings = $agentQuery->limit(5)->get();

        // 10. Generate dynamic chart data for current year
        $monthExpr = DB::connection()->getDriverName() === 'sqlite'
            ? 'CAST(strftime("%m", start_date) AS INTEGER)'
            : 'MONTH(start_date)';

        $monthlyStats = Contract::select(
            DB::raw("{$monthExpr} as month"),
            DB::raw('SUM(premium_amount) as total_prod'),
            DB::raw('SUM(commission_amount) as total_comm')
        )
        ->whereYear('start_date', now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $labels = [];
        $prodData = [];
        $commData = [];
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $months[$i - 1];
            $stat = $monthlyStats->firstWhere('month', $i);
            $prodData[] = $stat ? (float)$stat->total_prod : 0;
            $commData[] = $stat ? (float)$stat->total_comm : 0;
        }

        $this->chartLabels = $labels;
        $this->chartProductionData = $prodData;
        $this->chartCommissionsData = $commData;
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')
            ->layout('layouts.app');
    }
}
