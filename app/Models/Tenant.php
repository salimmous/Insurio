<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $casts = [
        'data' => 'array',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'db_name',
            'status',
            'plan',
            'plan_id',
            'created_by',
            'logo_path',
            'favicon_path',
            'couleur_primaire',
            'couleur_secondaire',
        ];
    }

    public function subscriptionPlan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Landlord\Plan::class, 'plan_id');
    }

    public function isExpired(): bool
    {
        if (empty($this->subscription_end_date)) {
            return false;
        }

        try {
            return \Carbon\Carbon::parse($this->subscription_end_date)->endOfDay()->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getDaysRemaining(): ?int
    {
        if (empty($this->subscription_end_date)) {
            return null;
        }

        try {
            $end = \Carbon\Carbon::parse($this->subscription_end_date)->startOfDay();
            $now = \Carbon\Carbon::now()->startOfDay();
            if ($end->isPast()) {
                return -$now->diffInDays($end);
            }
            return (int) $now->diffInDays($end);
        } catch (\Exception $e) {
            return null;
        }
    }
}
