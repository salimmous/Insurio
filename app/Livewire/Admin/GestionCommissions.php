<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\CommissionEmploye;
use App\Models\Employe;
use App\Services\CommissionService;
use Carbon\Carbon;

class GestionCommissions extends Component
{
    public $commissions = [];
    public $employes = [];
    public $periods = [];

    // Filters
    public $selectedEmployeId = '';
    public $selectedPeriod = '';
    public $selectedStatus = '';

    public function mount()
    {
        $user = auth()->user();
        if (!$user || (!$user->hasRole('agency-admin') && !$user->hasRole('responsable-succursale') && !$user->hasRole('comptable'))) {
            abort(403, 'Accès non autorisé.');
        }

        $this->loadFilters();
        $this->loadCommissions();
    }

    public function loadFilters()
    {
        $this->employes = Employe::all();
        
        // Get unique periods from DB
        $this->periods = CommissionEmploye::select('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode')
            ->toArray();
    }

    public function loadCommissions()
    {
        $query = CommissionEmploye::with(['employe', 'contrat.client']);

        if ($this->selectedEmployeId) {
            $query->where('employe_id', $this->selectedEmployeId);
        }

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
        if (in_array($propertyName, ['selectedEmployeId', 'selectedPeriod', 'selectedStatus'])) {
            $this->loadCommissions();
        }
    }

    public function validerCommission($id)
    {
        if (!auth()->user()->hasRole('agency-admin')) {
            $this->dispatch('swal:error', ['message' => 'Action non autorisée. Seul l\'administrateur du cabinet peut valider les commissions.']);
            return;
        }

        $commission = CommissionEmploye::findOrFail($id);
        $currentEmploye = auth()->user()->employe;
        
        if (!$currentEmploye) {
            $this->dispatch('swal:error', ['message' => 'Votre compte utilisateur n\'est pas lié à un profil employé.']);
            return;
        }

        app(CommissionService::class)->valider($commission, $currentEmploye->id);
        $this->loadCommissions();
        $this->dispatch('swal:success', ['message' => 'Commission validée avec succès.']);
    }

    public function payerCommission($id)
    {
        if (!auth()->user()->hasRole('agency-admin')) {
            $this->dispatch('swal:error', ['message' => 'Action non autorisée. Seul l\'administrateur du cabinet peut enregistrer les paiements.']);
            return;
        }

        $commission = CommissionEmploye::findOrFail($id);
        app(CommissionService::class)->payer($commission);
        $this->loadCommissions();
        $this->dispatch('swal:success', ['message' => 'Commission marquée comme payée.']);
    }

    public function render()
    {
        return view('livewire.admin.gestion-commissions')
            ->layout('layouts.app');
    }
}
