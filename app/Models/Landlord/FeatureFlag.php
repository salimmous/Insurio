<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $table = 'feature_flags';

    protected $fillable = [
        'name',
        'description',
        'is_active_globally',
        'rules',
    ];

    protected $casts = [
        'is_active_globally' => 'boolean',
        'rules' => 'array',
    ];

    public static function isActive(string $flagName, string $tenantId = null): bool
    {
        $flag = self::where('name', $flagName)->first();
        if (!$flag) {
            return false;
        }

        if ($flag->is_active_globally) {
            return true;
        }

        if ($tenantId && $flag->rules && isset($flag->rules['tenants'])) {
            return in_array($tenantId, $flag->rules['tenants']);
        }

        return false;
    }
}
