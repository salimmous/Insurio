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
        'contact',
        'email',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Compatibility aliases for the new specifications
    public function getNameAttribute(): ?string
    {
        return $this->nom;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->nom = $value;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->telephone;
    }

    public function setPhoneAttribute(?string $value): void
    {
        $this->telephone = $value;
    }

    public function getAddressAttribute(): ?string
    {
        return $this->adresse;
    }

    public function setAddressAttribute(?string $value): void
    {
        $this->adresse = $value;
    }

    public function contrats(): HasMany
    {
        return $this->hasMany(ContratAuto::class, 'compagnie_id');
    }
}
