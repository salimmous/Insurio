<?php

namespace App\Events;

use App\Models\Contract;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContractExpiring
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Contract $contract,
        public readonly int      $daysUntilExpiry
    ) {}
}
