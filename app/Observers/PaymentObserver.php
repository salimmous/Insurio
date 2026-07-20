<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Reglement;
use App\Models\Contract;

class PaymentObserver
{
    public static bool $syncing = false;

    public function created(Payment $payment): void
    {
        if (self::$syncing) {
            return;
        }

        self::$syncing = true;
        try {
            $modeMap = [
                'cash' => 'especes',
                'bank_transfer' => 'virement',
                'card' => 'carte',
            ];

            try {
                Reglement::create([
                    'contrat_id' => $payment->contract_id,
                    'montant' => $payment->amount,
                    'date_reglement' => $payment->created_at ?? now(),
                    'mode_reglement' => $modeMap[$payment->payment_method] ?? 'especes',
                    'reference_paiement' => $payment->reference,
                ]);
            } catch (\Throwable $e) {
                // Ignore if reglements table doesn't exist
            }

            // Dispatch event for Phase 3 components (e.g. cache invalidation)
            $contract = $payment->contract ?? Contract::find($payment->contract_id);
            if ($contract) {
                \App\Events\PaymentReceived::dispatch($payment, $contract);
            }
        } finally {
            self::$syncing = false;
        }
    }

    public function updated(Payment $payment): void
    {
        // No complex updates needed for simple tracking, but we can sync if needed
    }

    public function deleted(Payment $payment): void
    {
        if (self::$syncing) {
            return;
        }

        self::$syncing = true;
        try {
            try {
                Reglement::where('contrat_id', $payment->contract_id)
                    ->where('montant', $payment->amount)
                    ->delete();
            } catch (\Throwable $e) {
                // Ignore if reglements table doesn't exist
            }
        } finally {
            self::$syncing = false;
        }
    }
}
