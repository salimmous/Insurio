<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiculeModele extends Model
{
    protected $table = 'vehicule_modeles';

    protected $fillable = [
        'marque_id',
        'nom',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function marque(): BelongsTo
    {
        return $this->belongsTo(VehiculeMarque::class, 'marque_id');
    }
}
