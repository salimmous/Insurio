<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'cin',
        'adresse',
        'type',
        'solvabilite',
        'incident',
    ];

    public function contrats(): HasMany
    {
        return $this->hasMany(ContratAuto::class, 'client_id');
    }
}
