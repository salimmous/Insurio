<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;

class PlatformPayment extends Model
{
    protected $table = 'platform_payments';

    protected $fillable = [
        'invoice_id',
        'amount',
        'status',
        'payment_method',
        'transaction_reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
