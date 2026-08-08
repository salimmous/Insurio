<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueRenouvellement extends Model
{
    use HasFactory;

    protected $table = 'historique_renouvellements';

    protected $fillable = [
        'contrat_id',
        'anc_date_effet',
        'anc_date_echeance',
        'nouv_date_effet',
        'nouv_date_echeance',
        'prime_totale',
    ];

    protected $casts = [
        'anc_date_effet' => 'date',
        'anc_date_echeance' => 'date',
        'nouv_date_effet' => 'date',
        'nouv_date_echeance' => 'date',
        'prime_totale' => 'decimal:2',
    ];

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contrat_id');
    }
}
