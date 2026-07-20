<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'nom',
        'chemin_fichier',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
