<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Import extends Model
{
    protected $table = 'imports';

    protected $fillable = [
        'user_id',
        'type',
        'file',
        'total_rows',
        'success_rows',
        'failed_rows',
        'errors',
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
