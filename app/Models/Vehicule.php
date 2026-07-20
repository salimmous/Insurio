<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicule extends Model
{
    protected $table = 'vehicules';

    protected $fillable = [
        'matricule',
        'marque',
        'modele',
        'type_carburant',
        'puissance_fiscale',
        'date_mise_circulation',
    ];

    public function contrats(): HasMany
    {
        return $this->hasMany(ContratAuto::class, 'vehicule_id');
    }
}
