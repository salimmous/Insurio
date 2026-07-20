<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueContrat extends Model
{
    protected $table = 'historique_contrats';

    protected $fillable = [
        'contrat_id',
        'action',
        'description',
        'fait_par',
    ];

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(ContratAuto::class, 'contrat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fait_par');
    }
}
