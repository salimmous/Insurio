<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Contract extends Model
{
    protected $table = 'contracts';

    protected $fillable = [
        'details_type',
        'details_id',
        'contract_number',
        'policy_number',
        'start_date',
        'end_date',
        'premium_amount',
        'commission_amount',
        'payment_status',
        'insurance_company_id',
        'insurance_type_id',
        'client_id',
        'apporteur_id',
        'branch_id',
        'succursale_id',
        'employe_id',
        'terme',
        'avenant',
        'type_affaire',
        'attestation',
        'quittance',
        'souscripteur',
        
        // Keep old fillables for backward compatibility
        'numero_contrat',
        'police',
        'date_effet',
        'date_echeance',
        'prime_totale',
        'compagnie_id',
        'product_id',
        'statut',
        'status',
        'branche_code',
        'branche_libelle',
        'date_production',
        'date_resiliation',
        'vehicule_id',

        // Auto specific calculations (can remain on core table or details)
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
    ];

    protected $casts = [
        'terme' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'date_effet' => 'date',
        'date_echeance' => 'date',
        'date_production' => 'date',
        'date_resiliation' => 'date',
        'premium_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
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

        static::saving(function ($contract) {
            // Extract vehicle fields to AutoContractDetail for backward compatibility
            $autoFields = [
                'vehicule_id', 'usage', 'code_classe', 'sous_classe', 'marque', 'matricule',
                'puissance_fiscale', 'nb_places', 'carburant', 'nbr_mois', 'valeur_vehicule',
                'date_mise_circulation'
            ];

            $hasAutoData = false;
            $autoData = [];
            foreach ($autoFields as $field) {
                if (isset($contract->attributes[$field]) || array_key_exists($field, $contract->attributes)) {
                    $autoData[$field] = $contract->attributes[$field];
                    $hasAutoData = true;
                    // Unset from local attributes so it doesn't try to write to contracts table
                    unset($contract->attributes[$field]);
                }
            }

            if ($hasAutoData) {
                // If it already has details, update it; otherwise create new
                if ($contract->details_type === 'App\\Models\\AutoContractDetail' && $contract->details_id) {
                    $details = \App\Models\AutoContractDetail::find($contract->details_id);
                    if ($details) {
                        $details->update($autoData);
                    }
                } else {
                    $details = \App\Models\AutoContractDetail::create($autoData);
                    $contract->details_id = $details->id;
                    $contract->details_type = \App\Models\AutoContractDetail::class;
                }
                $contract->vehicule_id = $details->vehicule_id;
            }

            // Keep Auto calculations running if details is Auto or if auto columns are populated
            if ($contract->details_type === 'App\\Models\\AutoContractDetail' || $contract->prime_rc > 0) {
                $contract->prime_nette = (float)$contract->prime_rc +
                                        (float)$contract->def_rec +
                                        (float)$contract->tierce +
                                        (float)$contract->collision +
                                        (float)$contract->vol +
                                        (float)$contract->incendie +
                                        (float)$contract->bris_glace +
                                        (float)$contract->individuel;

                $contract->premium_amount = $contract->prime_nette +
                                         (float)$contract->taxe_auto +
                                         (float)$contract->timbre +
                                         (float)$contract->montant_pta +
                                         (float)$contract->montant_taxe_pta +
                                         (float)$contract->accessoires;
            }

            // Sync compatibility columns before saving to satisfy NOT NULL constraints
            if ($contract->contract_number) {
                $contract->numero_contrat = $contract->contract_number;
            } elseif ($contract->numero_contrat) {
                $contract->contract_number = $contract->numero_contrat;
            }

            if ($contract->policy_number) {
                $contract->police = $contract->policy_number;
            } elseif ($contract->police) {
                $contract->policy_number = $contract->police;
            }

            if ($contract->start_date) {
                $contract->date_effet = $contract->start_date;
            } elseif ($contract->date_effet) {
                $contract->start_date = $contract->date_effet;
            }

            if ($contract->end_date) {
                $contract->date_echeance = $contract->end_date;
            } elseif ($contract->date_echeance) {
                $contract->end_date = $contract->date_echeance;
            }

            if ($contract->premium_amount) {
                $contract->prime_totale = $contract->premium_amount;
            } elseif ($contract->prime_totale) {
                $contract->premium_amount = $contract->prime_totale;
            }

            if ($contract->insurance_company_id) {
                $contract->compagnie_id = $contract->insurance_company_id;
            } elseif ($contract->compagnie_id) {
                $contract->insurance_company_id = $contract->compagnie_id;
            }

            if ($contract->insurance_type_id) {
                $contract->product_id = $contract->insurance_type_id;
            } elseif ($contract->product_id) {
                $contract->insurance_type_id = $contract->product_id;
            }

            // Fallbacks for not-null/default columns in old schema
            if (!$contract->date_production) {
                $contract->date_production = $contract->start_date ?? now();
            }
            if (!$contract->statut) {
                $contract->statut = $contract->status ?? 'actif';
            }

            // Sync vehicule_id from polymorphic details
            if ($contract->details_type === 'App\\Models\\AutoContractDetail' && $contract->details) {
                $contract->vehicule_id = $contract->details->vehicule_id;
            }
        });

        static::saved(function ($contract) {
            app(\App\Services\CommissionService::class)->triggerForAction('vente', $contract);
        });
    }

    public function newEloquentBuilder($query)
    {
        return new class($query) extends \Illuminate\Database\Eloquent\Builder {
            protected $mappings = [
                'status' => 'statut',
            ];

            public function where($column, $operator = null, $value = null, $boolean = 'and')
            {
                if (is_string($column) && isset($this->mappings[$column])) {
                    $column = $this->mappings[$column];
                }

                // Map 'active' value to legacy 'actif' for statut column
                if ($column === 'statut') {
                    if ($value === null && !in_array($operator, ['=', '!=', '<>', 'like'])) {
                        $value = $operator;
                        $operator = '=';
                    }
                    if ($value === 'active') {
                        $value = 'actif';
                    } elseif ($value === 'suspended' || $value === 'inactive') {
                        $value = 'annule';
                    }
                }

                return parent::where($column, $operator, $value, $boolean);
            }

            public function orWhere($column, $operator = null, $value = null)
            {
                if (is_string($column) && isset($this->mappings[$column])) {
                    $column = $this->mappings[$column];
                }

                // Map 'active' value to legacy 'actif' for statut column
                if ($column === 'statut') {
                    if ($value === null && !in_array($operator, ['=', '!=', '<>', 'like'])) {
                        $value = $operator;
                        $operator = '=';
                    }
                    if ($value === 'active') {
                        $value = 'actif';
                    } elseif ($value === 'suspended' || $value === 'inactive') {
                        $value = 'annule';
                    }
                }

                return parent::orWhere($column, $operator, $value);
            }
        };
    }

    // Polymorphic details relation
    public function details(): MorphTo
    {
        return $this->morphTo();
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class, 'vehicule_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function compagnie(): BelongsTo
    {
        return $this->belongsTo(Compagnie::class, 'insurance_company_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Compagnie::class, 'insurance_company_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'insurance_type_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'insurance_type_id');
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

    public function renewals(): HasMany
    {
        return $this->hasMany(Renewal::class, 'contract_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'contract_id');
    }

    public function sinistres(): HasMany
    {
        return $this->hasMany(Sinistre::class, 'contrat_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    // Compatibility Getters & Setters
    public function getNumeroContratAttribute() { return $this->attributes['contract_number'] ?? ($this->attributes['numero_contrat'] ?? null); }
    public function setNumeroContratAttribute($value) { 
        $this->attributes['contract_number'] = $value; 
        $this->attributes['numero_contrat'] = $value;
    }

    public function getPoliceAttribute() { return $this->attributes['policy_number'] ?? ($this->attributes['police'] ?? null); }
    public function setPoliceAttribute($value) { 
        $this->attributes['policy_number'] = $value; 
        $this->attributes['police'] = $value;
    }

    public function getDateEffetAttribute() { 
        $val = $this->attributes['start_date'] ?? ($this->attributes['date_effet'] ?? null);
        return $val ? \Carbon\Carbon::parse($val) : null;
    }
    public function setDateEffetAttribute($value) { 
        $this->attributes['start_date'] = $value; 
        $this->attributes['date_effet'] = $value;
    }

    public function getDateEcheanceAttribute() { 
        $val = $this->attributes['end_date'] ?? ($this->attributes['date_echeance'] ?? null);
        return $val ? \Carbon\Carbon::parse($val) : null;
    }
    public function setDateEcheanceAttribute($value) { 
        $this->attributes['end_date'] = $value; 
        $this->attributes['date_echeance'] = $value;
    }

    public function getPrimeTotaleAttribute() { return $this->attributes['premium_amount'] ?? ($this->attributes['prime_totale'] ?? 0.00); }
    public function setPrimeTotaleAttribute($value) { 
        $this->attributes['premium_amount'] = $value; 
        $this->attributes['prime_totale'] = $value;
    }

    public function getCompagnieIdAttribute() { return $this->attributes['insurance_company_id'] ?? ($this->attributes['compagnie_id'] ?? null); }
    public function setCompagnieIdAttribute($value) { 
        $this->attributes['insurance_company_id'] = $value; 
        $this->attributes['compagnie_id'] = $value;
    }

    public function getProductIdAttribute() { return $this->attributes['insurance_type_id'] ?? ($this->attributes['product_id'] ?? null); }
    public function setProductIdAttribute($value) { 
        $this->attributes['insurance_type_id'] = $value; 
        $this->attributes['product_id'] = $value;
    }

    // Map both status and statut attributes to the database column 'statut'
    public function getStatusAttribute() { return $this->attributes['statut'] ?? 'actif'; }
    public function setStatusAttribute($value) { $this->attributes['statut'] = $value; }

    public function getStatutAttribute() { return $this->attributes['statut'] ?? 'actif'; }
    public function setStatutAttribute($value) { $this->attributes['statut'] = $value; }

    // Calculated Attributes
    public function getTotalTaxeAttribute(): float
    {
        return (float)($this->taxe_auto ?? 0.00) + (float)($this->montant_taxe_pta ?? 0.00);
    }

    public function getTotalCommissionsAttribute(): float
    {
        return (float)($this->commission_auto ?? 0.00) + (float)($this->commission_pta ?? 0.00);
    }

    public function getTotalTpsAttribute(): float
    {
        return (float)($this->tps_auto ?? 0.00) + (float)($this->tps_pta ?? 0.00);
    }

    public function getSoldeAttribute(): float
    {
        // Use in-memory collection when reglements are already eager-loaded (avoids N+1)
        if ($this->relationLoaded('reglements')) {
            $reglementsSum = $this->reglements->sum('montant');
        } else {
            $reglementsSum = $this->reglements()->sum('montant');
        }
        return (float)$this->prime_totale - (float)$reglementsSum;
    }
}
