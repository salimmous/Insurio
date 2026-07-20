<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;
use App\Models\Renewal;
use App\Models\Task;
use App\Models\User;
use App\Events\ContractExpiring;
use Carbon\Carbon;

class CheckContractExpiry extends Command
{
    protected $signature = 'contracts:check-expiry';
    protected $description = 'Find contracts expiring in 30, 15, or 7 days and create renewals/tasks';

    public function handle(): void
    {
        $this->info('Checking contract expiries across all tenants...');

        tenancy()->runForMultiple(null, function ($tenant) {
            $this->info("Processing tenant: " . $tenant->id);

            $targetDates = [
                30 => Carbon::now()->addDays(30)->toDateString(),
                15 => Carbon::now()->addDays(15)->toDateString(),
                7  => Carbon::now()->addDays(7)->toDateString(),
            ];

            foreach ($targetDates as $days => $dateStr) {
                $expiringContracts = Contract::whereDate('end_date', $dateStr)
                    ->whereNotIn('payment_status', ['cancelled'])
                    ->with('client')
                    ->get();

                foreach ($expiringContracts as $contract) {
                    // 1. Create or retrieve renewal record
                    Renewal::firstOrCreate(
                        ['contract_id' => $contract->id],
                        [
                            'reminder_date'      => Carbon::now()->toDateString(),
                            'days_before_expiry' => $days,
                            'status'             => 'pending',
                        ]
                    );

                    // 2. Create renewal task
                    $assignedUserId = User::role(['agent-commercial'])->first()?->id
                        ?? User::first()?->id;

                    Task::firstOrCreate(
                        [
                            'contract_id' => $contract->id,
                            'title'       => "Renouvellement contrat #" . ($contract->contract_number ?? $contract->id),
                        ],
                        [
                            'description'      => "Le contrat expire dans {$days} jours (le {$contract->end_date}). Veuillez contacter le client.",
                            'assigned_user_id' => $assignedUserId,
                            'client_id'        => $contract->client_id,
                            'priority'         => 'high',
                            'status'           => 'todo',
                            'due_date'         => Carbon::parse($contract->end_date)->subDays(1),
                        ]
                    );

                    // 3. Fire event — decoupled listeners handle WhatsApp, notifications, etc.
                    ContractExpiring::dispatch($contract, $days);

                    $this->info("Processed Contract ID: {$contract->id} ({$days} days remaining)");
                }
            }
        });

        $this->info('Completed successfully.');
    }
}
