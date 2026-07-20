<?php

namespace App\Listeners;

use App\Events\ContractExpiringEvent;
use App\Services\AutomationService;

class ContractExpiringListener
{
    public function handle(ContractExpiringEvent $event)
    {
        AutomationService::handleEvent('contract.expiring', $event->contract);
    }
}
