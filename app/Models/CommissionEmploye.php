<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionEmploye extends Model
{
    protected $table = 'commissions_employes';

    protected $fillable = [
        'employe_id',
        'contrat_id',
        'base_calcul',
        'taux_applique',
        'montant_commission',
        'periode',
        'statut',
        'date_validation',
        'date_paiement',
        'valide_par',
    ];

    protected $casts = [
        'base_calcul' => 'decimal:2',
        'taux_applique' => 'decimal:2',
        'montant_commission' => 'decimal:2',
        'date_validation' => 'datetime',
        'date_paiement' => 'datetime',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employe_id');
    }

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(ContratAuto::class, 'contrat_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'valide_par');
    }

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\SuccursaleScope);
    }
}
