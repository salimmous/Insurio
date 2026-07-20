<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankReconciliation extends Model
{
    protected $table = 'bank_reconciliations';

    protected $fillable = [
        'payment_id',
        'deposit_date',
        'reference',
        'matched',
        'difference',
        'notes',
    ];

    protected $casts = [
        'deposit_date' => 'date',
        'matched' => 'boolean',
        'difference' => 'decimal:2',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
