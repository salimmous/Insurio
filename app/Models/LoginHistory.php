<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_fingerprint',
        'status',
        'failure_reason',
        'is_suspicious',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_suspicious' => 'boolean',
            'created_at'    => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'success'  => 'bg-emerald-100 text-emerald-700',
            'failed'   => 'bg-red-100 text-red-700',
            'locked'   => 'bg-orange-100 text-orange-700',
            default    => 'bg-slate-100 text-slate-700',
        };
    }

    public function getBrowserAttribute(): string
    {
        $ua = $this->user_agent ?? '';
        if (str_contains($ua, 'Firefox')) return 'Firefox';
        if (str_contains($ua, 'Chrome')) return 'Chrome';
        if (str_contains($ua, 'Safari')) return 'Safari';
        if (str_contains($ua, 'Edge')) return 'Edge';
        return 'Unknown Browser';
    }

    public function getOsAttribute(): string
    {
        $ua = $this->user_agent ?? '';
        if (str_contains($ua, 'Windows')) return 'Windows';
        if (str_contains($ua, 'Mac')) return 'macOS';
        if (str_contains($ua, 'Linux')) return 'Linux';
        if (str_contains($ua, 'Android')) return 'Android';
        if (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) return 'iOS';
        return 'Unknown OS';
    }
}
