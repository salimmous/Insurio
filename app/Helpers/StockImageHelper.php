<?php

namespace App\Helpers;

class StockImageHelper
{
    /**
     * Get image details by key.
     *
     * @param string $key
     * @return array
     */
    public static function get(string $key): array
    {
        $library = config('stock_images', []);

        if (isset($library[$key])) {
            return $library[$key];
        }

        return [
            'title' => 'Insurio Premium Insurance',
            'local_path' => '/images/stock/happy_family_advisor.png',
            'url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=1600&q=80',
            'alt' => 'Insurio Assurance',
        ];
    }

    /**
     * Get URL for image (prefer local file if exists, fallback to 4K Unsplash stock photo URL).
     *
     * @param string $key
     * @return string
     */
    public static function url(string $key): string
    {
        $item = static::get($key);
        
        if (file_exists(public_path($item['local_path']))) {
            return asset($item['local_path']);
        }

        return $item['url'];
    }

    /**
     * Get all 30 stock images.
     *
     * @return array
     */
    public static function all(): array
    {
        return config('stock_images', []);
    }
}
