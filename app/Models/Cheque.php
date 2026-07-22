<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Cheque extends Model
{
    protected $table = 'cheques';

    protected $fillable = [
        'uuid',
        'cheque_number',
        'bank_name',
        'agency',
        'issuer',
        'beneficiary',
        'issue_date',
        'due_date',
        'deposit_date',
        'collection_date',
        'amount',
        'currency',
        'status',
        'reference',
        'front_image',
        'back_image',
        'notes',
        'client_id',
        'contract_id',
        'bank_account_id',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'deposit_date' => 'date',
        'collection_date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($cheque) {
            if (empty($cheque->uuid)) {
                $cheque->uuid = (string) Str::uuid();
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
}
