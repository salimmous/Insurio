<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Contract;
use App\Models\Succursale;
use App\Models\Compagnie;
use App\Models\Sinistre;
use Illuminate\Support\Facades\DB;

class RapportsBI extends Component
{
    public $selectedYear;
    public $selectedSuccursaleId = 'all';

    public function mount()
    {
        $this->selectedYear = (int)date('Y');
    }

    public function render()
    {
        $succursales = Succursale::all();

        // 1. Succursales Performance Comparison
        $branchPerformance = Succursale::withCount(['contrats as total_contracts' => function($q) {
            $q->whereYear('date_effet', $this->selectedYear);
        }])->get()->map(function($branch) {
            $primeTotal = Contract::where('succursale_id', $branch->id)
                ->whereYear('start_date', $this->selectedYear)
                ->sum('premium_amount');

            $sinistresCount = Sinistre::whereHas('contrat', function($q) use ($branch) {
                $q->where('succursale_id', $branch->id);
            })->whereYear('date_survenance', $this->selectedYear)->count();

            return [
                'name' => $branch->nom,
                'code' => $branch->code,
                'city' => $branch->ville,
                'contracts' => $branch->total_contracts,
                'prime_volume' => $primeTotal,
                'sinistres' => $sinistresCount,
            ];
        });

        // 2. Insurer Market Share
        $insurerStats = Compagnie::withCount(['contracts as count' => function($q) {
            $q->whereYear('start_date', $this->selectedYear);
        }])->get()->map(function($cie) {
            $volume = Contract::where('insurance_company_id', $cie->id)
                ->whereYear('start_date', $this->selectedYear)
                ->sum('premium_amount');
            return [
                'nom' => $cie->nom,
                'count' => $cie->count,
                'volume' => $volume,
            ];
        });

        // 3. Claims ratio summary
        $totalPrimes = Contract::whereYear('start_date', $this->selectedYear)->sum('premium_amount');
        $totalSinistres = Sinistre::whereYear('date_survenance', $this->selectedYear)->count();

        return view('livewire.admin.rapports-bi', [
            'succursales' => $succursales,
            'branchPerformance' => $branchPerformance,
            'insurerStats' => $insurerStats,
            'totalPrimes' => $totalPrimes,
            'totalSinistres' => $totalSinistres,
        ])->layout('layouts.app');
    }
}
