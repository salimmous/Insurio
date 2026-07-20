<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;

class SystemBackup extends Model
{
    protected $table = 'system_backups';

    protected $fillable = [
        'filename',
        'disk',
        'size',
        'status',
    ];
}
