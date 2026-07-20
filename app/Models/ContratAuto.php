<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContratAuto extends Model
{
    protected $table = 'contrats_auto';
    protected $fillable = [
        'numero_contrat',
        'terme',
        'client_id',
        'vehicule_id',
        'compagnie_id',
        'apporteur_id',
        'branche_code',
        'branche_libelle',
        'branch_id',
        'police',
        'avenant',
        'type_affaire',
        'attestation',
        'quittance',
        'souscripteur',
        'usage',
        'code_classe',
        'sous_classe',
        'marque',
        'matricule',
        'puissance_fiscale',
        'nb_places',
        'carburant',
        'nbr_mois',
        'valeur_vehicule',
        'date_mise_circulation',
        'date_effet',
        'date_echeance',
        'date_production',
        'date_resiliation',
        'prime_rc',
        'def_rec',
        'tierce',
        'collision',
        'vol',
        'incendie',
        'bris_glace',
        'individuel',
        'prime_nette',
        'taxe_auto',
        'accessoire_auto_cie',
        'timbre',
        'commission_auto',
        'tps_auto',
        'montant_pta',
        'montant_taxe_pta',
        'commission_pta',
        'tps_pta',
        'accessoires',
        'prime_totale',
        'statut',
        'succursale_id',
        'employe_id',
    ];

    protected $casts = [
        'terme' => 'boolean',
        'date_effet' => 'date',
        'date_echeance' => 'date',
        'date_production' => 'date',
        'date_mise_circulation' => 'date',
        'date_resiliation' => 'date',
        'prime_rc' => 'decimal:2',
        'def_rec' => 'decimal:2',
        'tierce' => 'decimal:2',
        'collision' => 'decimal:2',
        'vol' => 'decimal:2',
        'incendie' => 'decimal:2',
        'bris_glace' => 'decimal:2',
        'individuel' => 'decimal:2',
        'prime_nette' => 'decimal:2',
        'taxe_auto' => 'decimal:2',
        'accessoire_auto_cie' => 'decimal:2',
        'timbre' => 'decimal:2',
        'commission_auto' => 'decimal:2',
        'tps_auto' => 'decimal:2',
        'montant_pta' => 'decimal:2',
        'montant_taxe_pta' => 'decimal:2',
        'commission_pta' => 'decimal:2',
        'tps_pta' => 'decimal:2',
        'accessoires' => 'decimal:2',
        'prime_totale' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\SuccursaleScope);

        static::saving(function ($contrat) {
            // Calculate prime_nette as the sum of all auto warranties
            $contrat->prime_nette = (float)$contrat->prime_rc +
                                    (float)$contrat->def_rec +
                                    (float)$contrat->tierce +
                                    (float)$contrat->collision +
                                    (float)$contrat->vol +
                                    (float)$contrat->incendie +
                                    (float)$contrat->bris_glace +
                                    (float)$contrat->individuel;

            // Calculate prime_totale = prime_nette + taxe_auto + timbre + montant_pta + montant_taxe_pta + accessoires
            $contrat->prime_totale = $contrat->prime_nette +
                                     (float)$contrat->taxe_auto +
                                     (float)$contrat->timbre +
                                     (float)$contrat->montant_pta +
                                     (float)$contrat->montant_taxe_pta +
                                     (float)$contrat->accessoires;
        });

        static::saved(function ($contrat) {
            app(\App\Services\CommissionService::class)->triggerForAction('vente', $contrat);
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class, 'vehicule_id');
    }

    public function compagnie(): BelongsTo
    {
        return $this->belongsTo(Compagnie::class, 'compagnie_id');
    }

    public function apporteur(): BelongsTo
    {
        return $this->belongsTo(Apporteur::class, 'apporteur_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(AgenceBranch::class, 'branch_id');
    }

    public function succursale(): BelongsTo
    {
        return $this->belongsTo(Succursale::class, 'succursale_id');
    }

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employe_id');
    }

    public function reglements(): HasMany
    {
        return $this->hasMany(Reglement::class, 'contrat_id');
    }

    public function sinistres(): HasMany
    {
        return $this->hasMany(Sinistre::class, 'contrat_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function getTotalTaxeAttribute(): float
    {
        return (float)$this->taxe_auto + (float)$this->montant_taxe_pta;
    }

    public function getTotalCommissionsAttribute(): float
    {
        return (float)$this->commission_auto + (float)$this->commission_pta;
    }

    public function getTotalTpsAttribute(): float
    {
        return (float)$this->tps_auto + (float)$this->tps_pta;
    }

    public function getSoldeAttribute(): float
    {
        $reglementsSum = $this->reglements()->sum('montant');
        return (float)$this->prime_totale - $reglementsSum;
    }
}
