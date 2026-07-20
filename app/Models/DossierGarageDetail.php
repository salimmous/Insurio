<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DossierGarageDetail extends Model
{
    protected $table = 'dossier_garage_details';

    protected $fillable = [
        'dossier_id',
        'garage_name',
        'address',
        'phone',
        'appointment_at',
        'repair_start_date',
        'repair_end_date',
        'estimate',
        'invoice',
        'status',
    ];

    protected $casts = [
        'appointment_at' => 'datetime',
        'repair_start_date' => 'date',
        'repair_end_date' => 'date',
        'estimate' => 'decimal:2',
        'invoice' => 'decimal:2',
    ];

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }
}
