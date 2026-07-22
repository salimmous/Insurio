<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteTheme extends Model
{
    protected $connection = 'mysql';
    protected $table = 'website_themes';

    protected $fillable = [
        'name',
        'slug',
        'version',
        'author',
        'description',
        'colors',
        'typography',
        'components_config',
        'is_locked',
        'is_active',
    ];

    protected $casts = [
        'colors' => 'array',
        'typography' => 'array',
        'components_config' => 'array',
        'is_locked' => 'boolean',
        'is_active' => 'boolean',
    ];
}
