<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Contract;
use App\Models\AutomationRule;
use App\Events\ContractExpiringEvent;

class CheckContractExpirations extends Command
{
    protected $signature = 'platform:check-expirations';
    protected $description = 'Loop through all active tenants and trigger expiring contract events based on automation rules';

    public function handle()
    {
        $tenants = Tenant::where('status', 'active')->get();
        $this->info("Found {$tenants->count()} active tenants to process.");

        foreach ($tenants as $tenant) {
            $this->line("Processing tenant: {$tenant->id} ({$tenant->name})");
            
            $tenant->run(function () use ($tenant) {
                // Find all unique days_before_expiry conditions from active rules
                $rules = AutomationRule::where('event', 'contract.expiring')->where('is_active', true)->get();
                $daysBuckets = [];
                
                foreach ($rules as $rule) {
                    if (isset($rule->conditions['days_before_expiry'])) {
                        $daysBuckets[] = (int) $rule->conditions['days_before_expiry'];
                    }
                }
                
                $daysBuckets = array_unique($daysBuckets);
                if (empty($daysBuckets)) {
                    $this->line("-> No active contract.expiring rules found for this tenant.");
                    return;
                }

                $this->line("-> Active days buckets to check: " . implode(', ', $daysBuckets));

                foreach ($daysBuckets as $days) {
                    $targetDate = now()->addDays($days)->toDateString();
                    
                    $contracts = Contract::where('status', 'active')
                        ->whereDate('end_date', $targetDate)
                        ->get();

                    if ($contracts->isNotEmpty()) {
                        $this->info("--> Found {$contracts->count()} contracts expiring in {$days} days.");
                        foreach ($contracts as $contract) {
                            event(new ContractExpiringEvent($contract, $days));
                            $this->line("----> Dispatched expiring event for contract #{$contract->contract_number}");
                        }
                    }
                }
            });
        }

        $this->info('Contract expiration check complete.');
        return 0;
    }
}
