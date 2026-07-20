<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    protected $fillable = [
        'tenant_id',
        'creator_name',
        'creator_email',
        'subject',
        'status',
        'priority',
        'messages',
    ];

    protected $casts = [
        'messages' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
