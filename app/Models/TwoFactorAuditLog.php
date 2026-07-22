<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorAuditLog extends Model
{
    protected $table = 'two_factor_audit_logs';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'event',
        'ip_address',
        'user_agent',
        'device',
        'country',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
