<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Renewal extends Model
{
    protected $table = 'renewals';

    protected $fillable = [
        'contract_id',
        'reminder_date',
        'days_before_expiry',
        'status',
    ];

    protected $casts = [
        'reminder_date' => 'date',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
