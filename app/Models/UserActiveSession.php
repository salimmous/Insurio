<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActiveSession extends Model
{
    protected $table = 'user_active_sessions';

    protected $fillable = [
        'session_id',
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
        'status',
        'login_at',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'login_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isCurrentSession(): bool
    {
        return $this->session_id === session()->getId();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
