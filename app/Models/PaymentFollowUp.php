<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentFollowUp extends Model
{
    protected $table = 'payment_follow_ups';

    protected $fillable = [
        'payment_id',
        'assigned_employee_id',
        'reminder_date',
        'priority',
        'notes',
        'completed',
    ];

    protected $casts = [
        'reminder_date' => 'date',
        'completed' => 'boolean',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'assigned_employee_id');
    }
}
