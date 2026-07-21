<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrustedDevice extends Model
{
    protected $fillable = [
        'user_id',
        'device_fingerprint',
        'device_name',
        'ip_address',
        'confirmed_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'confirmed_at' => 'datetime',
            'expires_at'   => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
