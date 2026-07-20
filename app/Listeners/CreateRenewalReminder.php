<?php

namespace App\Listeners;

use App\Events\ContractCreated;
use App\Models\Renewal;

class CreateRenewalReminder
{
    /**
     * Automatically create a renewal reminder record when a new contract is saved.
     * The scheduled job will check this table and send notifications 30/15/7 days before expiry.
     */
    public function handle(ContractCreated $event): void
    {
        $contract = $event->contract;

        if (!$contract->end_date) {
            return;
        }

        try {
            Renewal::firstOrCreate(
                ['contract_id' => $contract->id],
                [
                    'reminder_date'     => $contract->end_date->subDays(30),
                    'days_before_expiry' => 30,
                    'status'            => 'pending',
                ]
            );
        } catch (\Throwable $e) {
            // Non-critical: do not break contract save
        }
    }
}
