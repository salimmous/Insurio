<?php

namespace App\Events;

use App\Models\Contract;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContractExpiringEvent
{
    use Dispatchable, SerializesModels;

    public $contract;
    public $daysBeforeExpiry;

    public function __construct(Contract $contract, int $daysBeforeExpiry)
    {
        $this->contract = $contract;
        $this->daysBeforeExpiry = $daysBeforeExpiry;
    }
}
