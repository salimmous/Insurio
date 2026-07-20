<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Dossier extends Model
{
    protected $table = 'dossiers';

    protected $fillable = [
        'uuid',
        'dossier_number',
        'type',
        'status',
        'priority',
        'urgency',
        'succursale_id',
        'assigned_employee_id',
        'manager_id',
        'client_id',
        'contract_id',
        'compagnie_id',
        'creation_date',
        'closing_date',
        'progress',
        'custom_fields',
        'checklist',
    ];

    protected $casts = [
        'creation_date' => 'date',
        'closing_date' => 'date',
        'custom_fields' => 'array',
        'checklist' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            if (empty($model->dossier_number)) {
                $year = date('Y');
                $latest = self::whereYear('creation_date', $year)->latest('id')->first();
                $seq = $latest ? ((int) substr($latest->dossier_number, 8)) + 1 : 1;
                $model->dossier_number = sprintf('DS-%s-%06d', $year, $seq);
            }
        });

        static::addGlobalScope(new \App\Models\Scopes\SuccursaleScope);
    }

    public function succursale(): BelongsTo
    {
        return $this->belongsTo(Succursale::class, 'succursale_id');
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'assigned_employee_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function compagnie(): BelongsTo
    {
        return $this->belongsTo(Compagnie::class, 'compagnie_id');
    }

    public function accidentDetail(): HasOne
    {
        return $this->hasOne(DossierAccidentDetail::class, 'dossier_id');
    }

    public function involvedParties(): HasMany
    {
        return $this->hasMany(DossierInvolvedParty::class, 'dossier_id');
    }

    public function expertDetail(): HasOne
    {
        return $this->hasOne(DossierExpertDetail::class, 'dossier_id');
    }

    public function garageDetail(): HasOne
    {
        return $this->hasOne(DossierGarageDetail::class, 'dossier_id');
    }

    public function chequeDetail(): HasOne
    {
        return $this->hasOne(DossierChequeDetail::class, 'dossier_id');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(DossierFollowUp::class, 'dossier_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'dossier_followers', 'dossier_id', 'user_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'dossier_id');
    }

    public function communications(): HasMany
    {
        return $this->hasMany(Communication::class, 'dossier_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'dossier_id');
    }
}
