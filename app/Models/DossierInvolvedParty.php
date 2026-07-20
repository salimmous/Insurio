<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DossierInvolvedParty extends Model
{
    protected $table = 'dossier_involved_parties';

    protected $fillable = [
        'dossier_id',
        'name',
        'role',
        'phone',
        'email',
        'company',
        'notes',
    ];

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }
}
