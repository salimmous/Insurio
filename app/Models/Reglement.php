<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reglement extends Model
{
    protected $table = 'reglements';

    protected $fillable = [
        'contrat_id',
        'montant',
        'date_reglement',
        'mode_reglement',
        'reference_paiement',
    ];

    protected $casts = [
        'date_reglement' => 'date',
        'montant' => 'decimal:2',
    ];

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(ContratAuto::class, 'contrat_id');
    }

    protected static function booted()
    {
        static::saved(function ($reglement) {
            if ($reglement->contrat) {
                app(\App\Services\CommissionService::class)->triggerForAction('encaissement', $reglement->contrat);
            }
        });
    }
}
