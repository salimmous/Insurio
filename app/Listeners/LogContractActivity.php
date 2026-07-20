<?php

namespace App\Listeners;

use App\Events\ContractCreated;
use App\Events\ContractRenewed;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogContractActivity
{
    /**
     * Handle contract created event.
     */
    public function handleCreated(ContractCreated $event): void
    {
        $this->log(
            $event->contract->client_id ?? null,
            $event->contract->id,
            'Contrat créé',
            "Nouveau contrat #{$event->contract->contract_number} — Prime: " . number_format($event->contract->premium_amount, 2) . " MAD"
        );
    }

    /**
     * Handle contract renewed event.
     */
    public function handleRenewed(ContractRenewed $event): void
    {
        $this->log(
            $event->newContract->client_id ?? null,
            $event->newContract->id,
            'Contrat renouvelé',
            "Contrat #{$event->oldContract->contract_number} renouvelé → #{$event->newContract->contract_number}"
        );
    }

    private function log(?int $clientId, ?int $contractId, string $action, string $description): void
    {
        try {
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'client_id'   => $clientId,
                'contract_id' => $contractId,
                'action'      => $action,
                'description' => $description,
            ]);
        } catch (\Throwable $e) {
            // Never break the main flow because of logging
        }
    }
}
