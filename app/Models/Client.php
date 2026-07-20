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
        'entreprise_id',
    ];

    public function contrats(): HasMany
    {
        return $this->hasMany(ContratAuto::class, 'client_id');
    }

    public function entreprise(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class, 'entreprise_id')->where('type', 'entreprise');
    }

    public function employes(): HasMany
    {
        return $this->hasMany(Client::class, 'entreprise_id')->where('type', 'particulier');
    }
}
