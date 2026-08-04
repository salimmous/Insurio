<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehiculeMarque extends Model
{
    protected $table = 'vehicule_marques';

    protected $fillable = [
        'nom',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function modeles(): HasMany
    {
        return $this->hasMany(VehiculeModele::class, 'marque_id')->orderBy('nom');
    }
}
