<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class AutoContractDetail extends Model
{
    protected $table = 'auto_contract_details';

    protected $fillable = [
        'vehicule_id',
        'usage',
        'code_classe',
        'sous_classe',
        'marque',
        'matricule',
        'puissance_fiscale',
        'nb_places',
        'carburant',
        'nbr_mois',
        'valeur_vehicule',
        'date_mise_circulation',
    ];

    protected $casts = [
        'date_mise_circulation' => 'date',
        'valeur_vehicule' => 'decimal:2',
    ];

    public function contract(): MorphOne
    {
        return $this->morphOne(Contract::class, 'details');
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class, 'vehicule_id');
    }
}
