<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantWebsiteConfig extends Model
{
    protected $table = 'tenant_website_configs';

    protected $fillable = [
        'theme_id',
        'content',
        'seo',
        'social_links',
        'custom_domain',
        'is_published',
    ];

    protected $casts = [
        'content' => 'array',
        'seo' => 'array',
        'social_links' => 'array',
        'is_published' => 'boolean',
    ];

    public function theme()
    {
        return $this->belongsTo(WebsiteTheme::class, 'theme_id');
    }
}
