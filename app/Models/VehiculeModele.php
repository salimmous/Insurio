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
        'annee_debut',
        'annee_fin',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'annee_debut' => 'integer',
        'annee_fin' => 'integer',
    ];

    public function marque(): BelongsTo
    {
        return $this->belongsTo(VehiculeMarque::class, 'marque_id');
    }

    public function getLibelleAnneeAttribute(): ?string
    {
        if ($this->annee_debut && $this->annee_fin) {
            if ($this->annee_debut === $this->annee_fin) {
                return (string) $this->annee_debut;
            }
            return "{$this->annee_debut}-{$this->annee_fin}";
        } elseif ($this->annee_debut) {
            return "{$this->annee_debut}+";
        } elseif ($this->annee_fin) {
            return "jusqu'à {$this->annee_fin}";
        }

        return null;
    }
}
