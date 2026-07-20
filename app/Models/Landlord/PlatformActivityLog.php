<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformActivityLog extends Model
{
    protected $table = 'platform_activity_logs';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (!app()->runningUnitTests()) {
            $this->connection = 'landlord';
        }
    }

    public $timestamps = false;

    protected $fillable = [
        'platform_admin_id',
        'action',
        'description',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(PlatformAdmin::class, 'platform_admin_id');
    }
}
