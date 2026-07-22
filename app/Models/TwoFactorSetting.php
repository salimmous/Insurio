<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactorSetting extends Model
{
    protected $table = 'two_factor_settings';

    protected $fillable = [
        'force_2fa_all',
        'force_2fa_admins',
        'force_2fa_finance',
        'force_2fa_managers',
        'force_2fa_roles',
    ];

    protected $casts = [
        'force_2fa_all' => 'boolean',
        'force_2fa_admins' => 'boolean',
        'force_2fa_finance' => 'boolean',
        'force_2fa_managers' => 'boolean',
        'force_2fa_roles' => 'array',
    ];
}
