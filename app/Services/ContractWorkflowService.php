<?php

namespace App\Services;

use App\Models\ContratAuto;
use App\Models\Vehicule;
use Carbon\Carbon;

class ContractWorkflowService
{
    /**
     * Renew a contract.
     */
    public function renouveler(ContratAuto $contrat): ContratAuto
    {
        $newContrat = $contrat->replicate();
        
        // Increment dates by the duration of the contract (nbr_mois)
        $months = $contrat->nbr_mois ?? 12;
        $newContrat->date_effet = $contrat->date_echeance->copy();
        $newContrat->date_echeance = $contrat->date_echeance->copy()->addMonths($months);
        $newContrat->date_production = Carbon::now();
        $newContrat->date_resiliation = null;
        
        // Generate new contract reference
        $newContrat->numero_contrat = $contrat->numero_contrat . '-RN';
        // Make sure it is unique
        $suffix = 1;
        while (ContratAuto::where('numero_contrat', $newContrat->numero_contrat)->exists()) {
            $newContrat->numero_contrat = $contrat->numero_contrat . '-RN' . $suffix;
            $suffix++;
        }

        $newContrat->type_affaire = 'RN';
        $newContrat->statut = 'actif';
        $newContrat->save();

        return $newContrat;
    }

    /**
     * Change vehicle on a contract.
     */
    public function changerVehicule(ContratAuto $contrat, array $vehiculeData): ContratAuto
    {
        // 1. Create a new vehicle record (historise old one by keeping it linked to old states, but create new one for new details)
        $newVehicule = Vehicule::create($vehiculeData);

        // 2. Update contract vehicle and set type to RC (Remplacement)
        $contrat->vehicule_id = $newVehicule->id;
        $contrat->matricule = $newVehicule->matricule;
        $contrat->marque = $newVehicule->marque;
        $contrat->type_affaire = 'RC';
        $contrat->save();

        return $contrat;
    }

    /**
     * Resign/Terminate a contract (Résiliation) with prorata calculation.
     */
    public function resilier(ContratAuto $contrat, $dateResiliationStr): ContratAuto
    {
        $dateResiliation = Carbon::parse($dateResiliationStr);
        $contrat->date_resiliation = $dateResiliation;
        $contrat->statut = 'resilie';

        // Calculate prorata temporis
        $totalDays = $contrat->date_effet->diffInDays($contrat->date_echeance);
        $elapsedDays = $contrat->date_effet->diffInDays($dateResiliation);

        if ($totalDays > 0 && $elapsedDays >= 0) {
            $prorataFactor = min(1, $elapsedDays / $totalDays);
            
            // Adjust primes
            $contrat->prime_rc = round((float)$contrat->prime_rc * $prorataFactor, 2);
            $contrat->def_rec = round((float)$contrat->def_rec * $prorataFactor, 2);
            $contrat->tierce = round((float)$contrat->tierce * $prorataFactor, 2);
            $contrat->collision = round((float)$contrat->collision * $prorataFactor, 2);
            $contrat->vol = round((float)$contrat->vol * $prorataFactor, 2);
            $contrat->incendie = round((float)$contrat->incendie * $prorataFactor, 2);
            $contrat->bris_glace = round((float)$contrat->bris_glace * $prorataFactor, 2);
            $contrat->individuel = round((float)$contrat->individuel * $prorataFactor, 2);

            $contrat->taxe_auto = round((float)$contrat->taxe_auto * $prorataFactor, 2);
            $contrat->timbre = round((float)$contrat->timbre * $prorataFactor, 2);
            $contrat->montant_pta = round((float)$contrat->montant_pta * $prorataFactor, 2);
            $contrat->montant_taxe_pta = round((float)$contrat->montant_taxe_pta * $prorataFactor, 2);
            $contrat->accessoires = round((float)$contrat->accessoires * $prorataFactor, 2);
        }

        $contrat->save();

        return $contrat;
    }

    /**
     * Cancel a contract retroactively (Annulation).
     */
    public function annuler(ContratAuto $contrat, string $motif = ''): ContratAuto
    {
        $contrat->statut = 'annule';
        $contrat->date_resiliation = $contrat->date_effet;
        
        // Zero all values
        $contrat->prime_rc = 0;
        $contrat->def_rec = 0;
        $contrat->tierce = 0;
        $contrat->collision = 0;
        $contrat->vol = 0;
        $contrat->incendie = 0;
        $contrat->bris_glace = 0;
        $contrat->individuel = 0;

        $contrat->taxe_auto = 0;
        $contrat->timbre = 0;
        $contrat->montant_pta = 0;
        $contrat->montant_taxe_pta = 0;
        $contrat->accessoires = 0;

        $contrat->save();

        return $contrat;
    }
}
