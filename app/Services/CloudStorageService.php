<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class CloudStorageService
{
    /**
     * Store a file in cloud storage with fallback to local public disk.
     */
    public static function putFile(string $path, string $content, string $visibility = 'private'): ?string
    {
        $diskName = config('filesystems.default', 'public');
        
        try {
            $tenantId = function_exists('tenant') && tenant('id') ? tenant('id') : 'central';
            $fullPath = "tenants/{$tenantId}/" . ltrim($path, '/');

            $stored = Storage::disk($diskName)->put($fullPath, $content, $visibility);

            if ($stored) {
                return $fullPath;
            }

            Log::warning("Storage put failed on disk {$diskName} for path {$fullPath}. Falling back to local disk.");
            Storage::disk('public')->put($fullPath, $content, $visibility);
            return $fullPath;
        } catch (Throwable $e) {
            Log::error("CloudStorageService error during putFile: " . $e->getMessage(), [
                'path' => $path,
                'disk' => $diskName,
                'exception' => $e
            ]);

            // Graceful fallback to local storage
            try {
                $tenantId = function_exists('tenant') && tenant('id') ? tenant('id') : 'central';
                $fullPath = "tenants/{$tenantId}/" . ltrim($path, '/');
                Storage::disk('local')->put($fullPath, $content);
                return $fullPath;
            } catch (Throwable $fallbackErr) {
                Log::critical("Fallback local storage also failed: " . $fallbackErr->getMessage());
                return null;
            }
        }
    }

    /**
     * Get a temporary signed URL or asset URL for secure document access.
     */
    public static function getSecureUrl(string $path, int $expirationMinutes = 60): string
    {
        $diskName = config('filesystems.default', 'public');

        try {
            if ($diskName === 's3' && Storage::disk('s3')->exists($path)) {
                return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($expirationMinutes));
            }

            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }

            return Storage::url($path);
        } catch (Throwable $e) {
            Log::error("CloudStorageService getSecureUrl error: " . $e->getMessage(), ['path' => $path]);
            return asset('storage/' . $path);
        }
    }

    /**
     * Verify cloud storage connectivity and write capability for Health Checks.
     */
    public static function checkHealth(): array
    {
        $diskName = config('filesystems.default', 'public');
        $testFile = 'health-check/probe-' . time() . '.txt';

        try {
            Storage::disk($diskName)->put($testFile, 'health-ok');
            $exists = Storage::disk($diskName)->exists($testFile);
            Storage::disk($diskName)->delete($testFile);

            return [
                'status' => $exists ? 'healthy' : 'unhealthy',
                'disk' => $diskName,
                'message' => $exists ? 'Storage write probe successful' : 'Storage read check failed'
            ];
        } catch (Throwable $e) {
            return [
                'status' => 'unhealthy',
                'disk' => $diskName,
                'message' => $e->getMessage()
            ];
        }
    }
}
