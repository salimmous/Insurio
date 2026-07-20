<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DossierChequeDetail extends Model
{
    protected $table = 'dossier_cheque_details';

    protected $fillable = [
        'dossier_id',
        'cheque_number',
        'bank',
        'issue_date',
        'deposit_date',
        'clearance_date',
        'returned_date',
        'reason',
        'image_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'deposit_date' => 'date',
        'clearance_date' => 'date',
        'returned_date' => 'date',
    ];

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }
}
