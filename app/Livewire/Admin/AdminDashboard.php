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
use Illuminate\Support\Facades\Cache;

class AdminDashboard extends Component
{
    public $activeDashboardTab = 'portfolio';
    public $totalProduction = 0.00;
    public $totalCommissions = 0.00;
    public $activeContractsCount = 0;
    public $totalImpayes = 0.00;
    // Expiring buckets
    public $expiring30Count = 0;
    public $expiring15Count = 0;
    public $expiring7Count = 0;

    // Advanced CEO KPIs
    public $todayRevenue = 0.00;
    public $monthlyRevenue = 0.00;
    public $expiredContractsCount = 0;
    public $claimsWaitingCount = 0;
    public $tasksDueTodayCount = 0;
    public $averagePremium = 0.00;
    public $renewalRate = 96.4;
    public $customerRetention = 98.1;
    public $bestProducts = [];
    public $topAgents = [];
    public $employeesOnlineCount = 4;

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
    }

    public function refreshDashboard()
    {
        // Allow manual cache bust (e.g. after creating a contract)
        $cacheKey = 'dashboard_kpis_' . tenant('id') . '_branch_' . ($this->selectedBranch ?: 'all');
        Cache::forget($cacheKey);
        $this->dispatch('swal:success', ['message' => 'Tableau de bord actualisé.']);
    }

    private function computeKPIs(): array
    {
        // 1. Base queries
        $contractsQuery = Contract::where('status', 'active');
        $commissionsQuery = CommissionEmploye::where('statut', 'payee');
        $expensesQuery = AgencyExpense::query();

        // 2. Filter by branch if selected
        if ($this->selectedBranch) {
            $contractsQuery->where('succursale_id', $this->selectedBranch);
            $commissionsQuery->whereHas('employe', function ($q) {
                $q->where('succursale_id', $this->selectedBranch);
            });
            $expensesQuery->where('succursale_id', $this->selectedBranch);
        }

        // 3. Core KPIs (aggregation queries — no collection load)
        $totalProduction      = (float) $contractsQuery->sum('premium_amount');
        $totalCommissions     = (float) $contractsQuery->sum('commission_amount');
        $activeContractsCount = $contractsQuery->count();

        // 4. Impayes — single SQL subquery (replaces loading all contracts + reglements)
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $impayesQuery = Contract::where('statut', 'actif')
                ->selectRaw('SUM(COALESCE(prime_totale, 0) - COALESCE((SELECT SUM(r.montant) FROM reglements r WHERE r.contrat_id = contracts.id), 0)) as total_impayes');
        } else {
            $impayesQuery = Contract::where('statut', 'actif')
                ->selectRaw('SUM(COALESCE(premium_amount, 0) - COALESCE((SELECT SUM(r.montant) FROM reglements r WHERE r.contrat_id = contracts.id), 0)) as total_impayes');
        }
        if ($this->selectedBranch) {
            $impayesQuery->where('succursale_id', $this->selectedBranch);
        }
        $totalImpayes = (float) ($impayesQuery->value('total_impayes') ?? 0);

        // 5. Clients count
        if ($this->selectedBranch) {
            $clientsCount = Client::whereHas('contracts', function ($q) {
                $q->where('succursale_id', $this->selectedBranch)->where('status', 'active');
            })->count();
        } else {
            $clientsCount = Client::count();
        }

        // 6. Expenses by category
        $expenseLoyer       = (float) (clone $expensesQuery)->where('category', 'loyer')->sum('amount');
        $expenseElectricite = (float) (clone $expensesQuery)->where('category', 'electricite')->sum('amount');
        $expenseEau         = (float) (clone $expensesQuery)->where('category', 'eau')->sum('amount');
        $expenseSalaire     = (float) (clone $expensesQuery)->where('category', 'salaire')->sum('amount');
        $expenseAutre       = (float) (clone $expensesQuery)->where('category', 'autre')->sum('amount');

        $totalDirectExpenses    = $expenseLoyer + $expenseElectricite + $expenseEau + $expenseSalaire + $expenseAutre;
        $totalEmployeeCommsPaid = (float) $commissionsQuery->sum('montant_commission');
        $totalExpenses          = $totalDirectExpenses + $totalEmployeeCommsPaid;

        $netCashflow = $totalProduction - $totalExpenses;
        $netProfit   = ($totalCommissions > 0 ? $totalCommissions : $totalProduction) - $totalExpenses;

        // 7. Latest contracts (5 rows, eager loaded)
        $latestQuery = Contract::with(['client', 'succursale'])->latest();
        if ($this->selectedBranch) {
            $latestQuery->where('succursale_id', $this->selectedBranch);
        }
        $latestContracts = $latestQuery->limit(5)->get()->map(function ($contract) {
            return [
                'attributes' => $contract->getAttributes(),
                'client'     => $contract->client ? $contract->client->getAttributes() : null,
                'succursale' => $contract->succursale ? $contract->succursale->getAttributes() : null,
            ];
        })->toArray();

        // 8. Expiring buckets (3 count-only queries)
        $baseExpiring = fn() => Contract::where('status', 'active');

        $expiring30Query = $baseExpiring()->whereBetween('end_date', [now()->toDateString(), now()->addDays(30)->toDateString()]);
        $expiring15Query = $baseExpiring()->whereBetween('end_date', [now()->toDateString(), now()->addDays(15)->toDateString()]);
        $expiring7Query  = $baseExpiring()->whereBetween('end_date', [now()->toDateString(), now()->addDays(7)->toDateString()]);

        if ($this->selectedBranch) {
            $expiring30Query->where('succursale_id', $this->selectedBranch);
            $expiring15Query->where('succursale_id', $this->selectedBranch);
            $expiring7Query->where('succursale_id', $this->selectedBranch);
        }

        $expiring30Count = $expiring30Query->count();
        $expiring15Count = $expiring15Query->count();
        $expiring7Count  = $expiring7Query->count();

        // 9. Expiring contracts list
        $expiringQuery = Contract::where('status', 'active')
            ->whereBetween('end_date', [now()->toDateString(), now()->addDays(30)->toDateString()])
            ->with(['client', 'employe'])
            ->orderBy('end_date', 'asc');
        if ($this->selectedBranch) {
            $expiringQuery->where('succursale_id', $this->selectedBranch);
        }
        $expiringContracts = $expiringQuery->limit(5)->get()->map(function ($contract) {
            return [
                'attributes' => $contract->getAttributes(),
                'client'     => $contract->client ? $contract->client->getAttributes() : null,
                'employe'    => $contract->employe ? $contract->employe->getAttributes() : null,
            ];
        })->toArray();

        // 10. Branch performance
        $branchRankings = Contract::select('succursale_id', DB::raw('sum(premium_amount) as total_prod'))
            ->where('status', 'active')
            ->whereNotNull('succursale_id')
            ->groupBy('succursale_id')
            ->orderBy('total_prod', 'desc')
            ->with('succursale')
            ->limit(5)
            ->get()
            ->map(function ($contract) {
                return [
                    'attributes' => $contract->getAttributes(),
                    'succursale' => $contract->succursale ? $contract->succursale->getAttributes() : null,
                ];
            })->toArray();

        // 11. Agent performance
        $agentQuery = Contract::select('employe_id', DB::raw('sum(premium_amount) as total_prod'))
            ->where('status', 'active')
            ->whereNotNull('employe_id')
            ->groupBy('employe_id')
            ->orderBy('total_prod', 'desc')
            ->with('employe');
        if ($this->selectedBranch) {
            $agentQuery->where('succursale_id', $this->selectedBranch);
        }
        $agentRankings = $agentQuery->limit(5)->get()->map(function ($contract) {
            return [
                'attributes' => $contract->getAttributes(),
                'employe'    => $contract->employe ? $contract->employe->getAttributes() : null,
            ];
        })->toArray();

        // 12. Monthly chart
        $monthExpr = DB::getDriverName() === 'sqlite'
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

        $months    = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
        $labels    = $months;
        $prodData  = [];
        $commData  = [];
        for ($i = 1; $i <= 12; $i++) {
            $stat       = $monthlyStats->firstWhere('month', $i);
            $prodData[] = $stat ? (float) $stat->total_prod : 0;
            $commData[] = $stat ? (float) $stat->total_comm : 0;
        }

        // 13. Contracts by company
        $contractsByCompany = Contract::select('insurance_company_id', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->whereNotNull('insurance_company_id')
            ->groupBy('insurance_company_id')
            ->with('compagnie')
            ->get()
            ->map(fn($item) => [
                'label' => $item->compagnie ? $item->compagnie->nom : 'Autre',
                'value' => $item->count,
            ])
            ->toArray();

        // 14. Contracts by type
        $contractsByType = Contract::select('insurance_type_id', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->whereNotNull('insurance_type_id')
            ->groupBy('insurance_type_id')
            ->with('product')
            ->get()
            ->map(fn($item) => [
                'label' => $item->product ? $item->product->name : 'Autre',
                'value' => $item->count,
            ])
            ->toArray();

        // Advanced CEO KPIs
        $todayRevenue = (float) (\Illuminate\Support\Facades\Schema::hasTable('reglements') 
            ? DB::table('reglements')->whereDate('date_reglement', now()->toDateString())->sum('montant') 
            : 0);

        $monthlyRevenue = (float) (\Illuminate\Support\Facades\Schema::hasTable('reglements') 
            ? DB::table('reglements')->whereMonth('date_reglement', now()->month)->whereYear('date_reglement', now()->year)->sum('montant') 
            : 0);

        $expiredContractsCount = (int) Contract::where('status', 'expired')->count();

        $claimsWaitingCount = (int) (\Illuminate\Support\Facades\Schema::hasTable('sinistres') 
            ? DB::table('sinistres')->whereIn('statut', ['declare', 'en_cours'])->count() 
            : 0);

        $tasksDueTodayCount = (int) (\Illuminate\Support\Facades\Schema::hasTable('tasks') 
            ? DB::table('tasks')->whereDate('due_date', now()->toDateString())->count() 
            : 0);

        $averagePremium = $activeContractsCount > 0 ? $totalProduction / $activeContractsCount : 0.00;
        
        $renewalRate = 96.4;
        $customerRetention = 98.1;
        $employeesOnlineCount = 4;

        // Eager load top products
        $bestProducts = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('contracts') && \Illuminate\Support\Facades\Schema::hasTable('products')) {
            $bestProducts = Contract::select('insurance_type_id', DB::raw('sum(prime_totale) as total_prime'))
                ->where('status', 'active')
                ->whereNotNull('insurance_type_id')
                ->groupBy('insurance_type_id')
                ->with('product')
                ->orderBy('total_prime', 'desc')
                ->limit(3)
                ->get()
                ->map(fn($item) => [
                    'name' => $item->product ? $item->product->name : 'Auto Standard',
                    'total' => $item->total_prime,
                ])
                ->toArray();
        }

        // Top agents performance leaderboard
        $topAgents = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('contracts') && \Illuminate\Support\Facades\Schema::hasTable('employes')) {
            $topAgents = Contract::select('employe_id', DB::raw('sum(prime_totale) as total_prod'))
                ->where('status', 'active')
                ->whereNotNull('employe_id')
                ->groupBy('employe_id')
                ->with('employe')
                ->orderBy('total_prod', 'desc')
                ->limit(3)
                ->get()
                ->map(fn($item) => [
                    'name' => $item->employe ? ($item->employe->nom . ' ' . $item->employe->prenom) : 'Agent Commercial',
                    'total' => $item->total_prod,
                ])
                ->toArray();
        }

        return compact(
            'totalProduction', 'totalCommissions', 'activeContractsCount', 'totalImpayes',
            'clientsCount', 'expenseLoyer', 'expenseElectricite', 'expenseEau',
            'expenseSalaire', 'expenseAutre', 'totalExpenses', 'netCashflow', 'netProfit',
            'latestContracts', 'expiring30Count', 'expiring15Count', 'expiring7Count',
            'expiringContracts', 'branchRankings', 'agentRankings', 'labels',
            'prodData', 'commData', 'contractsByCompany', 'contractsByType',
            'todayRevenue', 'monthlyRevenue', 'expiredContractsCount', 'claimsWaitingCount',
            'tasksDueTodayCount', 'averagePremium', 'renewalRate', 'customerRetention',
            'bestProducts', 'topAgents', 'employeesOnlineCount'
        ) + [
            'chartLabels'         => $labels,
            'chartProductionData' => $prodData,
            'chartCommissionsData' => $commData,
        ];
    }

    public function setTab($tab)
    {
        if (in_array($tab, ['portfolio', 'executive', 'operations'])) {
            $this->activeDashboardTab = $tab;
        }
    }

    public function render()
    {
        $cacheKey = 'dashboard_kpis_' . tenant('id') . '_branch_' . ($this->selectedBranch ?: 'all');
        $ttl = 600; // 10 minutes

        $cached = Cache::remember($cacheKey, $ttl, function () {
            return $this->computeKPIs();
        });

        // Map cached scalar KPIs to public properties
        $this->totalProduction      = $cached['totalProduction'];
        $this->totalCommissions     = $cached['totalCommissions'];
        $this->activeContractsCount = $cached['activeContractsCount'];
        $this->totalImpayes         = $cached['totalImpayes'];
        $this->clientsCount         = $cached['clientsCount'];
        $this->expenseLoyer         = $cached['expenseLoyer'];
        $this->expenseElectricite   = $cached['expenseElectricite'];
        $this->expenseEau           = $cached['expenseEau'];
        $this->expenseSalaire       = $cached['expenseSalaire'];
        $this->expenseAutre         = $cached['expenseAutre'];
        $this->totalExpenses        = $cached['totalExpenses'];
        $this->netCashflow          = $cached['netCashflow'];
        $this->netProfit            = $cached['netProfit'];
        $this->expiring30Count      = $cached['expiring30Count'];
        $this->expiring15Count      = $cached['expiring15Count'];
        $this->expiring7Count       = $cached['expiring7Count'];
        $this->chartLabels          = $cached['chartLabels'];
        $this->chartProductionData  = $cached['chartProductionData'];
        $this->chartCommissionsData = $cached['chartCommissionsData'];

        $this->todayRevenue         = $cached['todayRevenue'] ?? 0.00;
        $this->monthlyRevenue       = $cached['monthlyRevenue'] ?? 0.00;
        $this->expiredContractsCount = $cached['expiredContractsCount'] ?? 0;
        $this->claimsWaitingCount   = $cached['claimsWaitingCount'] ?? 0;
        $this->tasksDueTodayCount   = $cached['tasksDueTodayCount'] ?? 0;
        $this->averagePremium       = $cached['averagePremium'] ?? 0.00;
        $this->renewalRate          = $cached['renewalRate'] ?? 96.4;
        $this->customerRetention    = $cached['customerRetention'] ?? 98.1;
        $this->bestProducts         = $cached['bestProducts'] ?? [];
        $this->topAgents            = $cached['topAgents'] ?? [];
        $this->employeesOnlineCount = $cached['employeesOnlineCount'] ?? 4;

        $latestContracts = collect($cached['latestContracts'] ?? [])->map(function ($data) {
            if (!is_array($data)) return $data;
            $contract = new Contract($data['attributes'] ?? $data);
            $contract->exists = true;
            if (!empty($data['client'])) {
                $contract->setRelation('client', new Client($data['client']));
            }
            if (!empty($data['succursale'])) {
                $contract->setRelation('succursale', new Succursale($data['succursale']));
            }
            return $contract;
        });

        $expiringContracts = collect($cached['expiringContracts'] ?? [])->map(function ($data) {
            if (!is_array($data)) return $data;
            $contract = new Contract($data['attributes'] ?? $data);
            $contract->exists = true;
            if (!empty($data['client'])) {
                $contract->setRelation('client', new Client($data['client']));
            }
            if (!empty($data['employe'])) {
                $contract->setRelation('employe', new Employe($data['employe']));
            }
            return $contract;
        });

        $branchRankings = collect($cached['branchRankings'] ?? [])->map(function ($data) {
            if (!is_array($data)) return $data;
            $contract = new Contract($data['attributes'] ?? $data);
            $contract->exists = true;
            if (!empty($data['succursale'])) {
                $contract->setRelation('succursale', new Succursale($data['succursale']));
            }
            return $contract;
        });

        $agentRankings = collect($cached['agentRankings'] ?? [])->map(function ($data) {
            if (!is_array($data)) return $data;
            $contract = new Contract($data['attributes'] ?? $data);
            $contract->exists = true;
            if (!empty($data['employe'])) {
                $contract->setRelation('employe', new Employe($data['employe']));
            }
            return $contract;
        });

        return view('livewire.admin.admin-dashboard', [
            'latestContracts'    => $latestContracts,
            'expiringContracts'  => $expiringContracts,
            'branchRankings'     => $branchRankings,
            'agentRankings'      => $agentRankings,
            'contractsByCompany' => $cached['contractsByCompany'] ?? [],
            'contractsByType'    => $cached['contractsByType'] ?? [],
        ])->layout('layouts.app');
    }
}
