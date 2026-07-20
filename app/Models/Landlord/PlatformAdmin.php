<?php

namespace App\Models\Landlord;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PlatformAdmin extends Authenticatable
{
    use Notifiable;

    protected $table = 'platform_admins';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (!app()->runningUnitTests()) {
            $this->connection = 'landlord';
        }
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}
