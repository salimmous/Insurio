<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 */
class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'uuid',
        'reference',
        'first_name',
        'last_name',
        'company_name',
        'gerant',
        'num_permis',
        'client_type',
        'cin',
        'passport',
        'phone',
        'whatsapp_number',
        'email',
        'date_of_birth',
        'profession',
        'address',
        'city',
        'notes',
        'solvabilite',
        'incident',
        'type_incident',
        'entreprise_id',
        'succursale_id',
        'created_by',

        // Keep old fillables for backward compatibility
        'nom',
        'prenom',
        'type',
        'telephone',
        'adresse',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'incident' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function ($client) {
            if (empty($client->reference)) {
                $nextId = (static::max('id') ?? 0) + 1;
                $prefix = ($client->client_type === 'company' || $client->type === 'entreprise') ? 'ENT-' : 'CL-';
                $client->reference = $prefix . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }

            // Convert empty string fields to NULL to prevent SQL 1062 duplicate key error on unique indexes (like email, cin)
            $nullableFields = [
                'email', 'cin', 'phone', 'passport', 'whatsapp_number', 
                'num_permis', 'gerant', 'date_of_birth', 'profession', 
                'address', 'city', 'notes', 'type_incident', 'entreprise_id'
            ];

            foreach ($nullableFields as $field) {
                if (isset($client->attributes[$field]) && is_string($client->attributes[$field]) && trim($client->attributes[$field]) === '') {
                    $client->attributes[$field] = null;
                }
            }
        });
    }

    public function getFormattedReferenceAttribute()
    {
        if (!empty($this->attributes['reference'])) {
            return $this->attributes['reference'];
        }
        return 'CL-' . str_pad($this->id ?? 1, 5, '0', STR_PAD_LEFT);
    }

    public function newEloquentBuilder($query)
    {
        return new class($query) extends \Illuminate\Database\Eloquent\Builder {
            protected $mappings = [
                'nom' => 'last_name',
                'prenom' => 'first_name',
                'type' => 'client_type',
                'telephone' => 'phone',
                'adresse' => 'address',
            ];

            public function where($column, $operator = null, $value = null, $boolean = 'and')
            {
                if (is_string($column) && isset($this->mappings[$column])) {
                    $column = $this->mappings[$column];
                }
                return parent::where($column, $operator, $value, $boolean);
            }

            public function orWhere($column, $operator = null, $value = null)
            {
                if (is_string($column) && isset($this->mappings[$column])) {
                    $column = $this->mappings[$column];
                }
                return parent::orWhere($column, $operator, $value);
            }
        };
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    // Compatibility relation
    public function contrats(): HasMany
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'client_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'client_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'client_id');
    }

    public function communications(): HasMany
    {
        return $this->hasMany(Communication::class, 'client_id');
    }

    public function dossiers(): HasMany
    {
        return $this->hasMany(Dossier::class, 'client_id');
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'entreprise_id')->where('client_type', 'company');
    }

    public function employes(): HasMany
    {
        return $this->hasMany(Client::class, 'entreprise_id')->where('client_type', 'individual');
    }

    // Dynamic virtual mapping for legacy database columns
    public function getNomAttribute() { return $this->attributes['last_name'] ?? ''; }
    public function setNomAttribute($value) { $this->attributes['last_name'] = $value; }

    public function getPrenomAttribute() { return $this->attributes['first_name'] ?? ''; }
    public function setPrenomAttribute($value) { $this->attributes['first_name'] = $value; }

    public function getTypeAttribute() { return ($this->attributes['client_type'] ?? '') === 'company' ? 'entreprise' : 'particulier'; }
    public function setTypeAttribute($value) { 
        $this->attributes['client_type'] = ($value === 'entreprise' || $value === 'company') ? 'company' : 'individual';
    }

    public function getTelephoneAttribute() { return $this->attributes['phone'] ?? ''; }
    public function setTelephoneAttribute($value) { $this->attributes['phone'] = $value; }

    public function getAdresseAttribute() { return $this->attributes['address'] ?? ''; }
    public function setAdresseAttribute($value) { $this->attributes['address'] = $value; }

    public function getNomCompletAttribute()
    {
        if ($this->client_type === 'company') {
            return $this->company_name ?? $this->last_name;
        }
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
