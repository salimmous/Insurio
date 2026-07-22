<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DailyCashClosing extends Model
{
    protected $table = 'daily_cash_closings';

    protected $fillable = [
        'uuid',
        'closing_date',
        'cash_register_id',
        'branch_id',
        'opening_balance',
        'total_cash_in',
        'total_cash_out',
        'total_transfers_in',
        'total_card_in',
        'total_cheques_received',
        'total_cheques_deposited',
        'total_bank_deposits',
        'total_bank_withdrawals',
        'total_expenses',
        'total_commissions',
        'total_refunds',
        'expected_closing_balance',
        'physical_closing_balance',
        'variance_amount',
        'net_cash',
        'net_profit',
        'status',
        'closed_by',
        'approved_by',
        'closed_at',
        'manager_signature',
        'employee_signature',
        'notes',
    ];

    protected $casts = [
        'closing_date' => 'date',
        'closed_at' => 'datetime',
        'opening_balance' => 'decimal:2',
        'total_cash_in' => 'decimal:2',
        'total_cash_out' => 'decimal:2',
        'total_transfers_in' => 'decimal:2',
        'total_card_in' => 'decimal:2',
        'total_cheques_received' => 'decimal:2',
        'total_cheques_deposited' => 'decimal:2',
        'total_bank_deposits' => 'decimal:2',
        'total_bank_withdrawals' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'total_commissions' => 'decimal:2',
        'total_refunds' => 'decimal:2',
        'expected_closing_balance' => 'decimal:2',
        'physical_closing_balance' => 'decimal:2',
        'variance_amount' => 'decimal:2',
        'net_cash' => 'decimal:2',
        'net_profit' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($closing) {
            if (empty($closing->uuid)) {
                $closing->uuid = (string) Str::uuid();
            }
        });
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }
}
