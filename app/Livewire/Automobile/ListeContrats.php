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

    // Route state persistence across Livewire AJAX requests
    public $isRenouvellements = false;
    public $isRenouvellementMode = false;

    // Date Filtering
    public $dateField = 'date_effet'; // date_effet, date_echeance, date_production
    public $dateFrom = '';
    public $dateTo = '';

    // Bulk Selection
    public $selectedContrats = [];
    public $selectAll = false;

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
        'dateField' => ['except' => 'date_effet'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function boot()
    {
        $referer = request()->header('referer', '');
        if (request()->routeIs('admin.renouvellements') || request()->is('*renouvellements*') || str_contains($referer, 'renouvellements') || request()->has('renouvellements')) {
            $this->isRenouvellements = true;
            $this->isRenouvellementMode = true;
            $this->dateField = 'date_echeance';
        }
    }

    public function mount()
    {
        $referer = request()->header('referer', '');
        if (request()->routeIs('admin.renouvellements') || request()->is('*renouvellements*') || str_contains($referer, 'renouvellements') || request()->has('renouvellements')) {
            $this->isRenouvellements = true;
            $this->isRenouvellementMode = true;
            $this->dateField = 'date_echeance';
            $this->filterStatut = '';
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
    public function resilierContrat($id = null)
    {
        $targetId = $id ?? $this->selectedContratId;
        if ($targetId) {
            $this->selectedContratId = $targetId;
            $contrat = ContratAuto::findOrFail($targetId);
            app(\App\Services\ContractWorkflowService::class)->resilier($contrat, now()->toDateString());
            session()->flash('message', 'Le contrat N° ' . $contrat->numero_contrat . ' a été résilié avec calcul du prorata temporis.');
        }
    }

    public bool $showHistoryModal = false;
    public ?ContratAuto $historyContrat = null;

    public function renouvelerContrat($id = null)
    {
        $targetId = $id ?? $this->selectedContratId;
        if ($targetId) {
            $this->selectedContratId = $targetId;
            $contrat = ContratAuto::findOrFail($targetId);
            app(\App\Services\ContractWorkflowService::class)->renouveler($contrat);
            
            session()->flash('message', 'Le contrat N° ' . $contrat->numero_contrat . ' a été renouvelé avec succès ! (Période: ' . $contrat->date_effet->format('d/m/Y') . ' au ' . $contrat->date_echeance->format('d/m/Y') . ')');
            $this->dispatch('swal:success', ['message' => 'Renouvellement effectué pour le contrat N° ' . $contrat->numero_contrat]);
        }
    }

    public function openHistoryModal($id)
    {
        $this->historyContrat = ContratAuto::with(['client', 'historiqueRenouvellements'])->find($id);
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->historyContrat = null;
    }

    public function rejeterRenouvellement($id = null)
    {
        $targetId = $id ?? $this->selectedContratId;
        if ($targetId) {
            $this->selectedContratId = $targetId;
            $contrat = ContratAuto::findOrFail($targetId);
            $contrat->update(['statut' => 'resilie']);
            
            session()->flash('message', 'Le renouvellement du contrat N° ' . $contrat->numero_contrat . ' a été marqué comme Rejeté / Non renouvelé.');
            $this->dispatch('swal:success', ['message' => 'Renouvellement marqué comme Rejeté.']);
        }
    }

    public function annulerContrat($id = null)
    {
        $targetId = $id ?? $this->selectedContratId;
        if ($targetId) {
            $this->selectedContratId = $targetId;
            $contrat = ContratAuto::findOrFail($targetId);
            app(\App\Services\ContractWorkflowService::class)->annuler($contrat);
            session()->flash('message', 'Le contrat N° ' . $contrat->numero_contrat . ' a été annulé rétroactivement avec remise à zéro des primes.');
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

            $this->reglementMontant = '';
            $this->reglementDate = now()->toDateString();
            $this->reglementMode = 'especes';
            $this->reglementReference = '';

            $this->reglementLines = [
                [
                    'montant' => '',
                    'date' => now()->toDateString(),
                    'mode' => 'especes',
                    'reference' => '',
                    'date_echeance_cheque' => '',
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
            'date_echeance_cheque' => '',
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

    public function updatedReglementMode($value)
    {
        if (!empty($this->reglementLines)) {
            $this->reglementLines[0]['mode'] = $value;
        }
    }

    public function addReglement()
    {
        if (!$this->selectedContratId) return;

        $contrat = ContratAuto::findOrFail($this->selectedContratId);

        // If single properties were updated (e.g., in unit tests), sync between single props and line 0
        if (!empty($this->reglementLines) && count($this->reglementLines) === 1) {
            if ($this->reglementMontant !== '') {
                $this->reglementLines[0]['montant'] = $this->reglementMontant;
            }
            if (!empty($this->reglementDate)) {
                $this->reglementLines[0]['date'] = $this->reglementDate;
            }
            if (!empty($this->reglementMode) && $this->reglementMode !== 'especes') {
                $this->reglementLines[0]['mode'] = $this->reglementMode;
            } else {
                $this->reglementMode = $this->reglementLines[0]['mode'] ?? 'especes';
            }
            if (!empty($this->reglementReference)) {
                $this->reglementLines[0]['reference'] = $this->reglementReference;
            }
        }

        if (!empty($this->reglementLines)) {
            $this->validate([
                'reglementLines' => 'required|array|min:1',
                'reglementLines.*.montant' => 'required|numeric|min:0.01',
                'reglementLines.*.date' => 'required|date|after_or_equal:today',
                'reglementLines.*.mode' => 'required|in:especes,cheque,virement,carte',
                'reglementLines.*.reference' => 'nullable|string|max:255',
                'reglementLines.*.date_echeance_cheque' => 'nullable|date|after_or_equal:today',
            ], [
                'reglementLines.*.montant.required' => 'Le montant est obligatoire.',
                'reglementLines.*.montant.min' => 'Le montant doit être supérieur à 0.',
                'reglementLines.*.date.after_or_equal' => 'La date de règlement ne peut pas être dans le passé.',
                'reglementLines.*.date_echeance_cheque.after_or_equal' => 'La date de versement du chèque ne peut pas être dans le passé.',
            ]);

            foreach ($this->reglementLines as $idx => $line) {
                if ($line['mode'] === 'cheque' && empty($line['date_echeance_cheque'])) {
                    $this->addError("reglementLines.{$idx}.date_echeance_cheque", "La date de versement/échéance du chèque est obligatoire.");
                    return;
                }
            }

            $createdCount = 0;
            foreach ($this->reglementLines as $line) {
                \App\Models\Reglement::create([
                    'contrat_id' => $contrat->id,
                    'montant' => $line['montant'],
                    'date_reglement' => $line['date'],
                    'mode_reglement' => $line['mode'],
                    'reference_paiement' => $line['reference'] ?? null,
                    'date_echeance_cheque' => $line['mode'] === 'cheque' ? ($line['date_echeance_cheque'] ?? null) : null,
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
            'reglementDate' => 'required|date|after_or_equal:today',
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
        $createdTime = $reglement->created_at ? \Carbon\Carbon::parse($reglement->created_at) : ($reglement->date_reglement ? \Carbon\Carbon::parse($reglement->date_reglement) : null);
        $isPastDay = $createdTime ? !$createdTime->isToday() : false;

        if ($isPastDay) {
            $this->dispatch('swal:error', ['message' => 'Impossible de supprimer un règlement d\'une date antérieure (Seuls les règlements enregistrés aujourd\'hui sont modifiables).']);
            return;
        }

        $contratId = $reglement->contrat_id;
        $amount = (float)$reglement->montant;
        $method = strtolower($reglement->mode_reglement ?? 'especes');

        \Illuminate\Support\Facades\DB::transaction(function () use ($reglement, $contratId, $amount, $method) {
            // 1. Delete corresponding FinancialLedger entry if present
            \App\Models\FinancialLedger::where('contract_id', $contratId)
                ->where('amount', $amount)
                ->whereDate('entry_date', $reglement->date_reglement ?? now())
                ->delete();

            // 2. Delete corresponding Payment if present
            if (\Illuminate\Support\Facades\Schema::hasTable('payments')) {
                \App\Models\Payment::where('contract_id', $contratId)
                    ->where('amount', $amount)
                    ->delete();
            }

            // 3. Delete corresponding Cheque if cheque payment
            if ($method === 'cheque' && \Illuminate\Support\Facades\Schema::hasTable('cheques')) {
                \App\Models\Cheque::where('contract_id', $contratId)
                    ->where('amount', $amount)
                    ->delete();
            }

            // 4. Delete the Reglement record itself
            $reglement->delete();

            // 5. Recalculate CashRegister current & expected balance mathematically
            $caisse = \App\Models\CashRegister::first();
            if ($caisse) {
                $totalCashCredit = (float) \App\Models\FinancialLedger::where('payment_method', 'cash')
                    ->where('entry_type', 'credit')
                    ->whereIn('status', ['completed', 'posted', 'approved'])
                    ->sum('amount');

                $totalCashDebit = (float) \App\Models\FinancialLedger::where('payment_method', 'cash')
                    ->where('entry_type', 'debit')
                    ->whereIn('status', ['completed', 'posted', 'approved'])
                    ->sum('amount');

                $netCash = $totalCashCredit - $totalCashDebit;
                $caisse->update([
                    'current_balance' => $netCash,
                    'expected_balance' => $netCash,
                ]);
            }
        });

        // 6. Refresh modal state for current contract so paid totals, remaining balance & history reload live
        if ($contratId) {
            $this->openReglementsModal($contratId);
        }

        $this->dispatch('swal:success', ['message' => 'Règlement supprimé et solde caisse/dashboard recalculés avec succès.']);
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

    public function setDateRangePreset($preset)
    {
        switch ($preset) {
            case 'today':
                $this->dateFrom = now()->toDateString();
                $this->dateTo = now()->toDateString();
                break;
            case 'this_month':
                $this->dateFrom = now()->startOfMonth()->toDateString();
                $this->dateTo = now()->endOfMonth()->toDateString();
                break;
            case 'this_quarter':
                $this->dateFrom = now()->startOfQuarter()->toDateString();
                $this->dateTo = now()->endOfQuarter()->toDateString();
                break;
            case 'this_year':
                $this->dateFrom = now()->startOfYear()->toDateString();
                $this->dateTo = now()->endOfYear()->toDateString();
                break;
            case 'clear':
                $this->dateFrom = '';
                $this->dateTo = '';
                break;
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedContrats = $this->getFilteredContratIds();
        } else {
            $this->selectedContrats = [];
        }
    }

    public function clearSelection()
    {
        $this->selectedContrats = [];
        $this->selectAll = false;
    }

    protected function getFilteredContratIds()
    {
        $query = ContratAuto::query();

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

        $hasCustomDate = (!empty($this->dateFrom) || !empty($this->dateTo));

        if (!empty($this->filterStatut)) {
            if (str_starts_with($this->filterStatut, 'expiring_')) {
                if (!$hasCustomDate) {
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
                    }
                } else {
                    $query->where('statut', 'actif');
                }
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

        $this->applyDateFilter($query);

        return $query->pluck('id')->map(fn($id) => (string)$id)->toArray();
    }

    public function bulkUpdateStatut($statut)
    {
        if (empty($this->selectedContrats)) {
            $this->dispatch('swal:error', ['message' => 'Veuillez sélectionner au moins un contrat.']);
            return;
        }

        $validStatuts = ['actif', 'expire', 'resilie', 'annule'];
        if (!in_array($statut, $validStatuts)) {
            return;
        }

        $count = ContratAuto::whereIn('id', $this->selectedContrats)->update(['statut' => $statut]);

        $this->clearSelection();
        $this->dispatch('swal:success', ['message' => "Statut mis à jour pour {$count} contrat(s) sélectionné(s)."]);
    }

    public function bulkRelancerEmail()
    {
        if (empty($this->selectedContrats)) {
            $this->dispatch('swal:error', ['message' => 'Veuillez sélectionner au moins un contrat.']);
            return;
        }

        $mailHost = \App\Models\Setting::get('mail_host');
        if (empty($mailHost)) {
            $this->dispatch('swal:error', ['message' => "Le serveur SMTP n'est pas configuré. Veuillez aller dans la configuration de l'agence pour l'activer."]);
            return;
        }

        $contrats = ContratAuto::with('client')->whereIn('id', $this->selectedContrats)->get();
        $sentCount = 0;

        $tenantName = (function_exists('tenant') && tenant()) ? tenant('name') : 'Insurio';
        $agencyName = \App\Models\Setting::get('agency_name', $tenantName);
        $agencyPhone = \App\Models\Setting::get('agency_phone', '+212 5 22 00 00 00');

        foreach ($contrats as $contrat) {
            if ($contrat->client && !empty($contrat->client->email)) {
                try {
                    \Illuminate\Support\Facades\Mail::to($contrat->client->email)
                        ->send(new \App\Mail\RenewalReminderMail($contrat->client, $contrat, $agencyName, $agencyPhone));
                    $sentCount++;
                } catch (\Throwable $e) {
                    // Ignore individual failure
                }
            }
        }

        $this->clearSelection();
        $this->dispatch('swal:success', ['message' => "Relances envoyées par email à {$sentCount} client(s)."]);
    }

    public function bulkExportCsv()
    {
        if (empty($this->selectedContrats)) {
            $this->dispatch('swal:error', ['message' => 'Veuillez sélectionner au moins un contrat.']);
            return;
        }

        $contrats = ContratAuto::with(['client', 'compagnie', 'vehicule'])->whereIn('id', $this->selectedContrats)->get();

        $csvData = "ID;N° Contrat;Police;Avenant;Attestation;Code Client;Client;Compagnie;Matricule;Date Effet;Date Echeance;Prime Totale;Statut\n";

        foreach ($contrats as $c) {
            $clientName = $c->client ? ($c->client->nom . ' ' . $c->client->prenom) : ($c->souscripteur ?? '-');
            $codeClient = $c->client_id ? 'CL-' . str_pad($c->client_id, 6, '0', STR_PAD_LEFT) : '-';
            $compagnie = $c->compagnie ? $c->compagnie->nom : '-';
            $dateEffet = $c->date_effet ? $c->date_effet->format('d/m/Y') : '-';
            $dateEcheance = $c->date_echeance ? $c->date_echeance->format('d/m/Y') : '-';

            $csvData .= "{$c->id};{$c->numero_contrat};{$c->police};{$c->avenant};{$c->attestation};{$codeClient};{$clientName};{$compagnie};{$c->matricule};{$dateEffet};{$dateEcheance};{$c->prime_totale};{$c->statut}\n";
        }

        $this->clearSelection();

        return response()->streamDownload(function () use ($csvData) {
            echo "\xEF\xBB\xBF";
            echo $csvData;
        }, 'export_contrats_selection_' . date('Ymd_His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function deleteContrat($id)
    {
        $contrat = ContratAuto::withCount('reglements')->findOrFail($id);
        $num = $contrat->numero_contrat;

        // Rule 1: Do not delete if payments exist
        if ($contrat->reglements_count > 0) {
            $this->dispatch('swal:error', ['message' => "Impossible de supprimer le contrat N° {$num} car des règlements y sont enregistrés."]);
            return;
        }

        // Rule 2: Soft delete contract (moves to trash)
        $contrat->delete();

        $this->dispatch('swal:success', ['message' => "Le contrat N° {$num} a été placé dans la corbeille (Trash)."]);
    }

    public function bulkDelete()
    {
        if (empty($this->selectedContrats)) {
            $this->dispatch('swal:error', ['message' => 'Veuillez sélectionner au moins un contrat.']);
            return;
        }

        // Check if any selected contracts have payments
        $contratsWithPayments = ContratAuto::whereIn('id', $this->selectedContrats)
            ->whereHas('reglements')
            ->pluck('numero_contrat')
            ->toArray();

        if (!empty($contratsWithPayments)) {
            $nums = implode(', ', $contratsWithPayments);
            $this->dispatch('swal:error', ['message' => "Impossible de supprimer : le(s) contrat(s) {$nums} contienne(nt) des règlements."]);
            return;
        }

        // Soft delete all selected contracts (moves to trash)
        $count = count($this->selectedContrats);
        ContratAuto::whereIn('id', $this->selectedContrats)->delete();

        $this->clearSelection();
        $this->dispatch('swal:success', ['message' => "{$count} contrat(s) placé(s) dans la corbeille (Trash) avec succès."]);
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

        $hasCustomDate = (!empty($this->dateFrom) || !empty($this->dateTo));

        if (!empty($this->filterStatut)) {
            if (str_starts_with($this->filterStatut, 'expiring_')) {
                if (!$hasCustomDate) {
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
                    }
                } else {
                    $query->where('statut', 'actif');
                }
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

        // Filter by Date
        $this->applyDateFilter($query);

        // Priority sorting: put contracts expiring soonest right at the top
        if ($this->isRenouvellements || empty($this->filterStatut) || str_starts_with($this->filterStatut, 'expiring_')) {
            $query->orderByRaw("CASE WHEN statut = 'actif' AND COALESCE(end_date, date_echeance) >= CURRENT_DATE THEN 0 ELSE 1 END ASC")
                  ->orderByRaw("COALESCE(end_date, date_echeance) ASC");
        } else {
            $query->latest();
        }

        $contrats = $query->paginate(10);
        $compagnies = Compagnie::all();

        return view('livewire.automobile.liste-contrats', [
            'contrats' => $contrats,
            'compagnies' => $compagnies,
            'isRenouvellementMode' => $this->isRenouvellementMode || $this->isRenouvellements,
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

    public function updatedDateFrom()
    {
        if (in_array($this->filterStatut, ['reglement_non_paye', 'reglement_solde', 'reglement_partiel', 'reglement_impaye'])) {
            $this->filterStatut = '';
        }
    }

    public function updatedDateTo()
    {
        if (in_array($this->filterStatut, ['reglement_non_paye', 'reglement_solde', 'reglement_partiel', 'reglement_impaye'])) {
            $this->filterStatut = '';
        }
    }

    protected function applyDateFilter($query)
    {
        if ($this->isRenouvellements || $this->isRenouvellementMode) {
            $this->dateField = 'date_echeance';
        }

        if (empty($this->dateFrom) && empty($this->dateTo)) {
            // Default behavior for Renouvellements page: filter contracts expiring within 30 days OR already renewed
            if ($this->isRenouvellements || $this->isRenouvellementMode) {
                $maxEcheance = now()->addDays(30)->endOfDay()->toDateTimeString();
                $query->where(function ($q) use ($maxEcheance) {
                    $q->where('end_date', '<=', $maxEcheance);
                    if (\Illuminate\Support\Facades\Schema::hasColumn('contracts', 'date_echeance')) {
                        $q->orWhere('date_echeance', '<=', $maxEcheance);
                    }
                    $q->orWhereHas('historiqueRenouvellements');
                });
            }
            return;
        }

        $dateFrom = !empty($this->dateFrom) ? $this->dateFrom . ' 00:00:00' : null;
        $dateTo = !empty($this->dateTo) ? $this->dateTo . ' 23:59:59' : null;

        $primaryCol = match ($this->dateField) {
            'date_effet' => 'start_date',
            'date_echeance' => 'end_date',
            'date_production' => 'created_at',
            default => 'end_date',
        };

        $fallbackCol = match ($this->dateField) {
            'date_effet' => 'date_effet',
            'date_echeance' => 'date_echeance',
            'date_production' => 'date_production',
            default => 'date_echeance',
        };

        $query->where(function ($q) use ($primaryCol, $fallbackCol, $dateFrom, $dateTo) {
            $q->where(function ($sub) use ($primaryCol, $dateFrom, $dateTo) {
                if ($dateFrom && $dateTo) {
                    $sub->whereBetween($primaryCol, [$dateFrom, $dateTo]);
                } elseif ($dateFrom) {
                    $sub->where($primaryCol, '>=', $dateFrom);
                } elseif ($dateTo) {
                    $sub->where($primaryCol, '<=', $dateTo);
                }
            });

            if (\Illuminate\Support\Facades\Schema::hasColumn('contracts', $fallbackCol)) {
                $q->orWhere(function ($sub) use ($fallbackCol, $dateFrom, $dateTo) {
                    if ($dateFrom && $dateTo) {
                        $sub->whereBetween($fallbackCol, [$dateFrom, $dateTo]);
                    } elseif ($dateFrom) {
                        $sub->where($fallbackCol, '>=', $dateFrom);
                    } elseif ($dateTo) {
                        $sub->where($fallbackCol, '<=', $dateTo);
                    }
                });
            }

            if ($this->isRenouvellements || $this->isRenouvellementMode) {
                $q->orWhereHas('historiqueRenouvellements', function ($hQuery) use ($dateFrom, $dateTo) {
                    if ($dateFrom && $dateTo) {
                        $hQuery->whereBetween('anc_date_echeance', [$dateFrom, $dateTo])
                               ->orWhereBetween('nouv_date_echeance', [$dateFrom, $dateTo])
                               ->orWhereBetween('created_at', [$dateFrom, $dateTo]);
                    } elseif ($dateFrom) {
                        $hQuery->where('anc_date_echeance', '>=', $dateFrom)
                               ->orWhere('nouv_date_echeance', '>=', $dateFrom)
                               ->orWhere('created_at', '>=', $dateFrom);
                    } elseif ($dateTo) {
                        $hQuery->where('anc_date_echeance', '<=', $dateTo)
                               ->orWhere('nouv_date_echeance', '<=', $dateTo)
                               ->orWhere('created_at', '<=', $dateTo);
                    }
                });
            }
        });
    }
}
