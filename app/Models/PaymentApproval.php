<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PaymentApproval extends Model
{
    protected $table = 'payment_approvals';

    protected $fillable = [
        'uuid',
        'ledger_id',
        'requested_by',
        'approved_by_manager',
        'approved_by_finance',
        'amount',
        'status',
        'manager_notes',
        'finance_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($app) {
            if (empty($app->uuid)) {
                $app->uuid = (string) Str::uuid();
            }
        });
    }

    public function ledger(): BelongsTo
    {
        return $this->belongsTo(FinancialLedger::class, 'ledger_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
