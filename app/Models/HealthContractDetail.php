<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class HealthContractDetail extends Model
{
    protected $table = 'health_contract_details';

    protected $fillable = [
        'medical_conditions',
        'coverage_limit',
        'insurer_network',
    ];

    protected $casts = [
        'coverage_limit' => 'decimal:2',
    ];

    public function contract(): MorphOne
    {
        return $this->morphOne(Contract::class, 'details');
    }
}
