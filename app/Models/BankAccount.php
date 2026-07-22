<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BankAccount extends Model
{
    protected $table = 'bank_accounts';

    protected $fillable = [
        'uuid',
        'bank_name',
        'agency',
        'iban',
        'rib',
        'account_number',
        'swift',
        'opening_balance',
        'current_balance',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($bank) {
            if (empty($bank->uuid)) {
                $bank->uuid = (string) Str::uuid();
            }
        });
    }
}
