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
    public $reglementLines = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCompagnie' => ['except' => ''],
        'filterStatut' => ['except' => ''],
    ];

    public function mount()
    {
        if (request()->routeIs('admin.renouvellements') || request()->has('renouvellements')) {
            if (empty($this->filterStatut)) {
                $this->filterStatut = 'expiring_10_days';
            }
        }
    }

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

    public function openReglementsModal($contratId = null)
    {
        if ($contratId) {
            $this->selectedContratId = $contratId;
        }
        if ($this->selectedContratId) {
            $contrat = ContratAuto::findOrFail($this->selectedContratId);
            $initialMontant = $contrat->solde > 0 ? $contrat->solde : 0;

            $this->reglementMontant = $initialMontant;
            $this->reglementDate = now()->toDateString();
            $this->reglementMode = 'especes';
            $this->reglementReference = '';

            $this->reglementLines = [
                [
                    'montant' => $initialMontant > 0 ? $initialMontant : '',
                    'date' => now()->toDateString(),
                    'mode' => 'especes',
                    'reference' => '',
                ]
            ];

            $this->isReglementsModalOpen = true;
        }
    }

    public function addReglementLine()
    {
        $this->reglementLines[] = [
            'montant' => '',
            'date' => now()->toDateString(),
            'mode' => 'especes',
            'reference' => '',
        ];
    }

    public function removeReglementLine($index)
    {
        if (count($this->reglementLines) > 1) {
            unset($this->reglementLines[$index]);
            $this->reglementLines = array_values($this->reglementLines);
        }
    }

    public function closeReglementsModal()
    {
        $this->isReglementsModalOpen = false;
        $this->reglementLines = [];
    }

    public function addReglement()
    {
        if (!$this->selectedContratId) return;

        $contrat = ContratAuto::findOrFail($this->selectedContratId);

        if (!empty($this->reglementLines)) {
            $this->validate([
                'reglementLines' => 'required|array|min:1',
                'reglementLines.*.montant' => 'required|numeric|min:0.01',
                'reglementLines.*.date' => 'required|date',
                'reglementLines.*.mode' => 'required|in:especes,cheque,virement,carte',
                'reglementLines.*.reference' => 'nullable|string|max:255',
            ], [
                'reglementLines.*.montant.required' => 'Le montant est obligatoire.',
                'reglementLines.*.montant.min' => 'Le montant doit être supérieur à 0.',
            ]);

            $createdCount = 0;
            foreach ($this->reglementLines as $line) {
                \App\Models\Reglement::create([
                    'contrat_id' => $contrat->id,
                    'montant' => $line['montant'],
                    'date_reglement' => $line['date'],
                    'mode_reglement' => $line['mode'],
                    'reference_paiement' => $line['reference'] ?? null,
                ]);
                $createdCount++;
            }

            $this->closeReglementsModal();
            $message = $createdCount > 1 
                ? "{$createdCount} règlements enregistrés avec succès." 
                : "Règlement enregistré avec succès.";
            $this->dispatch('swal:success', ['message' => $message]);
            return;
        }

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

    public function getWhatsappUrl($contrat)
    {
        if (!$contrat) return '#';
        $client = $contrat->client;
        $rawPhone = $client->phone ?? ($client->telephone ?? '');
        $phone = preg_replace('/[^0-9]/', '', $rawPhone);
        
        if (empty($phone)) {
            return '#';
        }

        if (str_starts_with($phone, '0')) {
            $phone = '212' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '212')) {
            $phone = '212' . $phone;
        }

        $clientName = trim(($client->prenom ?? '') . ' ' . ($client->nom ?? ''));
        if (empty($clientName)) {
            $clientName = $contrat->souscripteur ?? 'Client';
        }

        $dateEcheance = $contrat->date_echeance ? $contrat->date_echeance->format('d/m/Y') : '';
        $tenantName = (function_exists('tenant') && tenant()) ? tenant('name') : 'Insurio';
        $agencyName = \App\Models\Setting::get('agency_name', $tenantName);

        $message = "Bonjour {$clientName},\n\nVotre contrat d'assurance Auto (Police N° {$contrat->police}) arrive à échéance le {$dateEcheance}.\n\nMerci de contacter l'agence {$agencyName} pour effectuer le renouvellement de votre contrat.\n\nCordialement,";

        return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }

    public function render()
    {
        $countExpiring1Day = ContratAuto::where('statut', 'actif')
            ->whereBetween('date_echeance', [now()->startOfDay(), now()->addDays(1)->endOfDay()])->count();
        $countExpiring7Days = ContratAuto::where('statut', 'actif')
            ->whereBetween('date_echeance', [now()->addDays(2)->startOfDay(), now()->addDays(7)->endOfDay()])->count();
        $countExpiring10Days = ContratAuto::where('statut', 'actif')
            ->whereBetween('date_echeance', [now()->addDays(8)->startOfDay(), now()->addDays(10)->endOfDay()])->count();
        $countExpiringAll = ContratAuto::where('statut', 'actif')
            ->whereBetween('date_echeance', [now()->startOfDay(), now()->addDays(10)->endOfDay()])->count();

        // Payment Counts
        $countReglementSolde = ContratAuto::whereRaw("(SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) >= contracts.prime_totale AND contracts.prime_totale > 0")->count();
        $countReglementPartiel = ContratAuto::whereRaw("(SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) > 0 AND (SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) < contracts.prime_totale")->count();
        $countReglementNonPaye = ContratAuto::whereRaw("(SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) <= 0")->count();
        $countReglementImpaye = ContratAuto::whereRaw("(SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) < contracts.prime_totale")->count();

        $query = ContratAuto::with(['client', 'vehicule', 'compagnie', 'apporteur', 'reglements']);

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
            if ($this->filterStatut === 'expiring_1_day') {
                $query->where('statut', 'actif')
                      ->whereBetween('date_echeance', [now()->startOfDay(), now()->addDays(1)->endOfDay()]);
            } elseif ($this->filterStatut === 'expiring_7_days') {
                $query->where('statut', 'actif')
                      ->whereBetween('date_echeance', [now()->addDays(2)->startOfDay(), now()->addDays(7)->endOfDay()]);
            } elseif ($this->filterStatut === 'expiring_10_days') {
                $query->where('statut', 'actif')
                      ->whereBetween('date_echeance', [now()->addDays(8)->startOfDay(), now()->addDays(10)->endOfDay()]);
            } elseif ($this->filterStatut === 'expiring_all') {
                $query->where('statut', 'actif')
                      ->whereBetween('date_echeance', [now()->startOfDay(), now()->addDays(10)->endOfDay()]);
            } elseif ($this->filterStatut === 'reglement_solde') {
                $query->whereRaw("(SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) >= contracts.prime_totale AND contracts.prime_totale > 0");
            } elseif ($this->filterStatut === 'reglement_partiel') {
                $query->whereRaw("(SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) > 0 AND (SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) < contracts.prime_totale");
            } elseif ($this->filterStatut === 'reglement_non_paye') {
                $query->whereRaw("(SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) <= 0");
            } elseif ($this->filterStatut === 'reglement_impaye') {
                $query->whereRaw("(SELECT COALESCE(SUM(montant), 0) FROM reglements WHERE reglements.contrat_id = contracts.id) < contracts.prime_totale");
            } else {
                $query->where('statut', $this->filterStatut);
            }
        }

        // Priority sorting: put contracts expiring soonest right at the top
        if (empty($this->filterStatut) || str_starts_with($this->filterStatut, 'expiring_')) {
            $query->orderByRaw("CASE WHEN statut = 'actif' AND date_echeance >= CURRENT_DATE THEN 0 ELSE 1 END ASC")
                  ->orderBy('date_echeance', 'asc');
        } else {
            $query->latest();
        }

        $contrats = $query->paginate(10);
        $compagnies = Compagnie::all();

        return view('livewire.automobile.liste-contrats', [
            'contrats' => $contrats,
            'compagnies' => $compagnies,
            'selectedContrat' => $this->getSelectedContrat(),
            'countExpiring1Day' => $countExpiring1Day,
            'countExpiring7Days' => $countExpiring7Days,
            'countExpiring10Days' => $countExpiring10Days,
            'countExpiringAll' => $countExpiringAll,
            'countReglementSolde' => $countReglementSolde,
            'countReglementPartiel' => $countReglementPartiel,
            'countReglementNonPaye' => $countReglementNonPaye,
            'countReglementImpaye' => $countReglementImpaye,
            'reglementLines' => $this->reglementLines,
        ])->layout('layouts.app');
    }
}
