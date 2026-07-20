<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apporteur extends Model
{
    protected $table = 'apporteurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'code_apporteur',
        'taux_commission',
    ];

    public function contrats(): HasMany
    {
        return $this->hasMany(ContratAuto::class, 'apporteur_id');
    }
}
