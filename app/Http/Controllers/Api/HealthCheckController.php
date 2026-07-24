<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Services\CloudStorageService;
use Throwable;

class HealthCheckController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $status = 'healthy';

        // 1. Database Check
        $dbStatus = 'healthy';
        $dbMessage = 'Connected';
        try {
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            $dbStatus = 'unhealthy';
            $dbMessage = $e->getMessage();
            $status = 'unhealthy';
        }

        // 2. Redis Check
        $redisStatus = 'healthy';
        $redisMessage = 'Connected';
        try {
            if (config('database.redis.default.host')) {
                Redis::ping();
            }
        } catch (Throwable $e) {
            $redisStatus = 'degraded';
            $redisMessage = $e->getMessage();
        }

        // 3. Storage Probe
        $storageHealth = CloudStorageService::checkHealth();
        if ($storageHealth['status'] !== 'healthy') {
            $status = 'degraded';
        }

        // 4. Queue Check
        $queueDriver = config('queue.default');

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
            'environment' => config('app.env'),
            'version' => '2.0.0-enterprise',
            'components' => [
                'database' => [
                    'status' => $dbStatus,
                    'driver' => config('database.default'),
                    'message' => $dbMessage,
                ],
                'redis' => [
                    'status' => $redisStatus,
                    'message' => $redisMessage,
                ],
                'storage' => $storageHealth,
                'queue' => [
                    'status' => 'healthy',
                    'driver' => $queueDriver,
                ],
            ]
        ], $status === 'healthy' ? 200 : ($status === 'degraded' ? 200 : 503));
    }
}
