<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employe extends Model
{
    protected $table = 'employes';

    protected $fillable = [
        'user_id',
        'matricule_employe',
        'nom',
        'prenom',
        'cin',
        'telephone',
        'email',
        'succursale_id',
        'poste',
        'taux_commission_defaut',
        'date_embauche',
        'date_sortie',
        'statut',
    ];

    protected $casts = [
        'taux_commission_defaut' => 'decimal:2',
        'date_embauche' => 'date',
        'date_sortie' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function succursale(): BelongsTo
    {
        return $this->belongsTo(Succursale::class, 'succursale_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(CommissionEmploye::class, 'employe_id');
    }

    public function contrats(): HasMany
    {
        return $this->hasMany(ContratAuto::class, 'employe_id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\SuccursaleScope);
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->nom} {$this->prenom}";
    }

    public function canBeDeleted(&$reason = null): bool
    {
        if ($this->contrats()->exists()) {
            $reason = "Cet employé est lié à des contrats enregistrés.";
            return false;
        }

        if ($this->commissions()->exists()) {
            $reason = "Cet employé possède un historique de commissions.";
            return false;
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('dossiers') && \App\Models\Dossier::where('assigned_agent_id', $this->id)->exists()) {
            $reason = "Cet employé est assigné à des dossiers en cours.";
            return false;
        }

        if ($this->user_id && \App\Models\ActivityLog::where('user_id', $this->user_id)->exists()) {
            $reason = "Cet employé possède un historique d'activité d'audit dans le système.";
            return false;
        }

        return true;
    }
}
