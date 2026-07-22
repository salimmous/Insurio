<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FinancialLedger extends Model
{
    use SoftDeletes;

    protected $table = 'financial_ledgers';

    protected $fillable = [
        'uuid',
        'transaction_id',
        'entry_date',
        'category',
        'entry_type',
        'amount',
        'currency',
        'payment_method',
        'status',
        'receipt_number',
        'qr_code_hash',
        'notes',
        'metadata',
        'user_id',
        'approved_by',
        'last_modified_by',
        'branch_id',
        'client_id',
        'contract_id',
        'payment_id',
        'cheque_id',
        'bank_account_id',
        'cash_register_id',
    ];

    protected $casts = [
        'entry_date' => 'datetime',
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($ledger) {
            if (empty($ledger->uuid)) {
                $ledger->uuid = (string) Str::uuid();
            }
            if (empty($ledger->transaction_id)) {
                $nextId = (static::max('id') ?? 0) + 1;
                $ledger->transaction_id = 'TRX-' . date('Y') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
            if (empty($ledger->receipt_number)) {
                $nextId = (static::max('id') ?? 0) + 1;
                $ledger->receipt_number = 'REC-' . date('Ymd') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
            if (empty($ledger->qr_code_hash)) {
                $ledger->qr_code_hash = md5($ledger->transaction_id . '|' . $ledger->amount . '|' . config('app.key'));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function cheque(): BelongsTo
    {
        return $this->belongsTo(Cheque::class, 'cheque_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }
}
