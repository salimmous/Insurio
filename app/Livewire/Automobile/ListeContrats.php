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

    // Reglements Modal properties
    public $isReglementsModalOpen = false;
    public $reglementMontant = '';
    public $reglementDate = '';
    public $reglementMode = 'especes';
    public $reglementReference = '';

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

    public function renouvelerContrat()
    {
        if ($this->selectedContratId) {
            $contrat = ContratAuto::findOrFail($this->selectedContratId);
            $newContrat = app(\App\Services\ContractWorkflowService::class)->renouveler($contrat);
            
            session()->flash('message', 'Contrat renouvelé avec succès (Nouveau Contrat: ' . $newContrat->numero_contrat . ')');
            return redirect()->route('automobile.edit', $newContrat->id);
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

    public function relancerParEmail()
    {
        if ($this->selectedContratId) {
            $contrat = ContratAuto::with('client')->findOrFail($this->selectedContratId);
            $client = $contrat->client;

            if (!$client || empty($client->email)) {
                session()->flash('error', "Le client n'a pas d'adresse e-mail configurée.");
                return;
            }

            $mailHost = \App\Models\Setting::get('mail_host');
            if (empty($mailHost)) {
                session()->flash('error', "Le serveur SMTP n'est pas configuré. Veuillez aller dans la configuration de l'agence pour l'activer.");
                return;
            }

            try {
                $tenantName = (function_exists('tenant') && tenant()) ? tenant('name') : 'Insurio';
                $agencyName = \App\Models\Setting::get('agency_name', $tenantName);
                $agencyPhone = \App\Models\Setting::get('agency_phone', '+212 5 22 00 00 00');

                \Illuminate\Support\Facades\Mail::to($client->email)
                    ->send(new \App\Mail\RenewalReminderMail($client, $contrat, $agencyName, $agencyPhone));

                session()->flash('message', "E-mail de rappel envoyé avec succès à {$client->email} !");
            } catch (\Throwable $e) {
                session()->flash('error', "Échec de l'envoi de l'e-mail : " . $e->getMessage());
            }
        }
    }

    public function openReglementsModal()
    {
        if ($this->selectedContratId) {
            $contrat = ContratAuto::findOrFail($this->selectedContratId);
            $this->reglementMontant = $contrat->solde;
            $this->reglementDate = now()->toDateString();
            $this->reglementMode = 'especes';
            $this->reglementReference = '';
            $this->isReglementsModalOpen = true;
        }
    }

    public function closeReglementsModal()
    {
        $this->isReglementsModalOpen = false;
    }

    public function addReglement()
    {
        if (!$this->selectedContratId) return;

        $contrat = ContratAuto::findOrFail($this->selectedContratId);

        $this->validate([
            'reglementMontant' => 'required|numeric|min:0.01',
            'reglementDate' => 'required|date',
            'reglementMode' => 'required|in:especes,cheque,virement,carte',
            'reglementReference' => 'nullable|string|max:255',
        ]);

        \App\Models\Reglement::create([
            'contrat_id' => $contrat->id,
            'montant' => $this->reglementMontant,
            'date_reglement' => $this->reglementDate,
            'mode_reglement' => $this->reglementMode,
            'reference_paiement' => $this->reglementReference,
        ]);

        $this->closeReglementsModal();
        $this->dispatch('swal:success', ['message' => 'Règlement enregistré avec succès.']);
    }

    public function deleteReglement($id)
    {
        $reglement = \App\Models\Reglement::findOrFail($id);
        $reglement->delete();
        $this->dispatch('swal:success', ['message' => 'Règlement supprimé avec succès.']);
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
            if ($this->filterStatut === 'expiring_7_days') {
                $query->where('statut', 'actif')
                      ->whereBetween('date_echeance', [now()->toDateString(), now()->addDays(7)->toDateString()]);
            } else {
                $query->where('statut', $this->filterStatut);
            }
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
