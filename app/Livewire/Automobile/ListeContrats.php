<?php

namespace App\Livewire\Automobile;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ContratAuto;
use App\Models\Compagnie;

class ListeContrats extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCompagnie = '';
    public $filterStatut = '';

    // Active selected contract ID in the grid
    public $selectedContratId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCompagnie' => ['except' => ''],
        'filterStatut' => ['except' => ''],
    ];

    public function selectContrat($contratId)
    {
        $this->selectedContratId = $contratId;
    }

    public function getSelectedContrat()
    {
        if ($this->selectedContratId) {
            return ContratAuto::with(['client', 'vehicule', 'compagnie', 'apporteur', 'reglements'])->find($this->selectedContratId);
        }
        return null;
    }

    // Quick Actions
    public function resilierContrat()
    {
        if ($this->selectedContratId) {
            $contrat = ContratAuto::findOrFail($this->selectedContratId);
            app(\App\Services\ContractWorkflowService::class)->resilier($contrat, now()->toDateString());
            session()->flash('message', 'Le contrat a été résilié avec calcul du prorata temporis.');
        }
    }

    public function annulerContrat()
    {
        if ($this->selectedContratId) {
            $contrat = ContratAuto::findOrFail($this->selectedContratId);
            app(\App\Services\ContractWorkflowService::class)->annuler($contrat);
            session()->flash('message', 'Le contrat a été annulé rétroactivement avec remise à zéro des primes.');
        }
    }

    public function renouvelerContrat()
    {
        if ($this->selectedContratId) {
            $contrat = ContratAuto::findOrFail($this->selectedContratId);
            $newContrat = app(\App\Services\ContractWorkflowService::class)->renouveler($contrat);
            
            session()->flash('message', 'Contrat renouvelé avec succès (Nouveau Contrat: ' . $newContrat->numero_contrat . ')');
            return redirect()->route('automobile.edit', $newContrat->id);
        }
    }

    public function render()
    {
        $query = ContratAuto::with(['client', 'vehicule', 'compagnie', 'apporteur']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('numero_contrat', 'like', '%' . $this->search . '%')
                  ->orWhere('police', 'like', '%' . $this->search . '%')
                  ->orWhere('matricule', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function ($qc) {
                      $qc->where('nom', 'like', '%' . $this->search . '%')
                         ->orWhere('prenom', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if (!empty($this->filterCompagnie)) {
            $query->where('compagnie_id', $this->filterCompagnie);
        }

        if (!empty($this->filterStatut)) {
            $query->where('statut', $this->filterStatut);
        }

        $contrats = $query->latest()->paginate(10);
        $compagnies = Compagnie::all();

        return view('livewire.automobile.liste-contrats', [
            'contrats' => $contrats,
            'compagnies' => $compagnies,
            'selectedContrat' => $this->getSelectedContrat(),
        ])->layout('layouts.app');
    }
}
