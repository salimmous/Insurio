<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;

class PlatformWebhook extends Model
{
    protected $table = 'platform_webhooks';

    protected $fillable = [
        'event',
        'payload',
        'sent_to',
        'status',
        'response_code',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
