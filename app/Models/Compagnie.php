<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compagnie extends Model
{
    protected $table = 'compagnies';

    protected $fillable = [
        'nom',
        'code',
        'logo',
        'adresse',
        'telephone',
    ];

    public function contrats(): HasMany
    {
        return $this->hasMany(ContratAuto::class, 'compagnie_id');
    }
}
