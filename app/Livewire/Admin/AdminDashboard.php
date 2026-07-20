<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ContratAuto;
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

    // New Cashflow & Expense metrics
    public $selectedBranch = '';
    public $totalExpenses = 0.00;
    public $netCashflow = 0.00;
    public $clientsCount = 0;

    // Expense Category details
    public $expenseLoyer = 0.00;
    public $expenseElectricite = 0.00;
    public $expenseEau = 0.00;
    public $expenseSalaire = 0.00;
    public $expenseAutre = 0.00;

    // Sidebar/Selector List
    public $branchList = [];

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
        // 1. Base query scope
        $contractsQuery = ContratAuto::where('statut', 'actif');
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
        $this->totalProduction = (float)$contractsQuery->sum('prime_totale');
        $this->totalCommissions = (float)$commissionsQuery->sum('montant_commission');
        $this->activeContractsCount = $contractsQuery->count();

        // Solde à recouvrer (unpaid balance)
        $activeContracts = $contractsQuery->with('reglements')->get();
        $this->totalImpayes = $activeContracts->sum(function($c) {
            return $c->solde;
        });

        // 4. Clients Count
        // If a branch is selected, only count clients who have active contracts in that branch
        if ($this->selectedBranch) {
            $this->clientsCount = Client::whereHas('contrats', function($q) {
                $q->where('succursale_id', $this->selectedBranch)->where('statut', 'actif');
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

        // Total expenses = sum of all categories + internal commissions paid
        $totalDirectExpenses = $this->expenseLoyer + $this->expenseElectricite + $this->expenseEau + $this->expenseSalaire + $this->expenseAutre;
        $this->totalExpenses = $totalDirectExpenses + $this->totalCommissions;

        // Net Cashflow = Inflow (Total Production) - Outflow (Total Expenses)
        $this->netCashflow = $this->totalProduction - $this->totalExpenses;

        // 6. Latest Contracts
        $latestQuery = ContratAuto::with(['client', 'succursale'])->latest();
        if ($this->selectedBranch) {
            $latestQuery->where('succursale_id', $this->selectedBranch);
        }
        $this->latestContracts = $latestQuery->limit(5)->get();

        // 7. Expiring Contracts in next 30 days
        $expiringQuery = ContratAuto::where('statut', 'actif')
            ->whereBetween('date_echeance', [now()->toDateString(), now()->addDays(30)->toDateString()])
            ->with(['client', 'employe'])
            ->orderBy('date_echeance', 'asc');
        if ($this->selectedBranch) {
            $expiringQuery->where('succursale_id', $this->selectedBranch);
        }
        $this->expiringContracts = $expiringQuery->limit(5)->get();

        // 8. Branch Performance (only relevant if viewing globally)
        $this->branchRankings = ContratAuto::select('succursale_id', DB::raw('sum(prime_totale) as total_prod'))
            ->where('statut', 'actif')
            ->whereNotNull('succursale_id')
            ->groupBy('succursale_id')
            ->orderBy('total_prod', 'desc')
            ->with('succursale')
            ->limit(5)
            ->get();

        // 9. Agent Performance
        $agentQuery = ContratAuto::select('employe_id', DB::raw('sum(prime_totale) as total_prod'))
            ->where('statut', 'actif')
            ->whereNotNull('employe_id')
            ->groupBy('employe_id')
            ->orderBy('total_prod', 'desc')
            ->with('employe');
        if ($this->selectedBranch) {
            $agentQuery->where('succursale_id', $this->selectedBranch);
        }
        $this->agentRankings = $agentQuery->limit(5)->get();
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')
            ->layout('layouts.app');
    }
}
