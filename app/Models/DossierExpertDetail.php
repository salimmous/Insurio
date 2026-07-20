<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DossierExpertDetail extends Model
{
    protected $table = 'dossier_expert_details';

    protected $fillable = [
        'dossier_id',
        'expert_name',
        'company',
        'phone',
        'visit_date',
        'visit_time',
        'report',
        'estimated_damage',
        'repair_cost',
        'repairable',
        'total_loss',
        'recommendations',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'estimated_damage' => 'decimal:2',
        'repair_cost' => 'decimal:2',
        'repairable' => 'boolean',
        'total_loss' => 'boolean',
    ];

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }
}
