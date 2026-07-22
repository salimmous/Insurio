<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CashRegister extends Model
{
    protected $table = 'cash_registers';

    protected $fillable = [
        'uuid',
        'name',
        'branch_id',
        'opening_balance',
        'current_balance',
        'expected_balance',
        'physical_balance',
        'variance_amount',
        'is_open',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'expected_balance' => 'decimal:2',
        'physical_balance' => 'decimal:2',
        'variance_amount' => 'decimal:2',
        'is_open' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($cash) {
            if (empty($cash->uuid)) {
                $cash->uuid = (string) Str::uuid();
            }
        });
    }
}
