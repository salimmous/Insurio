<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TravelContractDetail extends Model
{
    protected $table = 'travel_contract_details';

    protected $fillable = [
        'destination',
        'passport_number',
        'travel_purpose',
    ];

    public function contract(): MorphOne
    {
        return $this->morphOne(Contract::class, 'details');
    }
}
