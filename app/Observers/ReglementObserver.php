<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Reglement;

class ReglementObserver
{
    public function created(Reglement $reglement): void
    {
        if (PaymentObserver::$syncing) {
            return;
        }

        PaymentObserver::$syncing = true;
        try {
            $modeMap = [
                'especes' => 'cash',
                'virement' => 'bank_transfer',
                'carte' => 'card',
                'cheque' => 'cash',
            ];

            $contract = $reglement->contrat;
            $clientId = $contract ? $contract->client_id : null;

            if ($clientId) {
                try {
                    // Check if already exists in payments
                    $exists = Payment::where([
                        'contract_id' => $reglement->contrat_id,
                        'amount' => $reglement->montant,
                    ])->exists();

                    if (!$exists) {
                        Payment::create([
                            'client_id' => $clientId,
                            'contract_id' => $reglement->contrat_id,
                            'amount' => $reglement->montant,
                            'payment_method' => $modeMap[$reglement->mode_reglement] ?? 'cash',
                            'status' => 'paid',
                            'reference' => $reglement->reference_paiement,
                            'created_at' => $reglement->date_reglement,
                        ]);
                    }
                } catch (\Throwable $e) {
                    // Ignore if payments table doesn't exist
                }
            }
        } finally {
            PaymentObserver::$syncing = false;
        }
    }
}
