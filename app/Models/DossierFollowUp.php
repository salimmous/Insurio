<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DossierFollowUp extends Model
{
    protected $table = 'dossier_follow_ups';

    protected $fillable = [
        'dossier_id',
        'date',
        'employee_id',
        'reminder_at',
        'priority',
        'notes',
        'completed',
    ];

    protected $casts = [
        'date' => 'date',
        'reminder_at' => 'datetime',
        'completed' => 'boolean',
    ];

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employee_id');
    }
}
