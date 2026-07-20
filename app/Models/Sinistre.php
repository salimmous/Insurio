<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sinistre extends Model
{
    protected $table = 'sinistres';

    protected $fillable = [
        'contrat_id',
        'date_sinistre',
        'date_declaration',
        'description',
        'statut',
        'montant_estime',
        'montant_paye',
    ];

    protected $casts = [
        'date_sinistre' => 'date',
        'date_declaration' => 'date',
        'montant_estime' => 'decimal:2',
        'montant_paye' => 'decimal:2',
    ];

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(ContratAuto::class, 'contrat_id');
    }
}
