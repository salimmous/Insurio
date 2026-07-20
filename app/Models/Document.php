<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'nom',
        'chemin_fichier',
        'client_id',
        'contract_id',
        'type',
        'file_path',
        'file_name',
        'mime_type',
        'uploaded_by',
    ];

    protected static function booted()
    {
        static::saving(function ($document) {
            // Automatically set polymorphic columns if client_id is set
            if (empty($document->documentable_id) && !empty($document->client_id)) {
                $document->documentable_id = $document->client_id;
                $document->documentable_type = Client::class;
            }
        });
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
