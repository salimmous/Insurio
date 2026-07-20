<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DossierAccidentDetail extends Model
{
    protected $table = 'dossier_accident_details';

    protected $fillable = [
        'dossier_id',
        'accident_date',
        'accident_time',
        'accident_city',
        'accident_address',
        'accident_gps',
        'weather',
        'road_condition',
        'police_present',
        'ambulance_present',
        'witnesses',
        'number_of_vehicles',
        'responsible_party',
        'description',
        'sketch_path',
        'statement_client',
        'notes_employee',
        'notes_police',
        'notes_expert',
        'notes_insurance',
        'notes_garage',
    ];

    protected $casts = [
        'accident_date' => 'date',
        'police_present' => 'boolean',
        'ambulance_present' => 'boolean',
    ];

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }
}
