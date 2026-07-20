<?php

namespace App\Livewire\Agent;

use Livewire\Component;
use App\Models\CommissionEmploye;
use App\Models\Employe;

class MesCommissions extends Component
{
    public $commissions = [];
    public $periods = [];
    public $employe = null;

    // Filters
    public $selectedPeriod = '';
    public $selectedStatus = '';

    public function mount()
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Accès non autorisé.');
        }

        // Get this user's employee record
        $this->employe = Employe::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->first();

        if (!$this->employe) {
            abort(403, 'Votre compte utilisateur n\'est pas configuré en tant que collaborateur.');
        }

        $this->loadFilters();
        $this->loadCommissions();
    }

    public function loadFilters()
    {
        // Get unique periods for this specific employee
        $this->periods = CommissionEmploye::where('employe_id', $this->employe->id)
            ->select('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode')
            ->toArray();
    }

    public function loadCommissions()
    {
        // Global scopes will automatically scope by employee_id because of SuccursaleScope
        // but let's be explicit to be 100% correct
        $query = CommissionEmploye::with(['contrat.client'])
            ->where('employe_id', $this->employe->id);

        if ($this->selectedPeriod) {
            $query->where('periode', $this->selectedPeriod);
        }

        if ($this->selectedStatus) {
            $query->where('statut', $this->selectedStatus);
        }

        $this->commissions = $query->latest()->get();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['selectedPeriod', 'selectedStatus'])) {
            $this->loadCommissions();
        }
    }

    public function render()
    {
        return view('livewire.agent.mes-commissions')
            ->layout('layouts.app');
    }
}
