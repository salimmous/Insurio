<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityAuditLog extends Model
{
    public $timestamps = false;

    protected $table = 'security_audit_logs';

    protected $fillable = [
        'uuid',
        'event_type',
        'status',
        'user_id',
        'user_name',
        'user_email',
        'agency_name',
        'branch_name',
        'role_name',
        'ip_address',
        'user_agent',
        'browser',
        'os',
        'device',
        'country',
        'city',
        'notes',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Enforce Immutability: Block all update and delete operations.
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            throw new \RuntimeException('Security audit logs are immutable and cannot be updated.');
        });

        static::deleting(function ($model) {
            throw new \RuntimeException('Security audit logs are permanent and cannot be deleted.');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
