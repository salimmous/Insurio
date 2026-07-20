<?php

namespace App\Events;

use App\Models\Contract;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContractRenewed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Contract $oldContract,
        public readonly Contract $newContract
    ) {}
}
