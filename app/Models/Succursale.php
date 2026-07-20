<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Succursale extends Model
{
    protected $table = 'succursales';

    protected $fillable = [
        'code_succursale',
        'nom',
        'adresse',
        'ville',
        'telephone',
        'responsable_id',
        'is_siege',
        'is_active',
        'domain',
    ];

    protected $casts = [
        'is_siege' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'responsable_id');
    }

    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class, 'succursale_id');
    }

    public function contrats(): HasMany
    {
        return $this->hasMany(ContratAuto::class, 'succursale_id');
    }
}
