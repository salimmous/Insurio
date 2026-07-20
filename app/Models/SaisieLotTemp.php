<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaisieLotTemp extends Model
{
    protected $table = 'saisie_lot_temp';

    protected $fillable = [
        'data',
        'statut',
        'logs',
        'fait_par',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fait_par');
    }
}
