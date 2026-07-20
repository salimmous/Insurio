<?php

namespace App\Services;

use App\Models\ContratAuto;
use App\Models\CommissionEmploye;
use App\Models\Employe;
use App\Models\Setting;
use Carbon\Carbon;

class CommissionService
{
    /**
     * Calculate and record commission for a contract.
     */
    public function calculerCommission(ContratAuto $contrat): ?CommissionEmploye
    {
        if (!$contrat->employe_id) {
            return null;
        }

        $employe = Employe::find($contrat->employe_id);
        if (!$employe) {
            return null;
        }

        // base_calcul = commission_auto + commission_pta of the contract
        $baseCalcul = (float)$contrat->commission_auto + (float)$contrat->commission_pta;
        $tauxApplique = (float)$employe->taux_commission_defaut;
        $montantCommission = round(($baseCalcul * $tauxApplique) / 100, 2);

        // Period from production date
        $periode = $contrat->date_production ? $contrat->date_production->format('Y-m') : Carbon::now()->format('Y-m');

        // Create or update commission
        $commission = CommissionEmploye::updateOrCreate(
            [
                'contrat_id' => $contrat->id,
                'employe_id' => $employe->id,
            ],
            [
                'base_calcul' => $baseCalcul,
                'taux_applique' => $tauxApplique,
                'montant_commission' => $montantCommission,
                'periode' => $periode,
                // Do not reset status if it is already validated or paid
                'statut' => CommissionEmploye::where('contrat_id', $contrat->id)->where('employe_id', $employe->id)->first()?->statut ?? 'calculee',
            ]
        );

        return $commission;
    }

    /**
     * Check if commission should trigger for the given action ('vente' or 'encaissement').
     */
    public function triggerForAction(string $action, ContratAuto $contrat): void
    {
        $configuredTrigger = Setting::get('commission_trigger', 'vente');

        if ($configuredTrigger === $action) {
            $this->calculerCommission($contrat);
        }
    }

    /**
     * Validate a commission.
     */
    public function valider(CommissionEmploye $commission, int $valideParEmployeId): bool
    {
        return $commission->update([
            'statut' => 'validee',
            'date_validation' => Carbon::now(),
            'valide_par' => $valideParEmployeId,
        ]);
    }

    /**
     * Pay a commission.
     */
    public function payer(CommissionEmploye $commission): bool
    {
        return $commission->update([
            'statut' => 'payee',
            'date_paiement' => Carbon::now(),
        ]);
    }
}
