<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgencyExpense extends Model
{
    protected $table = 'agency_expenses';

    protected $fillable = [
        'title',
        'category', // loyer, electricite, eau, salaire, autre
        'amount',
        'date_charge',
        'description',
        'succursale_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date_charge' => 'date',
    ];

    public function succursale(): BelongsTo
    {
        return $this->belongsTo(Succursale::class, 'succursale_id');
    }
}
